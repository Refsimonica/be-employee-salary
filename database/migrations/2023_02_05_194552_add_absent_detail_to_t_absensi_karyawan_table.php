<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAbsentDetailToTAbsensiKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_absensi_karyawan', function (Blueprint $table) {
            $table->longtext('absent_detail')->nullable()->after('absent_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_absensi_karyawan', function (Blueprint $table) {
            $table->dropColumn('absent_detail')->nullable();
        });
    }
}
