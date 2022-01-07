<?php

namespace App\Models;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [''];

    public static function getNotCompletedOrderSlotsIdsForShop($shopId)
    {
        return self::query()->select('schedule_slot_id')
            ->where('shop_id', '=', $shopId)
            ->whereIn('status', [Constant::ORDER_PENDING, Constant::ORDER_PROCESS, Constant::ORDER_CONFIRMED])
            ->get()
            ->pluck('schedule_slot_id')
            ->toArray();
    }

    public static function getServicesAgainstTimeSlot($slot_id, $shop_id)
    {
        return self::query()->select('shop_stylist_id')
            ->where('schedule_slot_id', '=', $slot_id)
            ->where('shop_id', '=', $shop_id)
            ->whereIn('status', [Constant::ORDER_PENDING, Constant::ORDER_PROCESS, Constant::ORDER_CONFIRMED])
            ->get()
            ->pluck('shop_stylist_id')
            ->toArray();
    }

    public function shopOrderDetail()
    {
        return $this->hasOne(ShopOrderDetail::class, 'shop_order_id', 'id');
    }

    public function shopProfile()
    {
        return $this->belongsTo(ShopProfile::class, 'shop_id', 'shop_id');
    }

    public static function getUserOrders($userId)
    {
        return self::with('shopOrderDetail.service')->with('shopProfile.shop')->orderBy('shop_orders.id')->get()->toArray();
    }

    public static function saveOrder($requestedData, $services)
    {
        $order = self::create($requestedData);

        foreach ($services as $service)
        {
            $serviceRecord = ShopServicesCharge::where('service_id', '=', $service)
                ->where('shop_id', '=', $order->shop_id)
                ->get()
                ->first();

            ShopOrderDetail::create([
                'shop_order_id' => $order->id,
                'service_id'    => $service,
                'charges'       => $serviceRecord->charges
            ]);
        }
    }

    public static function updateOrder($orderId, $attributes)
    {
        return self::query()->where('id', '=', $orderId)
            ->update($attributes);

    }
}
