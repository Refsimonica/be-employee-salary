<?php

namespace App\Http\Controllers\TRansaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\AbsensiImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MKaryawan;
use App\Models\TAbsensi;
use App\Models\TAbsensiKaryawan;
use App\Models\Setting;
use DB;
use File;
use Illuminate\Validation\Rule;

class AbsensiKaryawanController extends Controller {

    public function index(Request $request) {
        $this->validate($request, [
            'search'         => 'nullable|string',
            'sort_by'        => 'nullable|in:name',
            'sort_order'     => 'nullable|in:asc,desc',
            'page'           => 'nullable|numeric',
            'page_size'      => 'nullable|numeric',
        ]);
        try{
            $data = TAbsensi::when($request->search, function($q) use($request){
                $q->where('start_date', 'ilike', '%'.$request->search.'%')
                    ->orWhere('end_date', 'ilike', '%'.$request->search.'%');
            })
            ->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
            ->paginate($request->page_size ?? 10000);

            return $this->respond($data, 'berhasil menampilkan data!', 200);

        } catch(\Exception $e){
            report($e);
            return $this->respondWithError($e->getMessage(),500,null);
        }
    }

    public function import(Request $request) {

        $this->validate($request, [
            'file'          => 'required|mimes:xlsx,xls',
        ]);

        try{
            $imports = Excel::toArray(new AbsensiImport, request()->file('file'));
            $data_absensi = $imports[0] ?? $imports;
            $label_absensi = $data_absensi[0];

            $response = [];
            foreach ($data_absensi as $absensi) {
                $response [$absensi[2]][] = $absensi;
            }

            DB::beginTransaction();

            $umk_salary = Setting::where('name', 'salary')->first();
            $umk_salary = json_decode($umk_salary->value);

            if (request()->file('file')) {
                $file = request()->file('file')->store('files/salary', 'public');

                $absensi = TAbsensi::create([
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'file' => $file,
                    'status' => false,
                ]);

                $result = [];
                foreach ($response as $key => $values) {

                    $detail = [];
                    for ($index = 0; $index < sizeof($values); $index++) {
                        $row = [];
                        for ($index2 = 0; $index2 < sizeof($values[$index]); $index2++) {
                            $row [preg_replace('/[^A-Za-z0-9\-]/', '', $label_absensi[$index2])] = $values[$index][$index2];

                            if ($label_absensi[$index2] == 'Absent') {
                                if ($values[$index][$index2] == 'True' || $values[$index][$index2] == 'true') {
                                    $row['status'] = 'absent';
                                } else {
                                    $row['status'] = 'attend';
                                }
                            }
                        }
                        $detail [] = $row;
                    }

                    if (is_numeric($values[0][0])) {

                        $overtime = 0;
                        $absent = 0;
                        $absent_cut = 0;
                        $bpjs_kesehatan_cut = 0;
                        $bpjs_ketenagakerjaan_cut = 0;
                        $total_regular_salary = 0;
                        $total_overtime_salary = 0;
                        $total_salary = 0;
                        $total_overtime_reguler = 0;

                        $karyawan = MKaryawan::where('no_karyawan', $key)->first();

                        if (!empty($karyawan)) {
                            if (!empty($karyawan) && $karyawan->no_bpjs_kesehatan != '' && $request->bpjs_cut == 'true')
                                $bpjs_kesehatan_cut = $umk_salary->bpjs_kesehatan;

                            if (!empty($karyawan) && $karyawan->no_bpjs_ketenagakerjaan != '' && $request->bpjs_cut == 'true')
                                $bpjs_ketenagakerjaan_cut = $umk_salary->bpjs_ketenagakerjaan;

                            foreach ($detail as $key) {
                                $date = date('Y-m-d',strtotime(str_replace('/', '-', $key['Date'])));

                                $day = date('w',strtotime($date));

                                if ($day != 0 && $day != 6)
                                    $total_regular_salary += $umk_salary->daily;

                                if (($day != 0 && $day != 6) && $key['Absent'] == 'True') {
                                    $absent += 1;
                                    $absent_cut += $umk_salary->daily;
                                }

                                if (($day == 0 || $day == 6) && $key['Absent'] != 'True') {
                                    $strtotime = strtotime($key['WorkTime']);
                                    $hours_total = date('h', $strtotime);
                                    $overtime += $hours_total;

                                    if ($hours_total > 0 && $hours_total == 1) {
                                        $total_overtime_salary += $umk_salary->l1;
                                    } else if ($hours_total > 1 && $hours_total == 2) {
                                        $total_overtime_salary += $umk_salary->l1 + $umk_salary->l2;
                                    } else if ($hours_total > 2) {
                                        for ($i=1; $i <= $hours_total; $i++) {
                                            if($i == 1)
                                                $total_overtime_salary += $umk_salary->l1;

                                            if($i == 2)
                                                $total_overtime_salary += $umk_salary->l2;

                                            if($i > 2)
                                                $total_overtime_salary += $umk_salary->l3;
                                        }
                                    }
                                }

                            }

                            $result [] = TAbsensiKaryawan::create([
                                't_absensi_id'              => $absensi->id,
                                'no_acc'                    => $values[0][0],
                                'no_absent'                 => $values[0][1],
                                'no_karyawan'               => $values[0][2],
                                'absent_name'               => $values[0][3],
                                'm_karyawan_id'             => !empty($karyawan) ? $karyawan->id : null,
                                'absent_detail'             => json_encode($detail),
                                'absent'                    => $absent,
                                'overtime'                  => $overtime,
                                'absent_cut'                => $absent_cut,
                                'bpjs_ketenagakerjaan_cut'  => $bpjs_ketenagakerjaan_cut,
                                'bpjs_kesehatan_cut'        => $bpjs_kesehatan_cut,
                                'total_regular_salary'      => $total_regular_salary,
                                'total_overtime_salary'     => $total_overtime_salary,
                                'total_salary'              => $total_salary,
                                'bank_name'                 => $karyawan->bank_name,
                                'no_rekening'               => $karyawan->no_rekening
                            ]);
                        }

                    }

                }

            }

            DB::commit();
            return $this->respond($absensi, 'berhasil menyimpan data', 200);

        } catch(\Exception $e){
            DB::rollback();
            report($e);
            return $this->respondWithError('gagal menampilkan data', 500, $e->getMessage());
        }
    }

