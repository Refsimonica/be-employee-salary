<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use DB;

class SettingSeeder extends Seeder {

    public function run() {
        DB::table('settings')->delete();

        $settings = [
            'salary',
            'email',
            'phone',
            'address'
        ];

        $sallary = (object) [
            'salary' => 3241929,
            'l1' => 28109,
            'l2' => 37479,
            'l3' => 56218,
            'hourly' => 19297,
            'daily' => 154378,
        ];

        $sallary_encode = json_encode($sallary);

        for ($i=0; $i < count($settings); $i++) {
            if ($settings[$i] == 'salary') {
                Setting::create([
                    'name' => $settings[$i],
                    'value' => $sallary_encode,
                    'updated_by' => 0
                ]);
            } else {
                Setting::create([
                    'name' => $settings[$i],
                    'updated_by' => 0
                ]);
            }
        }
    }
}
