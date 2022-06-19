<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public static function getServices($shop = false)
    {
        if (!$shop)
        {
            return self::query()->select('id', 'name')->get();
        }
        else
        {
            $servicesId = ShopServicesCharge::query()
                ->where('shop_id', $shop)
                ->select('service_id')
                ->get()
                ->pluck('service_id')
                ->toArray();
            dd($servicesId);
            return self::query()->select('id', 'name')->whereIn('id', $servicesId)->get();
        }
    }

    public function shopStylists()
    {

        return $this->belongsToMany(ShopStylist::class, 'shop_services', 'service_id', 'id');

    }
}
