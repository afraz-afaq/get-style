<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Models\Notification;
use App\Models\ShopOrder;
use App\Traits\ResponseHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShopOrderController extends Controller
{
    use ResponseHandler;

    /**
     * @OA\Get(
     *
     *     path="/user/{userId}/order",
     *     tags={"Orders"},
     *     summary="Get user Order history",
     *     operationId="userOrderHistory",
     *
     *     @OA\Parameter(
     *     name="userId",
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

    public function userOrderHistory($userId)
    {
        return $this->responseSuccess(ShopOrder::getUserOrders($userId));
    }


    /**
     * @OA\POST(
     *
     *     path="/shop/order",
     *     tags={"Shop"},
     *     summary="Save shop order",
     *     operationId="saveOrder",
     *
     *
     *     @OA\RequestBody(
     *     description="Save a new shop order.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="Customer user id.",
     *                     type="number",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="shop_id",
     *                     description="Shop Id.",
     *                     type="number",
     *                     example="3"
     *                 ),
     *                 @OA\Property(
     *                     property="schedule_slot_id",
     *                     description="Selected slot.",
     *                     type="number",
     *                     example="1"
     *                 ),
     *                @OA\Property(
     *                     property="shop_stylist_id",
     *                     description="Stylist id if order type 2",
     *                     type="number",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="order_type",
     *                     description="1=shop | 2=stylist",
     *                     type="number",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="services",
     *                     description="date & time standard timestamp",
     *                     type="number",
     *                     example="3"
     *                 ),
     *              )
     *         )
     *     ),
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

    public function saveOrder(Request $request)
    {
        $data = $request->all();
        $services = $data['services'];
        unset($data['services']);
        $data['status'] = Constant::ORDER_PENDING;
        $data['order_date'] = Carbon::now();

        ShopOrder::saveOrder($data, $services);

        return $this->responseSuccess();
    }


    /**
     * @OA\POST(
     *
     *     path="/shop/order/status",
     *     tags={"Shop"},
     *     summary="Update order status",
     *     operationId="updateOrderStatus",
     *
     *
     *     @OA\RequestBody(
     *     description="Save a new shop order.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="order_id",
     *                     description="Order Id.",
     *                     type="number",
     *                     example="3"
     *                 ),
     *                @OA\Property(
     *                     property="status",
     *                     description="Status of the order.",
     *                     type="number",
     *                     example="1"
     *                 ),
     *              )
     *         )
     *     ),
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

    public function updateOrderStatus(Request $request)
    {
        $data = $request->all();
        $orderId = $data['order_id'];
        $attributes = ['status' => $data['status']];
        $order = ShopOrder::where('id', $data['order_id'])->first();
        ShopOrder::updateOrder($orderId, $attributes);
        Notification::sendFcmNotification($order->user_id, 1);
        return $this->responseSuccess();
    }

    /**
     * @OA\POST(
     *
     *     path="/shop/order/assignStylist",
     *     tags={"Shop"},
     *     summary="Assign a stylist to a order.",
     *     operationId="assignStylist",
     *
     *
     *     @OA\RequestBody(
     *     description="Assgin stylist to a order.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="order_id",
     *                     description="Order Id.",
     *                     type="number",
     *                     example="3"
     *                 ),
     *                @OA\Property(
     *                     property="shop_stylist_id",
     *                     description="Stylist id if order type 2",
     *                     type="number",
     *                     example="1"
     *                 ),
     *              )
     *         )
     *     ),
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

    public function assignStylist(Request $request)
    {
        $data = $request->all();
        $orderId = $data['order_id'];
        $attributes = ['shop_stylist_id' => $data['shop_stylist_id']];
        ShopOrder::updateOrder($orderId, $attributes);
        return $this->responseSuccess();
    }


    /**
     * @OA\Get(
     *
     *     path="/shop/{shopId}/order",
     *     tags={"Orders"},
     *     summary="Get shop Order history",
     *     operationId="shopOrderHistory",
     *
     *     @OA\Parameter(
     *     name="shopId",
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

    public function shopOrderHistory($shopId)
    {
        return $this->responseSuccess(ShopOrder::getUserOrders($shopId, 2));
    }
}

