<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ShopProfile;
use App\Models\User;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;

class MetaDataController extends Controller
{
    use ResponseHandler;

    /**
     * @OA\Get(
     *
     *     path="/metadata",
     *     tags={"Meta Configuration"},
     *     summary="Get App Metadata",
     *     operationId="getWebAppMetaData",
     *
     *     @OA\Response(response=200,description="Success"),
     *
     * )
     */
    public function getAppMetadata()
    {

        $response = [
            "cities" => ShopProfile::getShopCities(),
            "areas"  => ShopProfile::getShopAreas(),
            "shops"  => ShopProfile::getAreaShops(),
            "services" => Service::getServices()
        ];

        return $this->responseSuccess($response);
    }
}