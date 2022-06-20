<?php

namespace App\Models;

use App\Helpers\Constant;
use App\Traits\NotificationTrait;
use App\Traits\ResponseHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Messaging\MulticastSendReport;

class Notification extends Model
{
    use HasFactory, NotificationTrait;

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

    public static function sendFcmNotification($userId, $typeId)
    {
        try
        {
            $userDevice = UserDevice::where('user_id', $userId)->first();
            $isSilent = false;

            if (!empty($userDevice))
            {
                $params = [
                    'tokens' => [$userDevice->device_token],
                    'data'   => [
                        'type_id' => "$typeId",
                        'user_id' => "$userId",
                    ]
                ];
                $response = self::sendNotification($params, $isSilent);
                if ($response instanceof MulticastSendReport)
                {
                    return $response ? $response->getItems()[0]->result()['name'] : $response->getItems()[0]->error()->getMessage();
                }
                else
                {
                    return $response;
                }
            }
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
