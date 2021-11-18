<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use App\Traits\ResponseHandler;
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
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

        if ($validator->fails())
        {
            return $this->responseErrorValidation($validator->errors());
        }

        $userId = auth()->id();
        $file = $request->file('profile_picture');
        $fileName = "profile-" . $userId . "-" . $file->getClientOriginalName();
        Helper::uploadFile($file, $fileName);
        User::updateProfileImage($userId, $fileName);
        return $this->responseSuccess();
    }

}
