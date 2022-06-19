<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ShopOrder;
use App\Models\ShopProfile;
use App\Traits\ResponseHandler;

class ShopServiceController extends Controller
{

    use ResponseHandler;

    /**
     * @OA\Get(
     *
     *     path="/shop/{shopId}/slot/{slotId}/services",
     *     tags={"Services"},
     *     summary="Get services against slot for shop.",
     *     operationId="timeSlotServices",
     *
     *     @OA\Parameter(
     *     name="shopId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="slotId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
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

    public function timeSlotServices($shopId, $slotId)
    {
        $notAvailableStylist = ShopOrder::getServicesAgainstTimeSlot($slotId, $shopId);
        $shop = ShopProfile::where('shop_id', '=', $shopId)->first();
        $availableServices = $shop->shopServices()->whereNotIn('shop_stylist_id', $notAvailableStylist)
            ->with('service')
            ->get()
            ->pluck('service');

        $response = [];
        foreach ($availableServices as $item)
        {
            $response['services'][] = ['id' => $item['id'], 'name' => $item['name']];
        }


        return $this->responseSuccess($response);
    }


    /**
     * @OA\Get(
     *
     *     path="/shop/services/{shop_id}",
     *     tags={"Services"},
     *     summary="Get shop services.",
     *     operationId="getAllServices",
     *
     *     @OA\Parameter(
     *     name="shop_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
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

    public function getShopServices($shop_id)
    {
        return $this->responseSuccess(Service::getServices($shop_id));
    }
}
