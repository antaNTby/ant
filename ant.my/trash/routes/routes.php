<?php
/*use app\controllers\ApiExampleController;*/
/*use app\middlewares\SecurityHeadersMiddleware;*/
use Firebase\JWT\JWT;
use flight\Engine;
use flight\net\Router;
use flight\Session;
use flight\util\Json;

// Whip out the ol' router and we'll pass that to the routes file
$router = $app->router();
/**/

#### Если вы хотите применить глобальный middleware ко всем вашим маршрутам, вы можете добавить "пустую" группу:
// добавлено в конце метода группы
Flight::group( '/admin', function ( Router $router ) {

}, [LoggedInMiddleware::class] ); // или [ new ApiAuthMiddleware() ], одно и то же

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

    Flight::render( 'home/index.tpl.html',
        $rednderData
    );

} );

// Страница логина
Flight::route( 'GET /login', function () {
    $session = Flight::session();
    Flight::render( 'layout.tpl.html', [
        'title'        => 'Авторизация',
        'info_message' => 'error', // Получаем flash-сообщение
    ] );
} );

// Страница логаута (если нужен шаблон)
Flight::route( 'GET /logout', function () {
    $session = Flight::session();
    $session->clear();
    Flight::render( 'layout.tpl.html', [
        'title' => 'Выход из системы',
    ] );
} );
