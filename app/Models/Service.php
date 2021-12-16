<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public static function getServices()
    {
        return self::query()->select('id', 'name')->get();
    }

    public function shopStylists()
    {

        return $this->belongsToMany(ShopStylist::class, 'shop_services', 'service_id', 'id');

    }
}
