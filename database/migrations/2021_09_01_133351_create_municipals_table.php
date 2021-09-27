<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('bigyapan_data_db')->create('municipals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('district_id');
            $table->string('title',255);
            $table->string('title_en',255);
            $table->string('title_ne',255);
            $table->string('type',255);
            $table->string('code',255);
            $table->text('bbox');
            $table->text('centroid');
            $table->integer('order');
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipals');
    }
}