    public function show($id) {
        try{
            $response = TAbsensi::whereHas('absensi_karyawan', function ($q) {
                $q->whereNotNull('m_karyawan_id')->orderBy('absent_name', 'ASC');
            })
            ->with(['absensi_karyawan' => function ($q) {
                $q->whereNotNull('m_karyawan_id')->orderBy('absent_name', 'ASC');
            }])
            ->findOrFail($id);
            return $this->respond($response, 'berhasil menampilkan data', 200);
        }catch(\Exception $e){
            report($e);
            return $this->respondWithError('gagal menampilkan data', 500, $e->getMessage());
        }
    }

    public function update(Request $request, $id) {

        $data = TAbsensi::findOrFail($id);
        $current_file = $data->file;

        $request->validate([
            'file'          => 'mimes:xls,xlsx',
            'start_date'    => ['required', Rule::unique('t_absensi')->ignore($data->id, 'id')],
            'end_date'      => ['required', Rule::unique('t_absensi')->ignore($data->id, 'id')],
        ]);

        try{

            $file = null;

            if (request()->file('file'))
                $file = request()->file('file')->store('files/salary', 'public');

            DB::beginTransaction();

            $data->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'file' => $file != null ? $file : $data->file,
                'status' => false,
            ]);

            if (request()->file('file')) {

                TAbsensiKaryawan::where('t_absensi_id', $data->id)->forceDelete();
                File::delete(public_path('storage/'.$current_file));

                $imports = Excel::toArray(new AbsensiImport, request()->file('file'));
                $data_absensi = $imports[0] ?? $imports;
                $label_absensi = $data_absensi[0];

                $response = [];
                foreach ($data_absensi as $absensi) {
                    $response [$absensi[2]][] = $absensi;
                }

                $result = [];
                foreach ($response as $key => $values) {

                    $detail = [];
                    for ($index=0; $index < sizeof($values); $index++) {
                        $row = [];
                        for ($index2=0; $index2 < sizeof($values[$index]); $index2++) {
                            $row [preg_replace('/[^A-Za-z0-9\-]/', '', $label_absensi[$index2])] = $values[$index][$index2];
                        }
                        $detail [] = $row;
                    }

                    if (is_numeric($values[0][0])) {

                        $karyawan = MKaryawan::where('no_karyawan', $key)->first();
                        $result [] = TAbsensiKaryawan::create([
                            't_absensi_id' => $data->id,
                            'no_acc' => $values[0][0],
                            'no_absent' => $values[0][1],
                            'no_karyawan' => $values[0][2],
                            'absent_name' => $values[0][3],
                            'm_karyawan_id' => !empty($karyawan) ? $karyawan->id : null,
                            'absent_detail' => json_encode($detail)
                        ]);

                    }

                }

            }

