<?php

namespace App\Imports;

use App\Models\AbsensiKaryawan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AbsensiImport implements ToCollection
{

    // public function __construct($request) {
    //     $this->request = $request;
    // }

    public function collection(Collection $rows) {
        return $rows;
    }
}
