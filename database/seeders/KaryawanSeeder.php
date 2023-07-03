<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Service\ServiceKaryawan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MKaryawan;
use App\Models\MBagian;
use App\Models\MDepartemen;
use App\Models\MPenempatan;
use App\Models\MJabatan;
use Spatie\Permission\Models\Role;
use DB;
use Carbon;

class KaryawanSeeder extends Seeder {

    public function run() {

        DB::table('m_karyawan')->delete();
        DB::table('users')->where('email_verified_at', null)->delete();

        $time = Carbon\Carbon::now();
        $now = $time->toDateTimeString();
        $role = Role::where('name', 'Karyawan')->first();
        $this->karyawan = new ServiceKaryawan;
        $karyawans = $this->karyawan->dataKaryawan();
        $jabatans = MJabatan::get();
        foreach ($karyawans as $karyawan) {
            $user = User::create([
                'name' => $karyawan['name'],
                'email' => $karyawan['email'],
                'password' => Hash::make('asdw1234')
            ]);
            $user->assignRole($role);
            $birth = explode(",", $karyawan['ttl']);
            $date = explode("-", $birth[1]);

            foreach ($jabatans as $jabatan) {

                if ($jabatan->name == $karyawan['m_jabatan_id']) {
                    MKaryawan::create([
                        'name' => $karyawan['name'],
                        'm_departemen_id' => $karyawan['m_departemen_id'],
                        'm_bagian_id' => $karyawan['m_bagian_id'],
                        'm_jabatan_id' => $jabatan->id,
                        'm_penempatan_id' => $karyawan['m_penempatan_id'],
                        'user_id' => $user->id,
                        'no_bpjs_kesehatan' => $karyawan['no_bpjs_kesehatan'],
                        'no_bpjs_ketenagakerjaan' => $karyawan['no_bpjs_ketenagakerjaan'],
                        'no_ktp' => $karyawan['no_ktp'],
                        'no_karyawan' => $karyawan['no_karyawan'],
                        'no_kk' => $karyawan['no_kk'],
                        'no_rekening' => $karyawan['no_rekening'],
                        'bank_name' => $karyawan['bank_name'],
                        'gender' => $karyawan['gender'],
                        'phone_number' => $karyawan['phone'],
                        'email' => $karyawan['email'],
                        'religion' => 'Islam',
                        'emergency_phone_number' => '',
                        'education' => $karyawan['education'],
                        'address' => $karyawan['address'],
                        'place_birth' => $birth[0],
                        'date_birth' => str_replace(" ", "", $date[2]).'-'.str_replace(" ", "", $date[1]).'-'.str_replace(" ", "", $date[0]),
                        'description' => $karyawan['description'],
                        'created_by' => 0
                    ]);
                }
            }

        }
    }
}
