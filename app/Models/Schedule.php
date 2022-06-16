<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public static function getShopSchedules($shopId)
    {
        return self::query()->where('shop_id', $shopId)->with('slots')->get()->toArray();
    }

    public function slots()
    {
        return $this->hasMany(ScheduleSlot::class, 'schedule_id', 'id');
    }
}
