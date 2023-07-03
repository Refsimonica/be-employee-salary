<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_karyawan', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('m_departemen_id');
            $table->integer('m_bagian_id');
            $table->integer('m_jabatan_id');
            $table->integer('m_penempatan_id');
            $table->integer('user_id');

            $table->string('no_bpjs_kesehatan')->nullable(true);
            $table->string('no_bpjs_ketenagakerjaan')->nullable(true);
            $table->string('no_ktp');
            $table->string('no_karyawan')->nullable(true);
            $table->string('no_kk')->nullable(true);
            $table->string('no_rekening')->nullable(true);
            $table->string('bank_name')->nullable(true);
            $table->string('gender');
            $table->string('phone_number');
            $table->string('email');
            $table->string('religion');
            $table->string('emergency_phone_number')->nullable(true);
            $table->string('education')->nullable(true);
            $table->text('address')->nullable(true);
            $table->string('place_birth')->nullable(true);
            $table->date('date_birth')->nullable(true);
            $table->string('description')->nullable(true);

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
        Schema::dropIfExists('m_karyawan');
    }
}
