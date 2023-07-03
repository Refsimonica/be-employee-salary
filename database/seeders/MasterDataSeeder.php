<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\MDepartemen;
use App\Models\MPenempatan;
use App\Models\MBagian;
use App\Models\MJabatan;
use DB;

class MasterDataSeeder extends Seeder
{

    public function run() {

        // DELETE MASTER DATA
        DB::table('m_bagian')->delete();
        DB::table('m_departemen')->delete();
        DB::table('m_jabatan')->delete();
        DB::table('m_penempatan')->delete();

        $departements = [
            'ENGINEERING',
            'PPIC ANALIS',
            'PROCUREMENT',
            'FINANCE',
            'GA',
            'PERSONALIA',
            'SYSTEM',
            'MARKETING',
            'MARKETING - DELTA',
            'QUALITY',
            'PRODUKSI',
            'PRODUKSI - MAINTENANCE',
            'PPIC',
            'PRODUKSI - QUALITY',
            'R & D',
            'DIE SHOP',
            'FINANCE ACCOUNTING'
        ];

        $penempatans = [
            'STL 1',
            'STL 2',
            'STL 3',
            'DELTA',
        ];

        $bagians = [
            [ "m_departemen_id" => "11", "name" => "Stamping" ],
            [ "m_departemen_id" => "11", "name" => "Welding" ],
            [ "m_departemen_id" => "8", "name" => "Logistik" ],
            [ "m_departemen_id" => "8", "name" => "Paking" ],
            [ "m_departemen_id" => "11", "name" => "Spot" ],
            [ "m_departemen_id" => "14", "name" => "In Proses" ],
            [ "m_departemen_id" => "12", "name" => "Maintenance" ],
            [ "m_departemen_id" => "5", "name" => "GA" ],
            [ "m_departemen_id" => "13", "name" => "PPIC" ],
            [ "m_departemen_id" => "11", "name" => "Produksi" ],
            [ "m_departemen_id" => "11", "name" => "Center Tool" ],
            [ "m_departemen_id" => "5", "name" => "Polybox" ],
            [ "m_departemen_id" => "16", "name" => "Die Shop" ],
            [ "m_departemen_id" => "11", "name" => "Administrasi" ],
            [ "m_departemen_id" => "5", "name" => "Office Boy" ],
            [ "m_departemen_id" => "16", "name" => "Dies Shop" ],
            [ "m_departemen_id" => "1", "name" => "Engineering" ],
            [ "m_departemen_id" => "4", "name" => "Finance" ],
            [ "m_departemen_id" => "4", "name" => "Accounting" ],
            [ "m_departemen_id" => "5", "name" => "Staff GA" ],
            [ "m_departemen_id" => "8", "name" => "Marketing" ],
            [ "m_departemen_id" => "8", "name" => "Delta" ],
            [ "m_departemen_id" => "8", "name" => "Distribusi" ],
            [ "m_departemen_id" => "6", "name" => "Security" ],
            [ "m_departemen_id" => "6", "name" => "Administrasi" ],
            [ "m_departemen_id" => "6", "name" => "Personalia" ],
            [ "m_departemen_id" => "2", "name" => "PPIC ANALIS" ],
            [ "m_departemen_id" => "3", "name" => "Procurement RM" ],
            [ "m_departemen_id" => "3", "name" => "Procurement Non RM" ],
            [ "m_departemen_id" => "11", "name" => "Spot Welding" ],
            [ "m_departemen_id" => "11", "name" => "Robot Welding" ],
            [ "m_departemen_id" => "11", "name" => "Spot " ],
            [ "m_departemen_id" => "11", "name" => "Forklif" ],
            [ "m_departemen_id" => "11", "name" => "Dies & Jig Store" ],
            [ "m_departemen_id" => "11", "name" => "Raw Material" ],
            [ "m_departemen_id" => "10", "name" => "Out Going" ],
            [ "m_departemen_id" => "10", "name" => "Quality" ],
            [ "m_departemen_id" => "10", "name" => "In Coming" ],
            [ "m_departemen_id" => "10", "name" => "New Model" ],
            [ "m_departemen_id" => "15", "name" => "R & D" ],
            [ "m_departemen_id" => "7", "name" => "System" ]

        ];

        $jabatans = [
            [ "m_departemen_id" => "11", "name" => "Operator Produksi"],
            [ "m_departemen_id" => "8", "name" => "Operator Paking"],
            [ "m_departemen_id" => "14", "name" => "Operator Qc In Proses"],
            [ "m_departemen_id" => "12", "name" => "Operator Maintenance"],
            [ "m_departemen_id" => "14", "name" => "Operator Quality Control"],
            [ "m_departemen_id" => "5", "name" => "Ofice Boy"],
            [ "m_departemen_id" => "13", "name" => "Operator PPIC"],
            [ "m_departemen_id" => "11", "name" => "Pengemudi"],
            [ "m_departemen_id" => "8", "name" => "Operator Packing"],
            [ "m_departemen_id" => "5", "name" => "UMUM"],
            [ "m_departemen_id" => "5", "name" => "Operator Ga"],
            [ "m_departemen_id" => "16", "name" => "Operator Dieshop"],
            [ "m_departemen_id" => "11", "name" => "Staff Produksi"],
            [ "m_departemen_id" => "16", "name" => "Progamer"],
            [ "m_departemen_id" => "16", "name" => "Ka. Ru Dies shop MTN"],
            [ "m_departemen_id" => "16", "name" => "Ka. Ru Dies shop"],
            [ "m_departemen_id" => "16", "name" => "Operator Dies shop MTN"],
            [ "m_departemen_id" => "16", "name" => "Operator Dies shop"],
            [ "m_departemen_id" => "16", "name" => "Ka. Sie Dies shop"],
            [ "m_departemen_id" => "16", "name" => "Staft Dies shop"],
            [ "m_departemen_id" => "16", "name" => "Ka. Bag Dies shop"],
            [ "m_departemen_id" => "16", "name" => "Ka. Sie Dies shop MTN"],
            [ "m_departemen_id" => "16", "name" => "Ka. Bag Dies shop MTN"],
            [ "m_departemen_id" => "1", "name" => "Staff Engineering"],
            [ "m_departemen_id" => "1", "name" => "Ka. Dept Engineering"],
            [ "m_departemen_id" => "4", "name" => "Ka. Bag. Acounting"],
            [ "m_departemen_id" => "4", "name" => "Staff Finance"],
            [ "m_departemen_id" => "4", "name" => "Staff ADM Finance"],
            [ "m_departemen_id" => "4", "name" => "Ka. Ru Finance"],
            [ "m_departemen_id" => "4", "name" => "Ka. Bag Finance"],
            [ "m_departemen_id" => "5", "name" => "Staff HRD & GA"],
            [ "m_departemen_id" => "5", "name" => "Ka. Sie GA"],
            [ "m_departemen_id" => "5", "name" => "Office Boy"],
            [ "m_departemen_id" => "8", "name" => "Operator Gudang"],
            [ "m_departemen_id" => "8", "name" => "Ka. Bag Distribusi"],
            [ "m_departemen_id" => "8", "name" => "Kolektor"],
            [ "m_departemen_id" => "8", "name" => "Pengemudi"],
            [ "m_departemen_id" => "8", "name" => "Ka. Dept. Marketing"],
            [ "m_departemen_id" => "8", "name" => "Ka. Bag Logistik"],
            [ "m_departemen_id" => "8", "name" => "Ka. Sie. Delta"],
            [ "m_departemen_id" => "8", "name" => "Ka. Ru. Delta"],
            [ "m_departemen_id" => "8", "name" => "Ka. Ru Packing"],
            [ "m_departemen_id" => "8", "name" => "Ka. Sie Gudang"],
            [ "m_departemen_id" => "6", "name" => "Anggota Security"],
            [ "m_departemen_id" => "6", "name" => "Kordinator Security (Setara Dengan Ka. Sie)"],
            [ "m_departemen_id" => "6", "name" => "Staff Personalia"],
            [ "m_departemen_id" => "6", "name" => "Ka. Ru GA"],
            [ "m_departemen_id" => "13", "name" => "Ka. Ru PPC Serah Terima"],
            [ "m_departemen_id" => "13", "name" => "Operator PPC Serah Terima 1"],
            [ "m_departemen_id" => "13", "name" => "Ka. Sie Shearing Plat"],
            [ "m_departemen_id" => "13", "name" => "Operator PPC Makloon"],
            [ "m_departemen_id" => "13", "name" => "Pengemudi Forklip"],
            [ "m_departemen_id" => "13", "name" => "Pengemudi (PPIC)"],
            [ "m_departemen_id" => "13", "name" => "Ka. Ru PPC Makloon"],
            [ "m_departemen_id" => "13", "name" => "Operator Shearing Plat"],
            [ "m_departemen_id" => "13", "name" => "Ka. Dept PPIC"],
            [ "m_departemen_id" => "13", "name" => "Ka. Sie PPC"],
            [ "m_departemen_id" => "2", "name" => "Ka. Dept PPIC"],
            [ "m_departemen_id" => "3", "name" => "Ka. Bag Procurment"],
            [ "m_departemen_id" => "3", "name" => "Ka. Sie Procurment"],
            [ "m_departemen_id" => "3", "name" => "Staff Procurement"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Produksi"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie A Shif 2B"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Center Tool"],
            [ "m_departemen_id" => "11", "name" => "Ka. Shift Produksi"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Line C Shif 2B"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie Line B Shif 1A"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Line A Shift 1A"],
            [ "m_departemen_id" => "11", "name" => "Staff Produksi (Raw Material)"],
            [ "m_departemen_id" => "11", "name" => "Operator Forklift"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie Shif B"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie Produksi Line C"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Shif 2B"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie Produksi"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Line B Shift 1A"],
            [ "m_departemen_id" => "11", "name" => "Ka. Dept. MFG"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Shif 1A"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Line A Shif 2B"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Robot Welding"],
            [ "m_departemen_id" => "11", "name" => "Ka. Bag Produksi"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru "],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru A Shif 2B"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie SOP"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Line B Shift 2B"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie Line B Shif 2B"],
            [ "m_departemen_id" => "11", "name" => "Operator Raw Material"],
            [ "m_departemen_id" => "11", "name" => "W. Ka. Bag Produksi"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Shif A"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie Produksi Shif A"],
            [ "m_departemen_id" => "11", "name" => "Ka. Ru Line C Shif 1A"],
            [ "m_departemen_id" => "11", "name" => "Ka. Sie Line A Shif 1A"],
            [ "m_departemen_id" => "12", "name" => "Ka. Ru Maintenance"],
            [ "m_departemen_id" => "12", "name" => "Ka. Sie Maintenance"],
            [ "m_departemen_id" => "12", "name" => "Ka. Bag Maintenance"],
            [ "m_departemen_id" => "14", "name" => "Ka. Ru Quality Control"],
            [ "m_departemen_id" => "14", "name" => "Ka. Sie Quality In Proses"],
            [ "m_departemen_id" => "10", "name" => "Operator Quality Out Going 1"],
            [ "m_departemen_id" => "10", "name" => "Ka. Bag Quality"],
            [ "m_departemen_id" => "10", "name" => "Ka. Sie Quality"],
            [ "m_departemen_id" => "10", "name" => "Operator Quality In Coming"],
            [ "m_departemen_id" => "10", "name" => "Operator Quality Out Going 2"],
            [ "m_departemen_id" => "10", "name" => "Ka. Sie Quality "],
            [ "m_departemen_id" => "10", "name" => "Ka. Ru Quality Out Going 2"],
            [ "m_departemen_id" => "10", "name" => "Ka. Sie Quality In Coming"],
            [ "m_departemen_id" => "10", "name" => "Ka. Ru Quality Out Going 1"],
            [ "m_departemen_id" => "10", "name" => "Ka. Ru Quality New Model"],
            [ "m_departemen_id" => "15", "name" => "Operator R & D"],
            [ "m_departemen_id" => "15", "name" => "Staff R & D"],
            [ "m_departemen_id" => "15", "name" => "Ka. Sie R & D"],
            [ "m_departemen_id" => "7", "name" => "Ka. Bag. System"]
        ];

        for ($i=0; $i < count($departements); $i++) {
            MDepartemen::create([
                'name' => $departements[$i]
            ]);
        }

        for ($i=0; $i < count($penempatans); $i++) {
            MPenempatan::create([
                'id' => $i+1,
                'name' => $penempatans[$i]
            ]);
        }

        foreach ($bagians as $bagian) {
            MBagian::create([
                'name' => $bagian['name'],
                'm_departemen_id' => $bagian['m_departemen_id']
            ]);
        }

        foreach ($jabatans as $jabatan) {
            MJabatan::create([
                'name' => $jabatan['name'],
                'm_departemen_id' => $jabatan['m_departemen_id']
            ]);
        }

    }
}
