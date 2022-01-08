<?php

namespace App\Models;

use App\Helpers\Constant;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [''];

    protected $appends = ['schedule_slots_booked','serviceCharges'];

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
        $shops = ShopProfile::query()->select(["area", "shop_id"])->with('shop:id,full_name')->get();
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

    public static function createOrUpdateRecord($data)
    {
        return self::updateOrCreate($data);
    }

    public static function getValidationRules($type, $params = [])
    {
        $rules = [
            'getShopProfile' => [
                'stylist_id' => 'required|numeric',
            ],
            'getAllShops'    => [
            ],
        ];

        return $rules[$type];
    }


    public static function getShops($offset, $limit, $filters)
    {
        $lat = isset($filters["lat"]) ? $filters["lat"] : null;
        $lng = isset($filters["lng"]) ? $filters["lng"] : null;

        return self::query()
            ->with('shop:id,full_name,profile_image,email,phone')
            ->with('shopServices:service_id', 'shopServices.service:id,name')
            ->orderBy('avg_rating', 'desc')
            ->when(Helper::keyValueExists($filters, 'city'), fn($query) => $query->where('city', 'like', '%' . $filters['city'] . '%'))
            ->when(Helper::keyValueExists($filters, 'radius'), fn($query) => $query->whereRaw("( FLOOR(6371 * ACOS( COS( RADIANS( $lat ) ) * COS( RADIANS( shop_profiles.lat ) ) * COS( RADIANS( shop_profiles.lng ) - RADIANS( $lng ) ) + SIN( RADIANS( $lat ) ) * SIN( RADIANS( shop_profiles.lat ) ) )) ) <= " . $filters['radius']))
            ->when(Helper::keyValueExists($filters, 'area'), fn($query) => $query->where('area', 'like', '%' . $filters['area'] . '%'))
            ->when(Helper::keyValueExists($filters, 'shop_id'), fn($query) => $query->where('shop_id', '=', $filters['shop_id']))
            ->when(Helper::keyValueExists($filters, 'services'), fn($query) => $query->whereHas('shopServices', fn($query) => $query->whereIn('service_id', $filters['services'])))
            ->where('is_available', '=', Constant::TRUE);
    }

    public static function getTopRatedShops()
    {
        return self::query()
            ->with('shop:id,full_name,profile_image,email,phone')
            ->with('shopServices:service_id', 'shopServices.service:id,name')
            ->orderBy('avg_rating', 'desc')
            ->where('is_available', '=', Constant::TRUE)
            ->limit(10)
            ->get();
    }

    public function shopServices()
    {
        return $this->hasManyThrough(ShopService::class, ShopStylist::class, 'shop_id', 'shop_stylist_id', 'shop_id', 'id');
    }

    public static function getShop($shopId)
    {
        return self::query()
            ->with('shop:id,full_name,profile_image,email,phone')
            ->with('shopServices:service_id', 'shopServices.service:id,name')
            ->with('stylists.user')
            ->where('shop_id', '=', $shopId)
            ->get()
            ->first();
    }

    public function scheduleSlots()
    {
        return $this->hasManyThrough(ScheduleSlot::class, Schedule::class, 'shop_id', 'schedule_id', 'shop_id', 'id')->select('schedule_slots.id', 'schedule_id', 'start_time', 'end_time');
    }

    public function getScheduleSlotsBookedAttribute()
    {
        $slots = $this->scheduleSlots;
        $bookedSlots = ShopOrder::getNotCompletedOrderSlotsIdsForShop($this->shop_id);
        foreach ($slots as $slot)
        {
            if (array_search($slot->id, $bookedSlots) !== false)
            {
                $slot['is_available'] = Constant::FALSE;
            }
            else
            {
                $slot['is_available'] = Constant::TRUE;
            }
        }

        return $slots;
    }

    public function stylists()
    {
        return $this->hasMany(ShopStylist::class, 'shop_id', 'shop_id');
    }

    public function getServiceChargesAttribute()
    {
        $shopServices = $this->shopServices;

        foreach ($shopServices as $ShopService)
        {
            $shopServiceCharge = ShopServicesCharge::where('shop_id', '=', $this->shop->id)
                ->where('service_id', '=', $ShopService->service->id)->first();

            $ShopService->service['charges'] = $shopServiceCharge;
        }

        return $shopServices;
    }
}
