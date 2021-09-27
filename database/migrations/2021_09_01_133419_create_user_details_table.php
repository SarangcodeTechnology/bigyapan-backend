<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('bigyapan_data_db')->create('user_details', function (Blueprint $table) {
            $db_main = DB::connection('mysql')->getDatabaseName();
            $db_data = DB::connection('bigyapan_data_db')->getDatabaseName();

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('account_type_id');
            $table->string('address_street', 255)->nullable();
            $table->unsignedInteger('address_ward')->nullable();
            $table->unsignedBigInteger('address_municipality_id')->nullable();
            $table->unsignedBigInteger('address_district_id')->nullable();
            $table->unsignedBigInteger('address_province_id')->nullable();
            $table->unsignedBigInteger('address_country_id')->nullable();
            $table->text('user_image')->nullable();
            $table->text('phone_number');
            $table->string('user_description', 500)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on(new Expression($db_main . '.users'))->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')->onUpdate('cascade');
            $table->foreign('address_municipality_id')->references('id')->on('municipals')->onUpdate('cascade');
            $table->foreign('address_district_id')->references('id')->on('districts')->onUpdate('cascade');
            $table->foreign('address_province_id')->references('id')->on('provinces')->onUpdate('cascade');
            $table->foreign('address_country_id')->references('id')->on('countries')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
