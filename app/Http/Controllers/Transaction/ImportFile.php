<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TODBC;
use DB;
use PDO;
class ImportFile extends Controller
{

    public function index() {
        try{
            // $data = DB::connection('odbc')
            // ->table('USERINFO')
            // ->select('*')
            // ->get();

            // return php_ini_loaded_file();
            $dbName = $_SERVER["DOCUMENT_ROOT"] . "/storage/files/att2000.mdb";
            // return $dbName;
            if (!file_exists($dbName)) {
                die("Could not find database file.");
            }
            // $db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName; Uid=; Pwd=;");
            $dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=$dbName;Uid=; Pwd=;");

        }catch(\Exception $e){
            return $this->respondWithError('gagal menampilkan data',500, $e->getMessage());
        }
    }

    // public function importMDB() {

    //     try{
    //          //load you file
    //         $file = public_path('storage/files/att2000.mdb');
    //         $parser = MDBParser::loadFile($file);

    //         // return $file;

    //         //see table names...
    //         $tables = $parser->tables();

    //         //parse data from one chosen table...
    //         $response = $parser->selectTable('SystemLog')->toArray();
    //         return $this->respond($response, 'berhasil menampilkan data', 200);
    //     }catch(\Exception $e){
    //         return $this->respondWithError('gagal menampilkan data',500, $e->getMessage());
    //     }
    // }
}
