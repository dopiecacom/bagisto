<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmsitPaczkomatyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('emsit_paczkomaty_locations')) {
            Schema::create('emsit_paczkomaty_locations', function (Blueprint $table) {
                $table->increments('id');
                $table->text('name')->unique();
                $table->string('address');
                $table->string('city');
                $table->string('post_code');
                $table->string('province');
                $table->text('location_description');
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
        Schema::dropIfExists('emsit_paczkomaty_locations');
    }
}
