<?php
use app\controllers\ApiExampleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
use flight\Session;

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
    Flight::halt( 403, 'Access denied' );
} );

Flight::route( 'GET /logout', function () {
    $session = Flight::session();
// Remove a session value
    $session->delete( 'log' );
    $session->delete( 'is_admin' );
    $session->delete( 'paswordhash' );
    // Flight::halt( 403, 'Access denied' );

    Flight::render( 'home.tpl.html', [
        'session' => [
            $session->get( 'log' ),
            $session->get( 'is_admin' ),
            $session->get( 'paswordhash' ),
        ],
    ] );
} );

Flight::route( '*', function () {
    $session = Flight::session();

    // dump( Flight::router() );
    $rednderData = [

        'app'       => Flight::app(),
        'year'      => date( 'Y' ),
        'title'     => SERVER_NAME . ' ' . date( 'Y' ) . '-' . date( 'M' ) . '-' . date( 'd' ) . ' ' . date( 'H' ) . ':' . date( 'm' ) . ':' . date( 'i' ),
        'COPYRIGHT' => COPYRIGHT,
        // 'BRANDNAME' => BRANDNAME,

    ];

    if ( $session->get( 'log' ) ) {

        Flight::render( __TPL__ . DIRECTORY_SEPARATOR . DEFAULT_TPL_HTML,
            $rednderData
        );
    } else {
        Flight::render( 'home.tpl.html', [
            'session' => [
                $session->get( 'log' ),
                $session->get( 'is_admin' ),
                $session->get( 'paswordhash' ),
            ],
        ] );
    }

} );

Flight::route( 'GET /admin', function () {
    $session = Flight::session();

    $session->set( 'log', 'admin' );
    $session->set( 'is_admin', true );
    $session->set( 'paswordhash', password_hash( 'string', PASSWORD_DEFAULT ) );

    // dump( Flight::router() );

    Flight::render( __TPL__ . DIRECTORY_SEPARATOR . DEFAULT_TPL_HTML,
        [

            'app'       => Flight::app(),
            'year'      => date( 'Y' ),
            'title'     => SERVER_NAME . ' ' . date( 'Y' ) . '-' . date( 'M' ) . '-' . date( 'd' ) . ' ' . date( 'H' ) . ':' . date( 'm' ) . ':' . date( 'i' ),
            'COPYRIGHT' => 'ADMIN',
            // 'BRANDNAME' => 'ADMIN ' . BRANDNAME,
            'session'   => [

                $session->get( 'log' ),
                $session->get( 'is_admin' ),
                $session->get( 'paswordhash' ),
            ],

        ] );

} );

// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'ui_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'api_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'sub_routes.php';
