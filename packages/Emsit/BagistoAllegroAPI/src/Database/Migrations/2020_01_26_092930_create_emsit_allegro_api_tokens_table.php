<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmsitAllegroApiTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('emsit_allegro_api_tokens')) {
            Schema::create('emsit_allegro_api_tokens', function (Blueprint $table) {
                $table->increments('id');
                $table->text('token');
                $table->text('refresh_token');
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
        Schema::dropIfExists('emsit_allegro_api_tokens');
    }
}
