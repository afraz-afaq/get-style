<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Models\ShopStylist;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopStylistController extends Controller
{
    use ResponseHandler;

    /**
     * @OA\Get(
     *
     *     path="/stylist/top-rated",
     *     tags={"Stylist"},
     *     summary="Top Rated Stylists",
     *     operationId="getTopRatedStylists",
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

    public function getTopRatedStylists()
    {
        try
        {
            $response = ShopStylist::getTopRatedStylists();
            return $this->responseSuccess($response);
        }
        catch (\Exception $e)
        {
            return $this->serverError($e);
        }

    }


    /**
     * @OA\Get(
     *
     *     path="/stylist/{offset}",
     *     tags={"Stylist"},
     *     summary="Get all stylists",
     *     operationId="getAllStylists",
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
    public function getAllStylists(Request $request, $offset)
    {
        try
        {
            $requestData = $request->all();

            $validator = Validator::make($requestData, ShopStylist::getValidationRules('getAllStylist'));

            if ($validator->fails())
            {
                return $this->responseErrorValidation($validator->errors());
            }

            $limit = Constant::DEFAULT_PAGINATION_NUMBER;
            $offset = ($offset - 1) * $limit;

            $stylists = ShopStylist::getStylists($offset, $limit, $requestData);

            $count = $stylists->count();

            $stylists = $stylists->offset($offset)
                ->limit($limit)
                ->latest()
                ->get()
                ->toArray();

            $data = [
                'total_count'      => $count,
                'records_per_page' => $limit,
                'stylists'         => $stylists,
            ];
            return $this->responseSuccess($data);
        }
        catch (\Exception $exception)
        {
            return $this->serverError($exception);
        }
    }
}
