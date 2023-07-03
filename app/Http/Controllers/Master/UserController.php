<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use DB;

class UserController extends Controller {

    public function index(Request $request) {

        $this->validate($request, [
            'search'          => 'nullable|string',
            'sort_by'         => 'nullable|in:name,id',
            'sort_order'      => 'nullable|in:asc,desc',
            'page'            => 'nullable|numeric',
            'page_size'       => 'nullable|numeric',
        ]);

        try{
            $response = User::with('karyawan', 'roles.permissions')
                ->when($request->search, function($q) use($request){
                    $q->where('name', 'ilike', '%'.$request->search.'%')
                        ->orWhere('email', 'ilike', '%'.$request->search.'%');
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
            'name'          => 'required',
            'email'         => 'required|email',
            'password'      => 'required',
            'role_id'       => 'required|numeric|exists:Spatie\Permission\Models\Role,id'
        ]);

        DB::beginTransaction();
        try {
            $response = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);
            $response->syncRoles($request->role_id);
            DB::commit();
            return $this->respond($response, 'berhasil menyimpan data', 200);
        } catch(\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal menyimpan data', 500, $e->getMessage());
        }
    }

    public function show($id) {
        try{
            $response = User::with('karyawan', 'roles.permissions')->find($id);
            return $this->respond($response, 'berhasil menampilkan data', 200);
        }catch(\Exception $e){
            return $this->respondWithError('gagal menampilkan data',500, $e->getMessage());
        }
    }

    public function update(Request $request, $id) {

        $this->validate($request, [
            'name'          => 'required',
            'email'         => 'required|email',
            'password'      => 'required',
            'role_id'       => 'required|numeric|exists:Spatie\Permission\Models\Role,id'
        ]);

        $data = User::find($id);
        if (!$data)
            return $this->respondNotFound('User tidak ditemukan', 404);

        DB::beginTransaction();
        try {
            $data->update([
                'name'          => isset($request->name) ? $request->name : $data->name,
                'email'         => isset($request->email) ? $request->email : $data->email,
                'password'      => isset($request->password) ? Hash::make($request->password) : $data->password
            ]);
            DB::commit();
            return $this->respond($data, 'berhasil mengubah data', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError('gagal mengubah data', 500, $e->getMessage());
        }
    }

    public function destroy($id) {
        $data = User::find($id);
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
