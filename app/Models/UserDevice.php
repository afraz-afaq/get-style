<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public static function saveToken($userId, $token, $device = null)
    {
        $userDevice = self::query()->where('user_id', $userId)->first();
        if (!$userDevice)
        {
            $userDevice = new UserDevice();
        }

        $userDevice->user_id = $userId;
        $userDevice->device_token = $token;
        if($device)
            $userDevice->device_meta = $device;
        return $userDevice->save();

    }

    public static function getTokens(){
        return self::all()->pluck('device_token','user_id')->toArray();
    }
}
