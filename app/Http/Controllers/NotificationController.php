<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Models\UserDevice;
use App\Traits\NotificationTrait;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\MulticastSendReport;

class NotificationController extends Controller
{
    use NotificationTrait, ResponseHandler;


    /**
     * @OA\Get(
     *
     *     path="/notifyDummy/{typeId}/{fcm_token}",
     *     tags={"Notification"},
     *     summary="Send dummy notification",
     *     operationId="notifyDummy",
     *
     *     @OA\Parameter(
     *     name="typeId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *     @OA\Parameter(
     *     name="fcm_token",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *          ),
     *      ),
     *
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */

    public function notifyDummy($typeId, $fcmToken, Request $request)
    {
        try
        {
            $userDevice = UserDevice::where('device_token', $fcmToken)->first();
            $isSilent = false;

            if (!empty($userDevice))
            {
                $userId = $userDevice->user_id;
                $params = [
                    'tokens' => [$fcmToken],
                    'data'   => [
                        'type_id' => "$typeId",
                        'user_id' => "$userId",
                        "name"    => "Test PN",
                    ]
                ];
                $response = $this->sendNotification($params, $isSilent);
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

    public function notify(Request $request)
    {
        try
        {
            $authenticate = false;
            if ($request->headers->has('secret'))
            {
                $secret = $request->headers->get('secret');
                if ($secret == config('app.API_SECRET'))
                {
                    $authenticate = true;
                }
            }

            if (!$authenticate)
            {
                return $this->responseErrorAuth();
            }

            $requestData = $request->all();

            $tokens = UserDevice::whereIn('user_id', $requestData['user_ids'])->get()->pluck('device_token')->toArray();
            $params = [
                'tokens' => $tokens,
                'data'   => [
                    'type_id' => $requestData['type_id'] . "",
                ]
            ];

            $response = $this->sendNotification($params);
            if ($response instanceof MulticastSendReport)
            {
                return $response ? $this->responseSuccess($response->getItems()[0]->result()) : $this->responseErrorValidation([$response->getItems()[0]->error()->getMessage()]);
            }
            else
            {
                return $this->responseErrorNotFound($response);
            }
        }
        catch (\Exception $e)
        {
            return $this->responseErrorNotFound($e->getMessage());
        }
    }
}
