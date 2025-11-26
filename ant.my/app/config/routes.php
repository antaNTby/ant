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

Flight::route( 'GET /', function () {

    echo 'route GET /';

    Flight::render( __TPL__ . DIRECTORY_SEPARATOR . 'index.tpl.html', ['app' => Flight::app()] );

} );

Flight::route( 'GET /admin', function () {

    echo 'route GET /admin';

    Flight::render( __TPL__ . DIRECTORY_SEPARATOR . 'admin.tpl.html', ['app' => Flight::app()] );

} );

Flight::route( '*', function () {

    dump( Flight::router() );

    Flight::render( __TPL__ . DIRECTORY_SEPARATOR . 'index.tpl.html', ['app' => Flight::app()] );

} );

// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'ui_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'api_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'sub_routes.php';
