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
$authCheck  = new \app\middlewares\AdminAuthMiddleware();
$rememberMe = new \app\middlewares\RememberMeMiddleware();

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

    /* request data */
    $username   = Flight::request()->data->username;
    $password   = Flight::request()->data->password;
    $rememberMe = isset( Flight::request()->data->remember_me );

    // 1. Поиск пользователя
    $user = $db->fetchRow( 'SELECT * FROM users WHERE username = ?', [$username] );

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

        $session->set( 'flash_message', 'Успешная авторизация по паролю' );

        // --- ЛОГИКА "ЗАПОМНИТЬ МЕНЯ" ---
        if ( $rememberMe ) {
            $expireSeconds = 2592000; // 30 дней
            $rawToken      = bin2hex( random_bytes( 32 ) );
            $tokenHash     = hash( 'sha256', $rawToken );

            // 1. Запись в БД
            $db->runQuery(
                'INSERT INTO user_tokens (user_id, token_hash, user_agent, created_ip, expires_at)
         VALUES (?, ?, ?, ?, ?)',
                [
                    $user['id'],
                    $tokenHash,
                    Flight::request()->user_agent,
                    Flight::request()->ip,
                    date( 'Y-m-d H:i:s', time() + $expireSeconds ),
                ]
            );

            // 2. Установка КУКИ (без NULL)
            Flight::cookie()->set(
                'remember_token', // $name
                $rawToken,        // $value
                $expireSeconds,   // $expire
                '/',              // $path
                '',               // $domain (заменили null на пустую строку)
                true,             // $secure
                true              // $httponly
            );
            $session->set( 'flash_message', 'Мы вас помним' );
        }

        // --- КОНЕЦ ЛОГИКИ ---

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
    // echo '<pre>';
    // print_r( $_COOKIE ); // Проверка сырых данных PHP
    // echo '</pre>';
    // $rawToken = Flight::cookie()->get( 'remember_token' );
    // var_dump( $rawToken ); // Проверка через библиотеку
    // die();

    $session = Flight::session();
    $db      = Flight::db();

    // 1. Получаем токен из куки
    $rawToken = Flight::cookie()->get( 'remember_token' );

    // dd( $rawToken );
    if ( $rawToken ) {
        // 2. Удаляем хэш из базы
        $tokenHash = hash( 'sha256', $rawToken );
        $db->runQuery( 'DELETE FROM user_tokens WHERE token_hash = ?', [$tokenHash] );

        // 3. Удаляем куку (Используем ПРАВИЛЬНЫЙ порядок аргументов)
        // Аргументы: имя, значение, время, путь, домен, secure, httponly
        Flight::cookie()->set(
            'remember_token',
            '',
            -3600,
            '/',
            '',
            true,
            true
        );
    }

    // 4. Очистка сессии
    // Если вы хотите, чтобы flash_message дожил до страницы логина,
    // используйте clear() вместо destroy().
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
}, [$rememberMe, $authCheck] ); // Передаем объект middleware третьим аргументом

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
