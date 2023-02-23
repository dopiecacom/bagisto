<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmsitAllegroProductsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('emsit_allegro_products_data')) {
            Schema::create('emsit_allegro_products_data', function (Blueprint $table) {
                $table->increments('id');
                $table->bigInteger('allegro_product_id');
                $table->bigInteger('shop_product_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emsit_allegro_products_data');
    }
}
