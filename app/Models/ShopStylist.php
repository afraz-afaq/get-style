<?php

namespace App\Models;

use App\Helpers\Constant;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopStylist extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class, 'stylist_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_id', 'id');
    }


    public function services()
    {
        return $this->belongsToMany(Service::class, 'shop_services', 'shop_stylist_id', 'service_id');
    }

    public function shopServices()
    {
        return $this->hasOne(ShopService::class, 'shop_stylist_id', 'id')->orderBy('created_at','desc');
    }

    public static function getTopRatedStylists()
    {
        return self::query()
            ->with('user:id,full_name,profile_image,email,phone')
            ->with('shop:id,full_name,profile_image,email,phone', 'shop.shopProfile')
            ->orderBy('avg_rating', 'desc')
            ->where('is_available', '=', Constant::TRUE)
            ->limit(10)
            ->get();
    }

    public static function getStylists($offset, $limit, $filters)
    {
        return self::query()
            ->with('user:id,full_name,profile_image,email,phone')
            ->with('shop:id,full_name,profile_image,email,phone', 'shop.shopProfile')
            ->orderBy('avg_rating', 'desc')
            ->when(Helper::keyValueExists($filters, 'city'), fn($query) => $query->whereHas('shop', fn($query) => $query->whereHas('shopProfile', fn($query) => $query->where('city', 'like', '%' . $filters['city'] . '%'))))
            ->when(Helper::keyValueExists($filters, 'area'), fn($query) => $query->whereHas('shop', fn($query) => $query->whereHas('shopProfile', fn($query) => $query->where('area', 'like', '%' . $filters['area'] . '%'))))
            ->when(Helper::keyValueExists($filters, 'shop_id'), fn($query) => $query->where('shop_id', '=', $filters['shop_id']))
            ->when(Helper::keyValueExists($filters, 'services'), fn($query)  => $query->whereHas('shopServices', fn($query) => $query->whereIn('service_id', $filters['services'])))
            ->where('is_available', '=', Constant::TRUE)
            ->limit(10);
    }

    public static function getValidationRules($type, $params = [])
    {
        $rules = [
            'getStylistProfile' => [
                'stylist_id' => 'required|numeric',
            ],
            'getAllStylist'     => [

            ],
        ];

        return $rules[$type];
    }
}
