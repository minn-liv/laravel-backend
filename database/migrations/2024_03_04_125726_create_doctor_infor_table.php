<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorInforTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_infor', function (Blueprint $table) {
            $table->id();
            $table->integer('doctorId');
            $table->string('priceId');
            $table->string('provinceId');
            $table->string('paymentId');
            $table->string('addressClinic');
            $table->string('nameClinic');
            $table->string('note');
            $table->integer('count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_infor');
    }
}
