<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteCascadeItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('bigyapan_data_db')->table('item_images', function ($table) {
            $table->dropForeign('item_images_item_id_foreign')->references('id')->on('items');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('bigyapan_data_db')->table('item_images', function ($table) {
            $table->dropForeign('item_images_item_id_foreign')->references('id')->on('items');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade');
        });
    }
}
