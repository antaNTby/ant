<?php
// settings.php

use flight\util\Collection;

Flight::set( 'arrSubs', [
    'currency',
    'catalog',
    'tree',
    'categories',
    'products',
    'customers',
    'companies',
    'contracts',
    'invoices',
    'orders',
    'statuses',
    'settings',
] );

$CollectionCorrectSubs = new Collection( [
    'currency',
    'catalog',
    'tree',
    'categories',
    'products',
    'customers',
    'companies',
    'contracts',
    'invoices',
    'orders',
    'statuses',
    'settings',
] );

const DEFAULT_ITEMS_PER_PAGE = 16;
const CODES                  = [
    100 => 'Continue',
    101 => 'Switching Protocols',
    102 => 'Processing',

    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    207 => 'Multi-Status',
    208 => 'Already Reported',

    226 => 'IM Used',

    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    306 => '(Unused)',
    307 => 'Temporary Redirect',
    308 => 'Permanent Redirect',

    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Payload Too Large',
    414 => 'URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Range Not Satisfiable',
    417 => 'Expectation Failed',

    422 => 'Unprocessable Entity',
    423 => 'Locked',
    424 => 'Failed Dependency',

    426 => 'Upgrade Required',

    428 => 'Precondition Required',
    429 => 'Too Many Requests',

    431 => 'Request Header Fields Too Large',

    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    506 => 'Variant Also Negotiates',
    507 => 'Insufficient Storage',
    508 => 'Loop Detected',

    510 => 'Not Extended',
    511 => 'Network Authentication Required',
];

// dd( $SubsCollection );

$cookie = Flight::cookie();

$containerWidthIndex = $cookie->get( 'COOKIE_WIDTH_INDEX' );
$containerWidthValue = $cookie->get( 'COOKIE_WIDTH_VALUE' );
$asideMenuPosition   = $cookie->get( 'COOKIE_MENU_POSITION' );

if ( ( null !== $containerWidthIndex ) && ( null !== $containerWidthValue ) ) {
    Flight::set( 'body_height', 'min-height: 100vh;max-height: 100vh;height: 100vh;' );
    Flight::set( 'body_width', "width:{$containerWidthValue}px;min-width:{$containerWidthValue}px;max-width:{$containerWidthValue}px;" );
} else {
    Flight::set( 'body_height', 'min-height: 100vh;max-height: 100vh;height: 100vh;' );
    Flight::set( 'body_width', 'width:200ch;min-width:200ch;max-width:200ch;' );
}

if ( null !== $asideMenuPosition ) {
    switch ( $asideMenuPosition ) {
        case 'left':
            Flight::set( 'aside_reverse', 0 );
            Flight::set( 'aside_hide', 0 );
            break;
        case 'right':
            Flight::set( 'aside_reverse', 1 );
            Flight::set( 'aside_hide', 0 );
            break;
        case 'off':
            Flight::set( 'aside_reverse', 0 );
            Flight::set( 'aside_hide', 1 ); // code...
            break;

        default:
            Flight::set( 'aside_reverse', 0 );
            Flight::set( 'aside_hide', 0 );
            break;
    }

} else {
    Flight::set( 'aside_reverse', 0 );
    Flight::set( 'aside_hide', 0 );

}

// dump(
//     [
//         $containerWidthIndex,
//         $containerWidthValue,
//         Flight::get( 'body_width' ),
//     ]

// );
