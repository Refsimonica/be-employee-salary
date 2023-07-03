<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Menu;
use DB;

class AuthDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // delete semua data user, role, permission, menu
        DB::table('users')->delete();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('role_has_permissions')->delete();
        DB::table('menus')->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $menus = [ 'karyawan', 'departement', 'bagian', 'jabatan', 'user', 'role', 'salary', 'absensi' ];
        $actions = ['view', 'add', 'edit', 'delete', 'export'];
        $index = 0;
        do {
            $menu = Menu::create([ 'id' => $index + 1, 'name' => $menus[$index] ]);
            $index2 = 0;

            do {
                if ($actions[$index2] == 'export') {
                    if ($menus[$index] != 'user' && $menus[$index] != 'role') {
                        Permission::create([
                            'name' => $actions[$index2].' '.$menus[$index],
                            'guard_name' => 'web',
                            'menu_id' => $menu->id
                        ]);
                    }

                } else {
                    Permission::create([
                        'name' => $actions[$index2].' '.$menus[$index],
                        'guard_name' => 'web',
                        'menu_id' => $menu->id
                    ]);
                }
                $index2++;
            } while ($index2 < sizeof($actions));

            $index++;
        } while ($index < sizeof($menus));

        $karyawan = Role::create(['name' => 'Karyawan', 'guard_name' => 'web']);
        $admin = Role::create(['name' => 'Administrator', 'guard_name' => 'web']);
        $direktur = Role::create(['name' => 'Direktur', 'guard_name' => 'web']);
        $keuangan = Role::create(['name' => 'Keuangan', 'guard_name' => 'web']);
        $dev = Role::create(['id' => 0, 'name' => 'Developer', 'guard_name' => 'web']);

        $dev->givePermissionTo(Permission::all());

        $user = User::factory()->create([
            'id' => 0,
            'name' => 'Developer',
            'email' => 'dev@dev.com',
            'password' => Hash::make('dev')
        ]);
        $user->assignRole($dev);

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin')
        ]);
        $user->assignRole($admin);

        $user = User::factory()->create([
            'name' => 'Direktur',
            'email' => 'direktur@direktur.com',
            'password' => Hash::make('direktur')
        ]);
        $user->assignRole($direktur);

        $user = User::factory()->create([
            'name' => 'Keuangan',
            'email' => 'keuangan@keuangan.com',
            'password' => Hash::make('keuangan')
        ]);
        $user->assignRole($keuangan);
    }
}
