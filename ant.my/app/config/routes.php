<?php

Flight::route( 'OPTIONS *', function () {
    // https: //docs.flightphp.com/learn/security#cors
    header( 'Access-Control-Allow-Origin: *' );
    // header('Access-Control-Allow-Origin: https://your-domain.com');
    header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
    header( 'Access-Control-Allow-Headers: Content-Type, Authorization' );
    header( 'Access-Control-Max-Age: 86400' ); // Кеширование
    Flight::halt( 200 );
} );

Flight::route( 'GET /hello', function () {
    echo '<h1>Welcome to the Flight Simple Example!</h1><h2>You are gonna do great things!</h2>';
} );

Flight::route( '*', function () {

    // dump( Flight::router() );

    Flight::render( __TPL__ . DIRECTORY_SEPARATOR . DEFAULT_TPL_HTML,
        [

            'app'       => Flight::app(),
            'title'     => SERVER_NAME . ' ' . COPYRIGHT,
            'year'      => date( 'Y' ),
            'COPYRIGHT' => COPYRIGHT,
            'BRANDNAME' => BRANDNAME,

        ]
    );

} );

Flight::route( 'GET /admin', function () {

    // dump( Flight::router() );

    Flight::render( __TPL__ . DIRECTORY_SEPARATOR . DEFAULT_TPL_HTML,
        [

            'app'       => Flight::app(),
            'title'     => SERVER_NAME . ' ' . COPYRIGHT,
            'year'      => date( 'Y' ),
            'COPYRIGHT' => 'ADMIN',
            'BRANDNAME' => 'ADMIN ' . BRANDNAME,

        ]
    );

} );

// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'ui_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'api_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'sub_routes.php';
