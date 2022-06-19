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
        'Fair'              => '0.5',
        'Fear'              => '0.5',
        'Feer'              => '0.5',
        'Splendid'          => '1.5',
        'Not good'          => '-0.5',
        'Satisfied'         => '1.0',
        'Not satisfied'     => '0',
        'Pathetic'          => '-1.5',
        'Worst'             => '-1.5',
        'Better'            => '1.0',
        'Average'           => '1.0',
        'But'               => '1.0',
        'Recommended'       => '1.5',
        'Not recommended'   => '-1.0',
        'Improve'           => '1.0',
        'Unprofessional'    => '-1.0',
        'Professionalism'   => '1.0',
        'unprofessionalism' => '-1.0',
        'Good'              => '1.0',
        'Best'              => '1.5',
        'Excellent'         => '1.5',
        'Supportive'        => '1.3',
        'Responsive'        => '1.2',
        'Quick service'     => '1.3',
        'Best service'      => '1.5',
        'Worst service'     => '-1.5',
        'Illiterate'        => '-1.5',
        'Awesome'           => '1.1',
        'Formal'            => '1.1',
        'Informal'          => '0.8',
        'Not impressive'    => '-1',
        'Impressive'        => '1.3',
        'Inspire'           => '1.5',
        'Inspiring'         => '1.5',
        'Amazing'           => '1.5',
        'Fabulous'          => '1.5',
        'inappropriate'     => '-1.1',
        'Good Experience'   => '1.0',
        'Bed service'       => '-0.5',
        'Bad service'       => '-0.5',
        'Cheap'             => '1',
        'Expensive'         => '-0.5',
    ];
}
