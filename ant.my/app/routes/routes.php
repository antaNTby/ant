<?php
/*use app\controllers\ApiExampleController;*/
/*use app\middlewares\SecurityHeadersMiddleware;*/
use flight\Engine;
use flight\net\Router;
use flight\Session;
use flight\util\Json;

// echo password_hash( '12345', PASSWORD_DEFAULT );

// Whip out the ol' router and we'll pass that to the routes file
$router = $app->router();

// Инициализируем сессию ПЕРЕД роутами
$session = new \flight\Session();
// Регистрируем middleware
$authCheck = new \app\middlewares\AdminAuthMiddleware();

Flight::route( 'GET /api/admin/ping', function () {
// Вся магия (продление сессии и отключение Tracy) уже произошла в Middleware
    Flight::json( [
        'status' => 'success',
        'time'   => date( 'H:i:s' ),
    ] );
} )->addMiddleware( new $authCheck );

// Страница показа формы
Flight::route( 'GET /login', function () {
    // dd( Flight::request() );
    $session = Flight::session();
    // Если уже залогинен — кидаем в админку
    if ( $session->get( 'user_role' ) === 'admin' ) {
        $session->set( 'flash_message', 'Полный доступ' );
        Flight::redirect( '/admin/dashboard' );
        // Flight::redirect( '/admin/login' );
    }

    Flight::render( 'login.tpl.html', [
        'year'  => date( 'Y' ),
        'error' => Flight::request()->query->error, // Получаем ошибку из URL
    ] );
} );

Flight::route( 'POST /login', function () {
    $db      = Flight::db();
    $session = Flight::session();

    /* request data*/
    $username   = Flight::request()->data->username;
    $email      = Flight::request()->data->email;
    $password   = Flight::request()->data->password;
    $rememberMe = isset( Flight::request()->data->remember_me );

    // 1. Поиск пользователя
    $user = $db->fetchRow( 'SELECT * FROM users WHERE username = ?', [$username] );
    // $user = verifyUser( $email, $password );
    // if ( !$user ) {
    //     Flight::halt( 401, '401 - Неверные данные' );
    // }
    if ( $user && password_verify( $password, $user['password_hash'] ) ) {

        // 2. ОБНОВЛЯЕМ ВРЕМЯ ВХОДА В БД
        $db->runQuery( 'UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']] );

        // 3. Устанавливаем сессию
        $session->regenerate( true );
        $session->set( 'is_admin', ( $user['role'] === 'admin' ) );
        $session->set( 'user_id', $user['id'] );
        $session->set( 'user_name', $user['username'] );
        $session->set( 'user_role', $user['role'] );
        $session->set( 'last_activity', time() );

        // dd( Flight::request()->data );

        $session->set( 'flash_message', 'Успешная авторизация по паролю' );

        if ( $session->get( 'user_role' ) === 'admin' ) {
            Flight::redirect( '/admin/settings' );
        } else {
            Flight::redirect( '/home/wellcome' );
        }

    } else {

        $session->set( 'flash_message', 'Неверные данные' );
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
