<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTAbsensiId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_absensi_karyawan', function (Blueprint $table) {
            $table->integer('t_absensi_id')->nullable()->after('id');
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
            $table->dropColumn('t_absensi_id')->nullable();
        });
    }
}
