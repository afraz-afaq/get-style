<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\ShopProfile;
use App\Models\ShopRating;
use App\Models\ShopStylist;
use App\Models\ShopStylistRating;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Sentiment\Analyzer;

class RatingController extends Controller
{

    use ResponseHandler;

    /**
     * @OA\Post(
     *
     *     path="/user/rate",
     *     tags={"User"},
     *     summary="Rate shop or Stylist",
     *     operationId="rate",
     *
     *     @OA\RequestBody(
     *         description="Rate shop or Stylist.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="User Id that is rating",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="rate_type",
     *                     description="1 for shop and 2 for stylist",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *               @OA\Property(
     *                     property="to_be_rated",
     *                     description="Shop or stylist ID based on type",
     *                     type="integer",
     *                     example="3"
     *                 ),
     *              @OA\Property(
     *                     property="rating",
     *                     description="Rating by the user",
     *                     type="float",
     *                     example="3.5"
     *                 ),
     *          @OA\Property(
     *                     property="review",
     *                     description="Review by the user.",
     *                     type="string",
     *                     example="Very nice environment."
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


    public function rate(Request $request)
    {

        try
        {
            $requestData = $request->all();
            $analyzer = new Analyzer();
            $output = $analyzer->getSentiment($requestData['review']);
            $requestData['rating'] = Helper::getRatingFromSentimentOutput($output);
            if ($requestData['rate_type'] == 1)
            {
                ShopRating::create([
                    'user_id' => $requestData['user_id'],
                    'shop_id' => $requestData['to_be_rated'],
                    'rating'  => $requestData['rating'],
                    'review'  => $requestData['review']
                ]);

                $shop = ShopProfile::where('shop_id', '=', $requestData['to_be_rated'])->first();
                $count = ShopRating::where('shop_id', '=', $requestData['to_be_rated'])->count();
                $ratingSum = ShopRating::query()->where('shop_id', '=', $requestData['to_be_rated'])->sum('rating');
                $shop->total_reviews = $count;
                $shop->avg_rating = intval($ratingSum / $count);
                $shop->save();
            }
            else
            {
                ShopStylistRating::create([
                    'user_id'         => $requestData['user_id'],
                    'shop_stylist_id' => $requestData['to_be_rated'],
                    'rating'          => $requestData['rating'],
                    'review'          => $requestData['review']
                ]);

                $shopStylist = ShopStylist::where('id', '=', $requestData['to_be_rated'])->first();
                $count = ShopStylistRating::where('shop_stylist_id', '=', $requestData['to_be_rated'])->count();
                $shopStylist->total_reviews = $count;
                $shopStylist->avg_rating = intval(ShopStylistRating::query()->where('shop_stylist_id', '=', $requestData['to_be_rated'])->sum('rating') / $count);
                $shopStylist->save();
            }

            return $this->responseSuccess();
        }
        catch (\Exception $exception)
        {
            return $this->serverError($exception);
        }
    }


    /**
     * @OA\Post(
     *
     *     path="/user/getRatings",
     *     tags={"User"},
     *     summary="Get Ratings of shop or stylist.",
     *     operationId="getReviews",
     *
     *     @OA\RequestBody(
     *         description="Get Ratings of shop or stylist.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="reviews_for",
     *                     description="1 for shop and 2 for stylist.",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="reviews_for_id",
     *                     description="Shop id or stylist id.",
     *                     type="integer",
     *                     example="1"
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

    public function getReviews(Request $request)
    {
        try
        {
            $requestData = $request->all();
            if ($requestData['reviews_for'] == 1)
            {
                $reviews = ShopRating::where('shop_id', '=', $requestData['reviews_for_id'])->with('user')->get();
            }
            else
            {
                $reviews = ShopStylistRating::where('shop_stylist_id', '=', $requestData['reviews_for_id'])->with('user')->get();
            }

            return $this->responseSuccess(['reviews' => $reviews]);
        }
        catch (\Exception $exception)
        {
            return $this->serverError($exception);
        }
    }
}
