<?php
/*use app\controllers\ApiExampleController;*/
/*use app\middlewares\SecurityHeadersMiddleware;*/
use flight\Engine;
use flight\net\Router;
use flight\Session;
use flight\util\Json;

// Whip out the ol' router and we'll pass that to the routes file
$router = $app->router();

// Инициализируем сессию ПЕРЕД роутами
$session = new \flight\Session();

// Регистрируем middleware
$authCheck = new \app\middlewares\AdminAuthMiddleware();

Flight::group( '/api', function () {
    // Вариант v3 с использованием метода addMiddleware
    Flight::route( 'GET /admin/ping', function () {

        Flight::json( [
            'status' => 'success',
            'msg'    => 'Session regenerated and extended',
            'time'   => date( 'H:i:s' ),
        ] );
    } )->addMiddleware( new \app\middlewares\AdminAuthMiddleware() );
    // Другие API роуты
}, [new \app\middlewares\ApiMiddleware()] );

// Страница показа формы
Flight::route( 'GET /login', function () {
    $session = Flight::session();
    // Если уже залогинен — кидаем в админку
    if ( $session->get( 'is_admin' ) ) {
        $session->set( 'flash_message', 'Неверный аккаунт :: GET /login' );
        Flight::redirect( '/admin' );
    }

    Flight::render( 'login.tpl.html', [
        'year'  => date( 'Y' ),
        'error' => Flight::request()->query->error, // Получаем ошибку из URL
    ] );
} );

// Обработка отправки формы
Flight::route( 'POST /login', function () {
    $session  = Flight::session();
    $username = Flight::request()->data->username;
    $password = Flight::request()->data->password;

    // ВАЖНО: Замените на реальную проверку БД или конфига
    if ( $username === 'admin' && $password === '12345' ) {
        $session->set( 'is_admin', true );
        $session->set( 'user_name', 'Administrator' );

        Flight::redirect( '/admin' );
    } else {
        // Если данные неверны, возвращаем на логин с ошибкой
        $session->set( 'flash_message', 'Неверный аккаунт :: POST /login' );
        Flight::redirect( '/login?error=Access+Denied' );
    }
} );

// Выход
Flight::route( '/logout', function () {
    $session = Flight::session();

    // Устанавливаем flash-сообщение перед выходом
    // Важно: если используете destroy(), flash может не сохраниться.
    // Лучше использовать clear(), чтобы сессия осталась жива для сообщения.
    $session->clear();
    $session->set( 'flash_message', 'Вы успешно вышли из системы' );
    Flight::redirect( '/login' );
} );

// 1. Группа для админки (обрабатывается ПЕРВОЙ)
Flight::group( '/admin', function () {

    // Этот маршрут сработает для любого пути, начинающегося на /admin/...
    Flight::route( '/*', function () {
        // Здесь можно добавить проверку сессии
        // if (!$session->get('is_admin')) { Flight::redirect('/login'); }

        $renderData = [
            'app'   => Flight::app(),
            'title' => 'Админка: ' . date( 'H:i:s' ),
            // другие данные...
        ];

        Flight::render( 'admin/index.tpl.html', $renderData );
    } );
}, [$authCheck] ); // Передаем объект middleware третьим аргументом

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
