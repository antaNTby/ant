<?php
/*use app\controllers\ApiExampleController;*/
/*use app\middlewares\SecurityHeadersMiddleware;*/
use flight\Engine;
use flight\net\Router;
use flight\Session;
use flight\util\Json;

// Whip out the ol' router and we'll pass that to the routes file
$router = $app->router();

// 1. Группа для админки (обрабатывается ПЕРВОЙ)
Flight::group( '/admin', function () {

    // Этот маршрут сработает для любого пути, начинающегося на /admin/...
    Flight::route( '/*', function () {
        // Здесь можно добавить проверку сессии
        // if (!Flight::session()->get('is_admin')) { Flight::redirect('/login'); }

        $renderData = [
            'app'   => Flight::app(),
            'title' => 'Админка: ' . date( 'H:i:s' ),
            // другие данные...
        ];

        Flight::render( 'admin/index.tpl.html', $renderData );
    } );
} );

// 2. Маршрут для главной страницы (точный путь '/')
Flight::route( '/', function () {
    $renderData = [
        'app'   => Flight::app(),
        'title' => 'Главная страница',
    ];
    Flight::render( 'home/index.tpl.html', $renderData );
} );

// 3. Глобальный "запасной" маршрут (только в самом КОНЦЕ)
// Сюда попадет всё, что не / и не начинается на /admin
Flight::route( '*', function () {
    $renderData = [
        'app'   => Flight::app(),
        'title' => '404 Страница не найдена или Home',
    ];
    Flight::render( 'home/index.tpl.html', $renderData );
} );

Flight::start();