            DB::commit();
            return $this->respond($data, 'berhasil menyimpan data', 200);

        } catch(\Exception $e){
            DB::rollback();
            report($e);
            return $this->respondWithError('gagal menampilkan data', 500, $e->getMessage());
        }
    }

    public function destroy($id) {
        $data = TAbsensi::find($id);
        $current_file = $data->file;

        if (!$data)
            return $this->respondNotFound(null, 404);

        DB::beginTransaction();
        try {
            $data->forceDelete();
            File::delete(public_path('storage/'.$current_file));

            DB::commit();
            return $this->success('berhasil menghapus data', 200);
        } catch (\Exception $e) {
            DB::rollback();
            report($e);
            return $this->respondWithError('gagal menghapus data', 500, $e->getMessage());
        }
    }

    public function updateAbsentDays(Request $request) {

        $request->validate(['id' => 'required', 'absent' => 'required' ]);

        try {

            $umk_salary = Setting::where('name', 'salary')->first();
            $umk_salary = json_decode($umk_salary->value);

            DB::beginTransaction();
            $data = TAbsensiKaryawan::findOrFail($request->id);

            $data->update([
                'absent'     => $request->absent,
                'absent_cut' => $request->absent * $umk_salary->daily,
            ]);
            DB::commit();
            return $this->respond($data, 'berhasil mengubah data', 200);
        } catch (\Throwable $th) {
            DB::rollback();
            report($th);
            return $this->respondWithError('gagal mengubah data', 200, $th->getMessage());
        }
    }

    public function updateOvertimeHour(Request $request) {
        $request->validate(['id' => 'required', 'overtime' => 'required' ]);

        try {

            $umk_salary = Setting::where('name', 'salary')->first();
            $umk_salary = json_decode($umk_salary->value);

            DB::beginTransaction();
            $data = TAbsensiKaryawan::findOrFail($request->id);

            $data->update([
                'overtime'                => $request->overtime,
                'total_overtime_salary'   => $request->overtime * $umk_salary->hourly,
            ]);
            DB::commit();
            return $this->respond($data, 'berhasil mengubah data', 200);
        } catch (\Throwable $th) {
            DB::rollback();
            report($th);
            return $this->respondWithError('gagal mengubah data', 500, $th->getMessage());
        }
    }

    public function updateAbsentDetail(Request $request) {

        try {
            $umk_salary = Setting::where('name', 'salary')->first();
            $umk_salary = json_decode($umk_salary->value);

            DB::beginTransaction();
            $data = TAbsensiKaryawan::findOrFail($request->id);
            $absent_detail = json_decode($data->absent_detail);

            switch ($request->status) {
                case 'attend':
                    // if (condition) {
                    //     # code...
                    // }
                    $absent_cut = $data->absent_cut - $umk_salary->daily;
                    $absent = $data->absent - 1;
                break;
                case 'sick':
                    $absent_cut = $data->absent_cut - $umk_salary->daily;
                    $absent = $data->absent - 1;
                break;
                default:
                    $absent_cut = $data->absent_cut + $umk_salary->daily;
                    $absent = $data->absent + 1;
                break;
            }

            $data->update([
                'absent_detail' => json_encode($request->absent),
                'absent_cut'    => $absent_cut,
                'absent'        => $absent
            ]);

            DB::commit();
            return $this->respond($request->absent, 'berhasil mengubah data', 200);
        } catch (\Throwable $th) {
            DB::rollback();
            report($th);
            return $this->respondWithError('gagal mengubah data', 500, $th->getMessage());
        }
    }
}
