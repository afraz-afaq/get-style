<?php

namespace App\Models;

use App\Helpers\Constant;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopStylist extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [''];

    protected $appends = ['schedule_slots_booked', 'serviceCharges'];

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
        return $this->hasOne(ShopService::class, 'shop_stylist_id', 'id')->orderBy('created_at', 'desc');
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

    public static function getStylist($stylistId)
    {
        return self::query()
            ->with('user:id,full_name,profile_image,email,phone')
            ->with('shop:id,full_name,profile_image,email,phone', 'shop.shopProfile')
            ->with('shopServices.service:id,name')
            ->where('id', '=', $stylistId)
            ->limit(10)
            ->get();
    }

    public static function getStylists($offset, $limit, $filters)
    {
        $lat = isset($filters["lat"]) ? $filters["lat"] : null;
        $lng = isset($filters["lng"]) ? $filters["lng"] : null;

        return self::query()
            ->with('user:id,full_name,profile_image,email,phone')
            ->with('shop:id,full_name,profile_image,email,phone', 'shop.shopProfile')
            ->with('services:id,name')
            ->orderBy('avg_rating', 'desc')
            ->when(Helper::keyValueExists($filters, 'city'), fn($query) => $query->whereHas('shop', fn($query) => $query->whereHas('shopProfile', fn($query) => $query->where('city', 'like', '%' . $filters['city'] . '%'))))
            ->when(Helper::keyValueExists($filters, 'radius'), fn($query) => $query->whereHas('shop', fn($query) => $query->whereHas('shopProfile', fn($query) => $query->whereRaw("( FLOOR(6371 * ACOS( COS( RADIANS( $lat ) ) * COS( RADIANS( shop_profiles.lat ) ) * COS( RADIANS( shop_profiles.lng ) - RADIANS( $lng ) ) + SIN( RADIANS( $lat ) ) * SIN( RADIANS( shop_profiles.lat ) ) )) ) <= " . $filters['radius']))))
            ->when(Helper::keyValueExists($filters, 'area'), fn($query) => $query->whereHas('shop', fn($query) => $query->whereHas('shopProfile', fn($query) => $query->where('area', 'like', '%' . $filters['area'] . '%'))))
            ->when(Helper::keyValueExists($filters, 'shop_id'), fn($query) => $query->where('shop_id', '=', $filters['shop_id']))
            ->when(Helper::keyValueExists($filters, 'services'), fn($query) => $query->whereHas('shopServices', fn($query) => $query->whereIn('service_id', $filters['services'])))
            ->where('is_available', '=', Constant::TRUE);
    }

    public function scheduleSlots()
    {
        return $this->hasManyThrough(ScheduleSlot::class, Schedule::class, 'shop_id', 'schedule_id', 'shop_id', 'id')->select('schedule_slots.id', 'schedule_id', 'start_time', 'end_time');
    }

    public function getScheduleSlotsBookedAttribute()
    {

        $slots = $this->scheduleSlots;
        $bookedSlots = ShopOrder::query()->select('schedule_slot_id')
            ->where('shop_stylist_id', '=', $this->id)
            ->whereIn('status', [Constant::ORDER_PENDING, Constant::ORDER_PROCESS, Constant::ORDER_CONFIRMED])
            ->get()
            ->pluck('schedule_slot_id')
            ->toArray();

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

    public function getServiceChargesAttribute()
    {
        $shopServices = $this->services;

        foreach ($shopServices as $service)
        {
            $shopServiceCharge = ShopServicesCharge::where('shop_id', '=', $this->shop->id)
                ->where('service_id', '=', $service->id)->first();

            $service['charges'] = $shopServiceCharge->charges;
        }

        return $shopServices;
    }

}
