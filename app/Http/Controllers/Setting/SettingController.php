<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use DB;

class SettingController extends Controller {

    public function index(Request $request) {

        $this->validate($request, [
            'search'          => 'nullable|string',
            'sort_by'         => 'nullable|in:name',
            'sort_order'      => 'nullable|in:asc,desc',
            'page'            => 'nullable|numeric',
            'page_size'       => 'nullable|numeric',
        ]);

        try{
            $response = Setting::when($request->search, function($q) use($request){
                $q->where('name', 'ilike', '%'.$request->search.'%');
            })
            ->orderBy($request->sort_by ?? 'name', $request->sort_order ?? 'asc')
            ->paginate($request->page_size ?? '');

            return $this->respond($response, 'berhasil menampilkan data!', 200);

        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage(),500,null);
        }
    }

    public function show($name) {
        try{
            $response = Setting::where('name', $name)->first();
            if (!$response)
                return $this->respondNotFound('Data tidak ditemukan atau sudah dihapus !');

            return $this->respond($response, 'berhasil menampilkan data', 200);
        }catch(\Exception $e){
            return $this->respondWithError('gagal menampilkan data',500, $e->getMessage());
        }
    }

    public function update(Request $request) {


        $this->validate($request, [
            'name'  => 'required'
        ]);

        DB::beginTransaction();

        try {

            $data = Setting::where('name', $request->name)->first();
            if (!$data)
                return $this->respondNotFound('Data tidak ditemukan atau sudah dihapus !');

            $value = json_encode($request->except(['name']));

            $data->update([ 'value' => $value ]);

            DB::commit();
            return $this->respond(['name' => $request->name, 'value' => $value], 'berhasil mengubah data', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal mengubah data', 500, $e->getMessage());
        }

    }
}
