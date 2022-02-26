<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Helpers\Constant;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Models\NotificationLog;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\FirebaseException;

trait NotificationTrait
{
    public function sendNotification($params, $isSilent = false)
    {
        try
        {
            $notification = Notification::getNotification($params['data']['type_id']);
            if (empty($notification))
            {
                return false;
            }
            $params['data']['title'] = $notification->title;
            $params['data']['body'] = $notification->body;
            $deviceTokens = $params['tokens'];

            $apnPayload = [
                'alert'           => [
                    'title' => $params['data']['title'],
                    'body'  => $params['data']['body']
                ],
                'data'            => $params['data'],
                'sound'           => 'default',
                'mutable-content' => 1
            ];

            //For Silent Apple PN
//            $apnPayload = [
//                "content-available" => 1,
//                'data'              => $params['data'],
//            ];


            $message = CloudMessage::fromArray([
                'data'    => $params['data'],
                'android' => [
                    'priority' => 'high',
                ],
                'apns'    => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => $apnPayload,
                    ],
                ],
            ]);

            $response = Firebase::messaging()->sendMulticast($message, $deviceTokens);


            self::logResponse($response, $params);


            return $response;
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
        catch (FirebaseException $e)
        {
            return $e->getMessage();
        }

    }

    protected function logResponse($response, $params)
    {
        if ($response)
        {
            $data = [];
            $tokens = array_flip(UserDevice::getTokens());
            foreach ($response->getItems() as $report)
            {
                $data[] = [
                    'title'                 => $params['data']['title'],
                    'body'                  => $params['data']['body'],
                    'device_token'          => $report->target()->value(),
                    'error'                 => $report->error() ? $report->error()->getMessage() : null,
                    'notification_id' => $report->message()->jsonSerialize()['data']->jsonSerialize()['type_id'],
                    'user_id'               => isset($tokens[$report->target()->value()]) ? $tokens[$report->target()->value()] : 0,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ];
            }
            NotificationLog::insert($data);
        }
    }

}
