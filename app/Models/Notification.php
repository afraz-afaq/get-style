<?php

namespace App\Models;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public static function getNotification($typeId)
    {
        return self::where('status', Constant::TRUE)
            ->whereId($typeId)->first();
    }


    public static function isNotificationActive($notificationEventId)
    {
        $record = self::where('notification_event_id', '=', $notificationEventId)->first();
        if ($record)
        {
            return $record->status;
        }
        return false;

    }
}
