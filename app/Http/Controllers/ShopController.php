<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Models\ShopProfile;
use App\Models\ShopService;
use App\Models\ShopServicesCharge;
use App\Models\ShopStylist;
use App\Models\ShopStylistSchedule;
use App\Models\User;
use App\Traits\ResponseHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth;

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


    /**
     * @OA\Get(
     *
     *     path="/shop/top-rated",
     *     tags={"Shop"},
     *     summary="Top Rated Shops",
     *     operationId="getTopRatedShops",
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
    public function getTopRatedShops()
    {
        try
        {
            $response = ShopProfile::getTopRatedShops();
            return $this->responseSuccess($response);
        }
        catch (\Exception $e)
        {
            return $this->serverError($e);
        }

    }


    /**
     * @OA\Post(
     *
     *     path="/shop/add/stylist",
     *     tags={"Shop"},
     *     summary="Add shop stylist",
     *     operationId="addStylist",
     *
     *     @OA\RequestBody(
     *         description="Create a new stylist for shop.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="full_name",
     *                     description="fullname of the user.",
     *                     type="string",
     *                     example="Asad Shareef"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="email of the user.",
     *                     type="string",
     *                     example="asad@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     description="Phone of the user.",
     *                     type="string",
     *                     example="923482269069"
     *                 ),
     *                 @OA\Property(
     *                     property="account_type",
     *                     description="Type of user.",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="experience",
     *                     description="Experience of stylist",
     *                     type="integer",
     *                     example="0"
     *                 ),
     *                 @OA\Property(
     *                     property="schedule_id",
     *                     description="Schedule of stylist",
     *                     type="number",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="services",
     *                     description="Services",
     *                     type="number",
     *                     example="[2,3,4]"
     *                 ),
     *              )
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *          ),
     *      ),
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */

    public function addStylist(Request $request)
    {
        try
        {
            $requestData = $request->all();

            $shop = \auth()->user();
            if ($shop->account_type != Constant::ROLES['SHOP'])
            {
                return $shop->account_type;
                return $this->responseErrorFailed();
            }
            $validator = Validator::make($requestData, User::getValidationRules('stylist'));


            if ($validator->fails())
            {
                return $this->responseErrorValidation($validator->errors());
            }

            $requestData['first_time_login'] = Carbon::now();
            $requestData['password'] = bcrypt("12345");
            $user = User::createOrUpdateRecord([
                'full_name'    => $requestData['full_name'], 'email' => $requestData['email'],
                'phone'        => $requestData['phone'], 'password' => $requestData['password'],
                'account_type' => Constant::ROLES['STYLIST'], 'first_time_login' => $requestData['first_time_login']
            ]);

            $shop = [
                'shop_id'      => $shop->id,
                'stylist_id'   => $user->id,
                'experience'   => $requestData['experience'],
                'is_available' => 1,
            ];

            $shopStylist = ShopStylist::query()->create($shop);

            foreach ($requestData['services'] as $service)
            {
                ShopService::create([
                    'shop_stylist_id' => $user->id,
                    'service_id'      => $service
                ]);
            }

            ShopStylistSchedule::create([
                'schedule_id'     => $requestData['schedule_id'],
                'shop_stylist_id' => $shopStylist->id
            ]);

            return $this->responseSuccess($user);
        }
        catch (\Exception $e)
        {
            return $this->serverError($e);
        }
    }


    /**
     * @OA\Get(
     *
     *     path="/shop/{shopId}/stylists",
     *     tags={"Shop"},
     *     summary="Get shop Stylists",
     *     operationId="getShopStylists",
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

    public function getShopStylists($shopId)
    {
        return $this->responseSuccess(ShopStylist::getShopStylists($shopId));
    }


    /**
     * @OA\Post(
     *
     *     path="/shop/schedule",
     *     tags={"Shop"},
     *     summary="Add shop schedule",
     *     operationId="createSchedule",
     *
     *     @OA\RequestBody(
     *         description="Create a new schedule for shop.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="name of the schedule.",
     *                     type="string",
     *                     example="Full Day"
     *                 ),
     *                  @OA\Property(
     *                     property="timings",
     *                     description="timings",
     *                     type="number",
     *                     example="[['9:00','10:00'],['13:00','14:00']]"
     *                 ),
     *              )
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *          ),
     *      ),
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */
    public function createSchedule(Request $request)
    {
        $schedule = Schedule::create(['shop_id' => \auth()->user()->id, 'name' => $request['name']]);
        foreach ($request['timings'] as $timing)
        {
            ScheduleSlot::create([
                'schedule_id' => $schedule->id,
                'start_time'  => $timing[0],
                'end_time'    => $timing[1],
            ]);
        }

        return $this->responseSuccess();
    }


    /**
     * @OA\Get(
     *
     *     path="/shop/schedules",
     *     tags={"Shop"},
     *     summary="Get Shop Schedules",
     *     operationId="getShopSchedules",
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
    public function getShopSchedules()
    {
        return $this->responseSuccess(Schedule::getShopSchedules(\auth()->user()->id));
    }


    /**
     * @OA\Post(
     *
     *     path="/shop/service",
     *     tags={"Shop"},
     *     summary="Add shop Services",
     *     operationId="addShopServices",
     *
     *     @OA\RequestBody(
     *         description="Add Shop services.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="shop_id",
     *                     description="Shop id",
     *                     type="integer",
     *                     example="3"
     *                 ),
     *                  @OA\Property(
     *                     property="services",
     *                     description="Services that shop will provide.",
     *                     type="number",
     *                     example="[[1,101],[2,900]]"
     *                 ),
     *              )
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *          ),
     *      ),
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */
    public function addShopServices(Request $request)
    {
        $requestData = $request->all();
        foreach ($requestData['services'] as $service)
        {
            ShopServicesCharge::query()->updateOrCreate(
                [
                    'shop_id'    => $requestData['shop_id'],
                    'service_id' => $service[0]
                ],
                [
                    'charges' => $service[1]
                ]);
        }
        return $this->responseSuccess();
    }
}
