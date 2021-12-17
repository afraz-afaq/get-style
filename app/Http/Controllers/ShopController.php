<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Models\ShopProfile;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    use ResponseHandler;

    /**
     * @OA\Get(
     *
     *     path="/shop/{offset}",
     *     tags={"Shop"},
     *     summary="Get all shops",
     *     operationId="getAllShops",
     *
     *     @OA\Parameter(
     *     name="offset",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *     @OA\Parameter(
     *     name="city",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="area",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="shop_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="radius",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="lat",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="float"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="lng",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="float"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="services[]",
     *     in="query",
     *     required=false,
     *       @OA\Schema(
     *              type="array",
     *               @OA\Items(type="integer")
     *          )
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

    public function getAllShops(Request $request, $offset)
    {
        try
        {
            $requestData = $request->all();
            $validator = Validator::make($requestData, ShopProfile::getValidationRules('getAllShops'));

            if ($validator->fails())
            {
                return $this->responseErrorValidation($validator->errors());
            }

            $limit = Constant::DEFAULT_PAGINATION_NUMBER;
            $offset = ($offset - 1) * $limit;

            $shops = ShopProfile::getShops($offset, $limit, $requestData);

            $count = $shops->count();

            $shops = $shops->offset($offset)
                ->limit($limit)
                ->latest()
                ->get()
                ->toArray();

            $data = [
                'total_count'      => $count,
                'records_per_page' => $limit,
                'shops'            => $shops,
            ];
            return $this->responseSuccess($data);
        }
        catch (\Exception $exception)
        {
            return $this->serverError($exception);
        }
    }


    /**
     * @OA\Get(
     *
     *     path="/shop/profile/{shopId}",
     *     tags={"Shop"},
     *     summary="Get shop profile",
     *     operationId="getShopProfile",
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
    public function getShopProfile($shopId)
    {
        $shop = ShopProfile::getShop($shopId);
        return $this->responseSuccess($shop);
    }

}
