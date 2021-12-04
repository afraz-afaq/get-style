<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopStylistController;
use App\Http\Controllers\MetaDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('metadata', [MetaDataController::class, 'getAppMetadata']);

// protected routes
Route::group(['middleware' => ['auth:sanctum']], function ()
{
    //General User
    Route::group(['prefix' => 'user'], function ()
    {
        Route::post('/profile-picture', [UserController::class, 'updateProfilePicture']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });

    //Stylist
    Route::group(['prefix' => 'stylist'], function ()
    {
        Route::get('/top-rated', [ShopStylistController::class, 'getTopRatedStylists']);
        Route::get('/{offset}', [ShopStylistController::class, 'getAllStylists']);
    });

    Route::post('logout', [AuthController::class, 'logout']);

    //Profile
    Route::post('profile/update', [UserController::class, 'editProfile']);

});
