<?php

namespace Emsit\BagistoAllegroAPI\Models;

use Emsit\BagistoAllegroAPI\Contracts\AllegroApiToken as AllegroApiTokenContract;
use Illuminate\Database\Eloquent\Model;

class AllegroApiToken extends Model implements AllegroApiTokenContract
{
	protected $table = 'emsit_allegro_api_tokens';
    protected $fillable = ['token', 'refresh_token', 'token_expiration_date'];
}