<?php

namespace Emsit\BagistoInPostShipping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Emsit\BagistoInPostShipping\Contracts\PaczkomatyLocation as PaczkomatyLocationContract;

class PaczkomatyLocation extends Model implements PaczkomatyLocationContract
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'emsit_paczkomaty_locations';

    protected $fillable = [
        'name',
        'address',
        'city',
        'post_code',
        'province',
        'location_description'
    ];
}