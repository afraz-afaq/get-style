<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Models\ShopProfile;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ResponseHandler;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ResponseHandler;

    /**
     * @OA\Post(
     *
     *     path="/register",
     *     tags={"User"},
     *     summary="Register Customer|Shop|Stylist",
     *     operationId="register",
     *
     *     @OA\RequestBody(
     *         description="Register a new user.",
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
     *                     property="password",
     *                     description="Password of the user.",
     *                     type="string",
     *                     example="IamAsecretHero"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     description="Confirmed password of the user.",
     *                     type="string",
     *                     example="IamAsecretHero"
     *                 )
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
     *      )
     * )
     */

    public function register(Request $request)
    {
        try
        {
            $requestData = $request->all();

            if ($requestData['account_type'] == Constant::ROLES['CUSTOMER'])
            {
                $validator = Validator::make($requestData, User::getValidationRules('register'));
            }
            else
            {
                $validator = Validator::make($requestData, User::getValidationRules('registerShop'));
            }

            if ($validator->fails())
            {
                return $this->responseErrorValidation($validator->errors());
            }

            unset($requestData['password_confirmation']);
            $requestData['first_time_login'] = Carbon::now();
            $requestData['password'] = bcrypt($requestData['password']);
            $user = User::createOrUpdateRecord($requestData);
            if ($requestData['account_type'] == Constant::ROLES['SHOP'])
            {
                $shop = [
                    'shop_id'          => $user->id,
                    'complete_address' => $requestData['complete_address'],
                    'city'             => $requestData['city'],
                    'area'             => $requestData['area'],
                    'lat'              => $requestData['lat'],
                    'lng'              => $requestData['lng'],
                ];

                ShopProfile::createOrUpdateRecord($shop);
            }

            $response = [
                'user'         => $user,
                'access_token' => $user->createToken(Constant::APP_TOKEN_NAME)->plainTextToken,
            ];

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
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Login User",
     *     operationId="login",
     *
     *     @OA\Response(response=200,description="Success"),
     *
     *     @OA\RequestBody(
     *         description="Login User to the system.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="user login email address provided on signup to Nonsulin",
     *                     type="string",
     *                     example="asad@getstyle.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="user secure password.",
     *                     type="string",
     *                     example="123456"
     *                 ),
     *                 @OA\Property(
     *                     property="device_token",
     *                     description="FCM Token.",
     *                     type="string",
     *                     example="8dsfu98pdsabfd9f8b7s9f7adf7b8adb7f"
     *                 ),
     *                  @OA\Property(
     *                     property="device_meta",
     *                     description="Device information.",
     *                     type="string",
     *                     example="GetStyle - v1.2 DEVICE STK-L21 SDK VERSION 29 ANDROID 10!"
     *                 )
     *              )
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        try
        {
            $requestData = $request->all();

            $validator = Validator::make($requestData, User::getValidationRules('login'));

            if ($validator->fails())
            {
                return $this->responseErrorValidation($validator->errors());
            }

            $user = User::getUser(['email' => $requestData['email']]);
            if ($user && Hash::check($requestData['password'], $user->password))
            {
                if (!$user->first_time_login)
                {
                    $user->first_time_login = Carbon::now();
                    $user->save();
                }
                $response = [
                    'user'         => $user,
                    'access_token' => $user->createToken(Constant::APP_TOKEN_NAME)->plainTextToken,
                ];
                if (isset($requestData['device_token']) && isset($requestData['device_meta']))
                {
                    UserDevice::saveToken($user->id, $requestData['device_token'], $requestData['device_meta']);
                }
                return $this->responseSuccess($response);
            }
            else
            {
                return $this->responseErrorValidation([__('Invalid email or password.')]);
            }
        }
        catch (\Exception $e)
        {
            return $this->serverError($e);
        }

    }


    /**
     * @OA\Post(
     *
     *     path="/logout",
     *     tags={"Auth"},
     *     summary="Logout User",
     *     operationId="logout",
     *
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
    public function logout()
    {
        auth()->user()->tokens()->delete();
        UserDevice::where('user_id', auth()->user()->id)->delete();
        return $this->responseSuccess(['Logout successfully!']);
    }


    /**
     * @OA\Post(
     *
     *     path="/user/change-password",
     *     tags={"Auth"},
     *     summary="Change password",
     *     operationId="changePassword",
     *
     *     @OA\RequestBody(
     *         description="Change Password.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="old_password",
     *                     description="user old password.",
     *                     type="string",
     *                     example="Test123!"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="user new password.",
     *                     type="string",
     *                     example="Test123!"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     description="user new password confirmation.",
     *                     type="string",
     *                     example="Test123!"
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
     *
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */

    public function changePassword(Request $request)
    {
        try
        {
            $requestData = $request->all();

            $validator = Validator::make($requestData, User::getValidationRules('changePassword'));

            if ($validator->fails())
            {
                return $this->responseErrorValidation($validator->errors());
            }

            $user = auth()->user();

            $hashedPassword = $user->password ?? '';

            if (Hash::check($requestData['old_password'], $hashedPassword))
            {
                if (!Hash::check($requestData['password'], $hashedPassword))
                {
                    $user->password = bcrypt($requestData['password']);
                    User::where('id', $user->id)->update([
                        'password' => $user->password
                    ]);
                    return $this->responseSuccess([], __('messages.auth.password_reset'));
                }
                else
                {
                    return $this->responseErrorValidation([__('messages.auth.new_password_not_old')], 'Validation Error');
                }
            }
            else
            {
                return $this->responseErrorValidation([__('messages.auth.old_password_not_match')], 'Validation Error');
            }
        }
        catch (\Exception $e)
        {
            return $this->serverError($e);
        }
    }
}
