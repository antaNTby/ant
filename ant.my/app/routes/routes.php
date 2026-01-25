<?php
use app\controllers\ApiExampleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
use flight\Session;

// Whip out the ol' router and we'll pass that to the routes file
$router = $app->router();
/**/
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

    if ( $session->get( 'login' ) ) {

        Flight::render( 'layout.tpl.html',
            $rednderData
        );
    } else {
        Flight::render( 'home.tpl.html', [
            'session' => [
                $session->get( 'login' ),
                $session->get( 'is_admin' ),
                $session->get( 'paswordhash' ),
            ],
        ] );
    }

} );

Flight::route( 'GET /login', function () {
    $session = Flight::session();
    $session->set( 'login', 'admin' );
    $session->set( 'is_admin', true );
    $session->set( 'paswordhash', password_hash( 'paSS$$word', PASSWORD_DEFAULT ) );
    $rednderData = [

        'app'       => Flight::app(),
        'year'      => date( 'Y' ),
        'title'     => SERVER_NAME . ' ' . date( 'Y' ) . '-' . date( 'M' ) . '-' . date( 'd' ) . ' ' . date( 'H' ) . ':' . date( 'm' ) . ':' . date( 'i' ),
        'COPYRIGHT' => COPYRIGHT,
        // 'BRANDNAME' => BRANDNAME,

    ];
    Flight::render( 'layout.tpl.html',
        $rednderData
    );
} );

Flight::route( 'GET /logout', function () {
    $session = Flight::session();
    $session->clear();
    $rednderData = [

        'app'       => Flight::app(),
        'year'      => date( 'Y' ),
        'title'     => SERVER_NAME . ' ' . date( 'Y' ) . '-' . date( 'M' ) . '-' . date( 'd' ) . ' ' . date( 'H' ) . ':' . date( 'm' ) . ':' . date( 'i' ),
        'COPYRIGHT' => COPYRIGHT,
        // 'BRANDNAME' => BRANDNAME,

    ];
    Flight::render( 'home.tpl.html',
        $rednderData
    );
} );
