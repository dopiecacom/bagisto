<?php

namespace Emsit\BagistoAllegroAPI\Models;

use Emsit\BagistoAllegroAPI\Contracts\AllegroApiSettings as AllegroApiSettingsContract;
use Illuminate\Database\Eloquent\Model;

class AllegroApiSettings extends Model implements AllegroApiSettingsContract
{
	protected $table = 'emsit_allegro_api_settings';
    protected $fillable = ['client_id', 'client_secret', 'code_verifier', 'sandbox_mode'];
}