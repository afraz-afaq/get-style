<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Models\Patient;
use App\Models\ShopProfile;
use App\Models\ShopStylist;
use App\Models\User;
use App\Traits\ResponseHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use ResponseHandler;

    /**
     * @OA\Post(
     *
     *     path="/user/profile-picture",
     *     tags={"User"},
     *     summary="Store profile pic for logged in User.",
     *     operationId="updateProfilePicture",
     *
     *     @OA\RequestBody(
     *         description="Store profile pic for logged in User.",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="profile_picture",
     *                     description="select profile picture for logged in User.",
     *                     type="array",
     *                  @OA\Items(type="string", format="binary")
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

    public function updateProfilePicture(Request $request)
    {
        try
        {
            $requestData = $request->all();
            $validator = Validator::make($requestData, ['profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

            if ($validator->fails())
            {
                return $this->responseErrorValidation($validator->errors());
            }

            $userId = auth()->user()->id;
            $file = $request->file('profile_picture');
            $fileName = "profile-" . $userId . "-" . $file->getClientOriginalName();
            Helper::uploadFile($file, $fileName);
            User::updateProfileImage($userId, $fileName);
            return $this->responseSuccess([asset("storage/profile-images/$fileName")]);
        }
        catch (Exception $e)
        {
            return $this->serverError($e);
        }
    }


    /**
     * @OA\Post(
     *
     *     path="/profile/update",
     *     tags={"User"},
     *     summary="Edit patient profile data",
     *     operationId="editProfile",
     *
     *     @OA\RequestBody(
     *         description="Edit patient profile data.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="full_name",
     *                     description="name",
     *                     type="string",
     *                     example="Full name"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="user email",
     *                     type="string",
     *                     example="abc@gg.com"
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

    public function editProfile(Request $request)
    {
        try
        {
            $user = auth()->user();
            switch ($user->account_type)
            {
                case Constant::ROLES['CUSTOMER']:
                    $requestData = $request->all();

                    $validator = Validator::make($requestData, [
                        'full_name' => 'required|string',
                        'email'     => 'required|string|unique:users,email,' . $user->id . ',id,deleted_at,NULL',
                    ]);

                    if ($validator->fails())
                    {
                        return $this->responseErrorValidation($validator->errors());
                    }

                    User::updateUser($requestData, ['id' => $user->id]);
                    break;
            }
            return $this->responseSuccess();
        }
        catch (\Exception $e)
        {
            return $this->serverError($e);
        }
    }


    /**
     * @OA\POST(
     *
     *     path="/user/availability/update",
     *     tags={"User"},
     *     summary="Update users availability.",
     *     operationId="updateAvailability",
     *
     *
     *     @OA\RequestBody(
     *     description="Update user availability status..",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="is_available",
     *                     description="Availability Status.",
     *                     type="number",
     *                     example="1 or 0"
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

    public function updateAvailability(Request $request)
    {
        $data = $request->all();

        if (auth()->user()->account_type == Constant::ROLES['SHOP'])
        {
            $user = ShopProfile::where(['shop_id' => auth()->user()->id])->first();
        }
        else
        {
            $user = ShopStylist::where(['stylist_id' => auth()->user()->id])->first();
        }

        $user->is_available = $data['is_available'];
        $user->save();

        return $this->responseSuccess();
    }

}
