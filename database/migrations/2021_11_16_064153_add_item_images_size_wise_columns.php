<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddItemImagesSizeWiseColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('bigyapan_data_db')->table('item_images', function ($table) {
            $table->renameColumn('item_image', 'item_image_large')->nullable();
            $table->text('item_image_medium')->nullable();
            $table->text('item_image_small')->nullable();
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
            $table->renameColumn('item_image_large', 'item_image');
            $table->dropColumn('item_image_medium');
            $table->dropColumn('item_image_small');
        });

    }
}
