<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *     description="Get Style APIs",
     *     version="1.0",
     *     title="Get Style APIs",
     *     @OA\Contact(
     *         email="dev@get-style.com"
     *     )
     * )
     */

    /**
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description=L5_SWAGGER_CONST_ENV
     *  )
     */

    /**
     * @OA\SecurityScheme(
     *     type="apiKey",
     *     description="user access token",
     *     name="Authorization",
     *     in="header",
     *     securityScheme="user_access_token"
     * )
     */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
