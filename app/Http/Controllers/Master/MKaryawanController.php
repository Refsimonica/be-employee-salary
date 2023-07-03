<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\MKaryawan;
use App\Models\User;
use DB;

class MKaryawanController extends Controller {

    public function index(Request $request) {

        $this->validate($request, [
            'departemen_id'   => 'nullable|numeric|exists:App\Models\MDepartemen,id,deleted_at,NULL',
            'jabatan_id'      => 'nullable|numeric|exists:App\Models\MJabatan,id,deleted_at,NULL',
            'bagian_id'       => 'nullable|numeric|exists:App\Models\MBagian,id,deleted_at,NULL',
            'penempatan_id'   => 'nullable|numeric|exists:App\Models\MPenempatan,id,deleted_at,NULL',
            'search'          => 'nullable|string',
            'sort_by'         => 'nullable|in:name',
            'sort_order'      => 'nullable|in:asc,desc',
            'page'            => 'nullable|numeric',
            'page_size'       => 'nullable|numeric',
        ]);

        try{
            $response = MKaryawan::with('departemen', 'bagian', 'jabatan', 'penempatan')
            ->when($request->search, function($q) use($request){
                $q->where('name', 'ilike', '%'.$request->search.'%');
            })
            ->when($request->input('departemen_id'), function($query) use ($request){
                return $query->where('m_departemen_id', $request->input('departemen_id'));
            })
            ->when($request->input('jabatan_id'), function($query) use ($request){
                return $query->where('m_jabatan_id', $request->input('jabatan_id'));
            })
            ->when($request->input('bagian_id'), function($query) use ($request){
                return $query->where('m_bagian_id', $request->input('bagian_id'));
            })
            ->when($request->input('penempatan_id'), function($query) use ($request){
                return $query->where('m_penempatan_id', $request->input('penempatan_id'));
            })
            ->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
            ->paginate($request->page_size ?? 10000);

            return $this->respond($response, 'berhasil menampilkan data!', 200);

        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage(),500,null);
        }
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name'            => 'required',
            'departemen_id'   => 'required|numeric|exists:App\Models\MDepartemen,id,deleted_at,NULL',
            'jabatan_id'      => 'required|numeric|exists:App\Models\MJabatan,id,deleted_at,NULL',
            'bagian_id'       => 'required|numeric|exists:App\Models\MBagian,id,deleted_at,NULL',
            'penempatan_id'   => 'required|numeric|exists:App\Models\MPenempatan,id,deleted_at,NULL',
            'gender'          => 'required|in:Laki-laki,Perempuan',
            'phone_number'    => 'required',
            'email'           => 'required|email|unique:users|max:255',
            'religion'        => 'required',
            'no_ktp'          => 'required',
        ]);

        DB::beginTransaction();
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('asdw')
            ]);

            $response = MKaryawan::create([
                'name'                      => $request->name,
                'm_departemen_id'           => $request->departemen_id,
                'm_jabatan_id'              => $request->jabatan_id,
                'm_bagian_id'               => $request->bagian_id,
                'm_penempatan_id'           => $request->penempatan_id,
                'gender'                    => $request->gender,
                'phone_number'              => $request->phone_number,
                'email'                     => $request->email,
                'religion'                  => $request->religion,
                'no_ktp'                    => $request->no_ktp,
                'user_id'                   => $user->id,
                'no_bpjs_kesehatan'         => $request->no_bpjs_kesehatan,
                'no_bpjs_ketenagakerjaan'   => $request->no_bpjs_ketenagakerjaan,
                'no_karyawan'               => $request->no_karyawan,
                'no_kk'                     => $request->no_kk,
                'no_rekening'               => $request->no_rekening,
                'bank_name'                 => $request->bank_name,
                'emergency_phone_number'    => $request->emergency_phone_number,
                'education'                 => $request->education,
                'address'                   => $request->address,
                'place_birth'               => $request->place_birth,
                'date_birth'                => $request->date_birth,
                'description'               => $request->description,
            ]);

            DB::commit();
            return $this->respond($response, 'berhasil menyimpan data', 200);
        } catch(\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal menyimpan data', 500, $e->getMessage());
        }
    }

    public function show($id) {
        try{
            $response = MKaryawan::with('departemen', 'bagian', 'jabatan', 'penempatan')->find($id);
            if (!$response)
                return $this->respondNotFound();

            return $this->respond($response, 'berhasil menampilkan data', 200);
        }catch(\Exception $e){
            return $this->respondWithError('gagal menampilkan data',500, $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $data = MKaryawan::find($id);
        if (!$data)
            return $this->respondNotFound();

        $this->validate($request, [
            'name'            => 'required',
            'departemen_id'   => 'required|numeric|exists:App\Models\MDepartemen,id,deleted_at,NULL',
            'jabatan_id'      => 'required|numeric|exists:App\Models\MJabatan,id,deleted_at,NULL',
            'bagian_id'       => 'required|numeric|exists:App\Models\MBagian,id,deleted_at,NULL',
            'penempatan_id'   => 'required|numeric|exists:App\Models\MPenempatan,id,deleted_at,NULL',
            'gender'          => 'required|in:Laki-laki,Perempuan',
            'phone_number'    => 'required',
            'email'           => 'required|email',
            'religion'        => 'required',
            'no_ktp'          => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data->update([
                'name'                      => isset($request->name) ? $request->name : $data->name,
                'm_departemen_id'           => isset($request->departemen_id) ? $request->departemen_id : $data->m_departemen_id,
                'm_jabatan_id'              => isset($request->jabatan_id) ? $request->jabatan_id : $data->m_jabatan_id,
                'm_bagian_id'               => isset($request->bagian_id) ? $request->bagian_id : $data->m_bagian_id,
                'm_penempatan_id'           => isset($request->penempatan_id) ? $request->penempatan_id : $data->m_penempatan_id,
                'gender'                    => isset($request->gender) ? $request->gender : $data->gender,
                'phone_number'              => isset($request->phone_number) ? $request->phone_number : $data->phone_number,
                'email'                     => isset($request->email) ? $request->email : $data->email,
                'religion'                  => isset($request->religion) ? $request->religion : $data->religion,
                'no_ktp'                    => isset($request->no_ktp) ? $request->no_ktp : $data->no_ktp,
                'no_bpjs_kesehatan'         => isset($request->no_bpjs_kesehatan) ? $request->no_bpjs_kesehatan : $data->no_bpjs_kesehatan,
                'no_bpjs_ketenagakerjaan'   => isset($request->no_bpjs_ketenagakerjaan) ? $request->no_bpjs_ketenagakerjaan : $data->no_bpjs_ketenagakerjaan,
                'no_karyawan'               => isset($request->no_karyawan) ? $request->no_karyawan : $data->no_karyawan,
                'no_kk'                     => isset($request->no_kk) ? $request->no_kk : $data->no_kk,
                'no_rekening'               => isset($request->no_rekening) ? $request->no_rekening : $data->no_rekening,
                'bank_name'                 => isset($request->bank_name) ? $request->bank_name : $data->bank_name,
                'emergency_phone_number'    => isset($request->emergency_phone_number) ? $request->emergency_phone_number : $data->emergency_phone_number,
                'education'                 => isset($request->education) ? $request->education : $data->education,
                'address'                   => isset($request->address) ? $request->address : $data->address,
                'place_birth'               => isset($request->place_birth) ? $request->place_birth : $data->place_birth,
                'date_birth'                => isset($request->date_birth) ? $request->date_birth : $data->date_birth,
                'description'               => isset($request->description) ? $request->description : $data->description,
            ]);
            DB::commit();
            return $this->respond($data, 'berhasil mengubah data', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal mengubah data', 500, $e->getMessage());
        }
    }

    public function destroy($id) {
        $data = MKaryawan::find($id);
        if (!$data)
            return $this->respondNotFound(null, 404);

        DB::beginTransaction();
        try {
            $data->delete();
            DB::commit();
            return $this->success('berhasil menghapus data', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal menghapus data', 500, $e->getMessage());
        }
    }
}
