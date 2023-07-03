<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MShift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shifts = [
            'Shift Pagi',
            'Shift Lembur Pagi',
            'Shift Lembur Sore',
            'Shift Pagi B',
            'Shift Malam',
            'SECURITY MALAM',
        ];

        $i = 0;
        do {
            MShift::create([
                'name' => $shifts[$i]
            ]);
            $i++;
        } while ($i < sizeof($shifts));
    }
}
