<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MPenempatan;
use DB;

class MPenempatanController extends Controller
{
    public function index(Request $request) {
        $this->validate($request, [
            'search'         => 'nullable|string',
            'sort_by'        => 'nullable|in:name',
            'sort_order'     => 'nullable|in:asc,desc',
            'page'           => 'nullable|numeric',
            'page_size'      => 'nullable|numeric',
        ]);
        try{
            $response = MPenempatan::when($request->search, function($q) use($request){
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
            ->paginate($request->page_size ?? 10000);

            return $this->respond($response, 'berhasil menampilkan data!', 200);

        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage(),500,null);
        }
    }

    public function store(Request $request) {

        $this->validate($request, ['name' => 'required' ]);

        DB::beginTransaction();
        try {
            $response = MPenempatan::create([ 'name' => $request->name ]);
            DB::commit();
            return $this->respond($response, 'berhasil menyimpan data', 200);
        } catch(\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal menyimpan data', 500, $e->getMessage());
        }
    }

    public function show($id) {
        try{
            $response = MPenempatan::findOrFail($id);
            return $this->respond($response, 'berhasil menampilkan data', 200);
        }catch(\Exception $e){
            return $this->respondWithError('gagal menampilkan data',500, $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $data = MPenempatan::find($id);
        if (!$data)
            return $this->respondNotFound();

        $this->validate($request, [ 'name' => 'required' ]);

        DB::beginTransaction();
        try {
            $data->update([
                'name' =>  isset($request->name) ? $request->name : $data->name,
            ]);
            DB::commit();
            return $this->respond($data, 'berhasil mengubah data', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal mengubah data', 500, $e->getMessage());
        }

    }

    public function destroy($id) {
        $data = MPenempatan::find($id);
        if (!$data)
            return $this->respondNotFound('gagal menghapus data');

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
