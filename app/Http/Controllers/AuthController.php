<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Models\User;
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
     *     summary="Register Customer|Shop|Freelancer",
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
        $requestData = $request->all();

        $validator = Validator::make($requestData, User::getValidationRules('register'));

        if ($validator->fails())
        {
            return $this->responseErrorValidation($validator->errors());
        }

        unset($requestData['password_confirmation']);
        $requestData['first_time_login'] = Constant::TRUE;
        $requestData['password'] = bcrypt($requestData['password']);
        $user = User::createOrUpdateRecord($requestData);
        $response = [
            'user'         => $user,
            'access_token' => $user->createToken(Constant::APP_TOKEN_NAME)->plainTextToken,
        ];
        return $this->responseSuccess($response);
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
     *                     example="asad@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="user secure password.",
     *                     type="string",
     *                     example="IamAsecretHero"
     *                 )
     *              )
     *         )
     *     )
     * )
     */

    public function login(Request $request)
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
            $response = [
                'user'         => $user,
                'access_token' => $user->createToken(Constant::APP_TOKEN_NAME)->plainTextToken,
            ];
            return $this->responseSuccess($response);
        }
        else
        {
            return $this->responseErrorValidation([__('Invalid email or password.')]);
        }

    }
}
