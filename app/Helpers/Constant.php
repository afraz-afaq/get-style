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

    const NOTIFICATION_TYPES = [
        'TEST' => 1,
    ];

    const DICT = [
        'rubbish'           => '-1.5',
        'mediocre'          => '-1.0',
        'agressive'         => '-0.5',
        'professional'      => '1.5',
        'professionalism'   => '1.5',
        'reasonable'        => '1.5',
        'fair'              => '0.5',
        'fear'              => '0.5',
        'feer'              => '0.5',
        'splendid'          => '1.5',
        'not good'          => '-0.5',
        'satisfied'         => '1.0',
        'not satisfied'     => '0',
        'pathetic'          => '-1.5',
        'worst'             => '-1.5',
        'better'            => '1.0',
        'average'           => '1.0',
        'but'               => '1.0',
        'recommended'       => '1.5',
        'not recommended'   => '-1.0',
        'improve'           => '1.0',
        'unprofessional'    => '-1.0',
        'professionalism'   => '1.0',
        'unprofessionalism' => '-1.0',
        'good'              => '1.0',
        'best'              => '1.5',
        'excellent'         => '1.5',
        'supportive'        => '1.3',
        'responsive'        => '1.2',
        'quick service'     => '1.5',
        'best service'      => '1.5',
        'worst service'     => '-1.5',
        'illiterate'        => '-1.5',
        'awesome'           => '1.1',
        'formal'            => '1.1',
        'informal'          => '0.8',
        'not impressive'    => '-1',
        'impressive'        => '1.3',
        'inspire'           => '1.5',
        'inspiring'         => '1.5',
        'amazing'           => '1.5',
        'fabulous'          => '1.5',
        'inappropriate'     => '-1.1',
        'good Experience'   => '1.0',
        'bed service'       => '-0.5',
        'bad service'       => '-0.5',
        'cheap'             => '1',
        'expensive'         => '-0.5',
        'expected'          => '1.5'
    ];
}
