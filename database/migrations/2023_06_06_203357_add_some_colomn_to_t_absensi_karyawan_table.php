<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColomnToTAbsensiKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_absensi_karyawan', function (Blueprint $table) {
            //
            $table->string('bank_name')->nullable(true);
            $table->string('no_rekening')->nullable(true);
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
            $table->dropColumn('bank_name');
            $table->dropColumn('no_rekening');
        });
    }
}
