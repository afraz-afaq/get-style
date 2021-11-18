<?php

namespace App\Traits;

use Response;
use App\Helpers\Constant;
use Illuminate\Http\Resources\Json\Resource;
use \Symfony\Component\HttpFoundation\Response as ResponseCode;

trait ResponseHandler
{
    protected function responseSuccess($data = [], $message = Constant::HTTP_RESPONSE_MESSAGES['success'])
    {
        return self::send(ResponseCode::HTTP_OK, $message, $data, [], null);
    }

    protected function responseErrorFailed($exception, $message = Constant::HTTP_RESPONSE_MESSAGES['failed'])
    {
        return self::send(ResponseCode::HTTP_BAD_REQUEST, $message, [], [], $exception);
    }

    protected function responseErrorNotFound($message = Constant::HTTP_RESPONSE_MESSAGES['notFound'])
    {
        return self::send(ResponseCode::HTTP_NOT_FOUND, $message, [], [], null);
    }

    protected function responseErrorAuth($message = Constant::HTTP_RESPONSE_MESSAGES['authenticationError'])
    {
        return self::send(ResponseCode::HTTP_UNAUTHORIZED, $message, [], [], null);
    }

    protected function responseErrorValidation($errors = [], $message = Constant::HTTP_RESPONSE_MESSAGES['validationError'])
    {
        $data = [];
        if (!is_array($errors))
        {
            $errors = $errors->first();
            $errors = [$errors];
        }
        return self::send(ResponseCode::HTTP_UNPROCESSABLE_ENTITY, $message, $data, $errors, null);
    }

    protected function serverError($exception = null, $message = "")
    {
        $exceptionMsg = $exception ? $exception->getMessage() : '';
        return self::send(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $exceptionMsg, [], [], $exceptionMsg);
    }


    private static function send($status, $message, $data, $errors, $exception)
    {
        //TODO: need to update this
        if (empty($data))
        {
            $data = json_decode("{}");
        }

        return response()->json([
            'status'    => $status,
            'message'   => $message,
            'data'      => $data,
            'errors'    => $errors,
            'exception' => config('app.debug') ? $exception : null
        ], $status, [], JSON_UNESCAPED_UNICODE);
    }
}
