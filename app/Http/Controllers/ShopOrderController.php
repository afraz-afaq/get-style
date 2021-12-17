<?php

namespace App\Http\Controllers;

use App\Models\ShopOrder;
use App\Traits\ResponseHandler;
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

}

