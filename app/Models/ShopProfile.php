<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProfile extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public static function getShopAreas()
    {
        $areas = self::query()->select(["city", "area"])->get();
        return $areas->mapToGroups(function ($item, $key)
        {
            return [$item['city'] => $item['area']];
        });

    }

    public static function getAreaShops()
    {
        $shops =ShopProfile::query()->select(["area", "shop_id"])->with('shop:id,full_name')->get();
        return $shops->mapToGroups(function ($item, $key)
        {
            return [$item['area'] => ['shop_id' => $item['shop_id'], 'shop_name' => $item['shop']['full_name']]];
        });

    }

    public static function getShopCities()
    {
        return self::query()->selectRaw("DISTINCT(city)")->get();
    }

    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_id', 'id');
    }
}
