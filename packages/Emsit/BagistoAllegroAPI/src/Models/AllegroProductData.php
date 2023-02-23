<?php

namespace Emsit\BagistoAllegroAPI\Models;

use Emsit\BagistoAllegroAPI\Contracts\AllegroProductData as AllegroProductDataContract;
use Illuminate\Database\Eloquent\Model;

class AllegroProductData extends Model implements AllegroProductDataContract
{
	protected $table = 'emsit_allegro_products_data';
    protected $fillable = ['allegro_product_id', 'shop_product_id'];
}