<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markdowns', function (Blueprint $table) {
            $table->id();
            $table->longText('contentHTML');
            $table->longText('contentMarkdown');
            $table->longText('description');
            $table->integer('doctorId');
            $table->integer('specialtyId');
            $table->integer('clinicId');
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
        Schema::dropIfExists('markdowns');
    }
}
