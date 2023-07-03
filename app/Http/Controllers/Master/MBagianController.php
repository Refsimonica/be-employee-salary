<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MBagian;
use DB;

class MBagianController extends Controller
{
    public function index(Request $request) {

        $this->validate($request, [
            'departemen_id'   => 'nullable|numeric|exists:App\Models\MDepartemen,id,deleted_at,NULL',
            'search'          => 'nullable|string',
            'sort_by'         => 'nullable|in:name',
            'sort_order'      => 'nullable|in:asc,desc',
            'page'            => 'nullable|numeric',
            'page_size'       => 'nullable|numeric',
        ]);

        try{
            $response = MBagian::with('departemen')
            ->when($request->search, function($q) use($request){
                $q->where('name', 'ilike', '%'.$request->search.'%')
                ->orWhereHas('departemen', function ($q2) use ($request) {
                    $q2->where('name', 'ilike', '%' . $request->search . '%');
                });

            })
            ->when($request->input('departemen_id'), function($query) use ($request){
                return $query->where('m_departemen_id', $request->input('departemen_id'));
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
            'name'          => 'required',
            'departemen_id' => 'required|numeric|exists:App\Models\MDepartemen,id,deleted_at,NULL'
        ]);

        DB::beginTransaction();
        try {
            $response = MBagian::create([
                'name'            => $request->name,
                'm_departemen_id' => $request->departemen_id
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
            $response = MBagian::with('departemen')->find($id);
            return $this->respond($response, 'berhasil menampilkan data', 200);
        }catch(\Exception $e){
            return $this->respondWithError('gagal menampilkan data',500, $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $data = MBagian::find($id);
        if (!$data)
            return $this->respondNotFound();

        $this->validate($request, [
            'name'          => 'required',
            'departemen_id' => 'required|numeric|exists:App\Models\MDepartemen,id,deleted_at,NULL'
        ]);

        DB::beginTransaction();
        try {
            $data->update([
                'name'            =>  isset($request->name) ? $request->name : $data->name,
                'm_departemen_id' =>  isset($request->departemen_id) ? $request->departemen_id : $data->m_departemen_id
            ]);
            DB::commit();
            return $this->respond($data, 'berhasil mengubah data', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal mengubah data', 500, $e->getMessage());
        }
    }

    public function destroy($id) {
        $data = MBagian::find($id);
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
