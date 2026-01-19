<?php
use app\controllers\ApiExampleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

// Whip out the ol' router and we'll pass that to the routes file
$router = $app->router();
/**/

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group( '', function ( Router $router ) use ( $app ) {

    $router->get( '/', function () use ( $app ) {
        $app->render( 'welcome', ['message' => 'You are gonna do great things!'] );
    } );

    $router->get( '/hello-world/@name', function ( $name ) {
        echo '<h1>Hello world! Oh hey ' . $name . '!</h1>';
    } );

    $router->group( '/api', function () use ( $router ) {
        $router->get( '/users', [ApiExampleController::class, 'getUsers'] );
        $router->get( '/users/@id:[0-9]', [ApiExampleController::class, 'getUser'] );
        $router->post( '/users/@id:[0-9]', [ApiExampleController::class, 'updateUser'] );
    } );

}, [SecurityHeadersMiddleware::class] );

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
            'year'      => date( 'Y' ),
            'title'     => SERVER_NAME . ' ' . date( 'Y' ) . '-' . date( 'M' ) . '-' . date( 'd' ) . ' ' . date( 'H' ) . ':' . date( 'm' ) . ':' . date( 'i' ),
            'COPYRIGHT' => COPYRIGHT,
            // 'BRANDNAME' => BRANDNAME,

        ]
    );

} );

Flight::route( 'GET /admin', function () {

    // dump( Flight::router() );

    Flight::render( __TPL__ . DIRECTORY_SEPARATOR . DEFAULT_TPL_HTML,
        [

            'app'       => Flight::app(),
            'year'      => date( 'Y' ),
            'title'     => SERVER_NAME . ' ' . date( 'Y' ) . '-' . date( 'M' ) . '-' . date( 'd' ) . ' ' . date( 'H' ) . ':' . date( 'm' ) . ':' . date( 'i' ),
            'COPYRIGHT' => 'ADMIN',
            // 'BRANDNAME' => 'ADMIN ' . BRANDNAME,

        ]
    );

} );

// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'ui_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'api_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'sub_routes.php';
