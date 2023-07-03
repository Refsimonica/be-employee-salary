<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTAbsensiKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_absensi_karyawan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('m_karyawan_id')->nullable(true);
            $table->string('no_acc');
            $table->string('no_absent');
            $table->string('no_karyawan');
            $table->string('absent_name');
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_absensi_karyawan');
    }
}
