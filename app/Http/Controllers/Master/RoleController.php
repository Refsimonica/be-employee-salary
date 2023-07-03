<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Menu;
use DB;

class RoleController extends Controller {

    public function index(Request $request) {

        $this->validate($request, [
            'search'          => 'nullable|string',
            'sort_by'         => 'nullable|in:name,id',
            'sort_order'      => 'nullable|in:asc,desc',
            'page'            => 'nullable|numeric',
            'page_size'       => 'nullable|numeric',
        ]);

        try{
            $response = Role::with('permissions')
                ->when($request->search, function($q) use($request){
                    $q->where('name', 'ilike', '%'.$request->search.'%');
                })
                ->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
                ->paginate($request->page_size ?? '');

            return $this->respond($response, 'berhasil menampilkan data!', 200);

        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage(),500,$e->getMessage());
        }
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name'              => 'required',
            'permission_id.*.*' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            $response = Role::create([ 'name' => $request->name, 'guard_name' => 'web' ]);

            $all_permission = [];
            foreach ($request->permission_id as $permission_id) {
                foreach ($permission_id as $key => $value) {
                    if ($value != "false") {
                        $all_permission [] = $value;
                    }
                }
            }

            $response->syncPermissions($all_permission);
            DB::commit();
            return $this->respond($response, 'berhasil menyimpan data', 200);
        } catch(\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal menyimpan data', 500, $e->getMessage());
        }
    }

    public function show($id) {
        try{
            $response = Role::with('permissions')->find($id);
            return $this->respond($response, 'berhasil menampilkan data', 200);
        }catch(\Exception $e){
            return $this->respondWithError('gagal menampilkan data',500, $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $data = Role::find($id);

        if (!$data)
            return $this->respondNotFound();

        $this->validate($request, [
            'name'          => 'required',
            'permission_id.*' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            $data->update([ 'name' =>  isset($request->name) ? $request->name : $data->name ]);

            $all_permission = [];
            foreach ($request->permission_id as $permission_id) {
                foreach ($permission_id as $key => $value) {
                    if ($value != "false") {
                        $all_permission [] = $value;
                    }
                }
            }

            $data->syncPermissions($all_permission);
            DB::commit();
            return $this->respond($data, 'berhasil mengubah data', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal mengubah data', 500, $e->getMessage());
        }
    }

    public function destroy($id) {

        try {
            $role = Role::find($id);
            $users = User::role($role->name)->get();
            if($role){
                if (count($users) > 0) {
                    return $this->respondWithError('Ada '.count($users).' user dengan role ini', 401);
                }
                $role->delete();
                return $this->success('berhasil menghapus data', 200);
            } else {
                return $this->respondNotFound(null, 404);
            }
        } catch (\Throwable $e) {
            return $this->respondWithError('gagal menghapus data', 500, $e->getMessage());
        }
    }

    public function menus() {
        try {
            $response = Menu::with('permissions')->get();
            return $this->respond($response, 'berhasil menampilkan data!', 200);
        } catch (\Throwable $e) {
            return $this->respondWithError($e->getMessage(),500,$e->getMessage());
        }
    }

}
