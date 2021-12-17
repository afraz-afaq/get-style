<?php

namespace App\Helpers;

class Constant
{
    const TRUE = 1;
    const FALSE = 0;

    const ROLES = [
        'CUSTOMER' => 1,
        'SHOP'     => 2,
        'STYLIST'  => 3
    ];

    const HTTP_RESPONSE_MESSAGES = [
        'success'             => 'Request Successful',
        'failed'              => 'Request Failed',
        'validationError'     => 'Validation Error',
        'authenticationError' => "Unauthenticated",
        'authorizationError'  => 403,
        'serverError'         => 500,
        'notFound'            => "Resource Not Found",
    ];

    const APP_TOKEN_NAME = 'get-style-access';

    const DEFAULT_PAGINATION_NUMBER = 20;

    const ORDER_PENDING = 1;
    const ORDER_CONFIRMED = 2;
    const ORDER_PROCESS = 3;
    const ORDER_COMPLETED = 4;
}
