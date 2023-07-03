<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalaryAndTimeColumnToTAbsensiKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_absensi_karyawan', function (Blueprint $table) {
            $table->double('total_salary', 9, 2)->after('absent_name');
            $table->double('total_overtime_salary', 9, 2)->after('total_salary');
            $table->double('total_regular_salary', 9, 2)->after('total_overtime_salary');
            $table->double('bpjs_kesehatan_cut', 9, 2)->nullable()->after('total_regular_salary');
            $table->double('bpjs_ketenagakerjaan_cut', 9, 2)->nullable()->after('bpjs_kesehatan_cut');
            $table->double('absent_cut', 9, 2)->nullable()->after('bpjs_ketenagakerjaan_cut');
            $table->integer('overtime')->after('absent_cut');
            $table->integer('absent')->after('overtime');
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
            $table->dropColumn('total_salary');
            $table->dropColumn('total_overtime_salary');
            $table->dropColumn('total_regular_salary');
            $table->dropColumn('bpjs_kesehatan_cut');
            $table->dropColumn('bpjs_ketenagakerjaan_cut');
            $table->dropColumn('absent_cut');
            $table->dropColumn('overtime');
            $table->dropColumn('absent');
        });
    }
}
