<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('bigyapan_data_db')->create('items', function (Blueprint $table) {
            $db_main = DB::connection('mysql')->getDatabaseName();

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('item_category_id');
            $table->unsignedBigInteger('item_sub_category_id');
            $table->string('item_name', 255);
            $table->unsignedInteger('item_price');
            $table->boolean('item_price_negotiable')->nullable();
            $table->unsignedInteger('item_views')->nullable();
            $table->text('item_description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on(new Expression($db_main . '.users'))->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('item_category_id')->references('id')->on('item_categories')->onUpdate('cascade');
            $table->foreign('item_sub_category_id')->references('id')->on('item_sub_categories')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
