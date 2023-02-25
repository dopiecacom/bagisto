<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmsitAllegroApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('emsit_allegro_api_settings')) {
            Schema::create('emsit_allegro_api_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->text('client_id')->unique();
                $table->text('client_secret');
                $table->text('code_verifier');
                $table->boolean('sandbox_mode');
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
        Schema::dropIfExists('emsit_allegro_api_settings');
    }
}
