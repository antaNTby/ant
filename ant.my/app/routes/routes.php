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
    $request = Flight::request();

    $username   = $request->data->username;
    $password   = $request->data->password;
    $rememberMe = isset( $request->data->remember_me );

    // 1. Поиск пользователя
    $user = $db->fetchRow( 'SELECT * FROM users WHERE username = ?', [$username] );

    // 2. Проверка пароля и статуса бана
    if ( $user && password_verify( $password, $user['password_hash'] ) ) {

        if ( !$user['is_active'] ) {
            $session->set( 'flash_message', 'Ваш аккаунт заблокирован' );
            Flight::redirect( '/login?error=Banned' );

            return;
        }

        // 3. Базовая авторизация в сессию
        $session->regenerate( true );
        $session->set( 'user_id', $user['id'] );
        $session->set( 'user_name', $user['username'] );
        $session->set( 'user_role', $user['role'] );
        $session->set( 'is_admin', ( $user['role'] === 'admin' ) );
        $session->set( 'last_activity', time() );

        // 4. Логика "Запомнить меня"
        if ( $rememberMe ) {
            $expireSeconds = 2592000;
            $rawToken      = bin2hex( random_bytes( 32 ) );
            $tokenHash     = hash( 'sha256', $rawToken );

            // Вставляем запись
            $db->runQuery(
                'INSERT INTO user_tokens (user_id, token_hash, user_agent, created_ip, expires_at) VALUES (?, ?, ?, ?, ?)',
                [$user['id'], $tokenHash, $request->user_agent, $request->ip, date( 'Y-m-d H:i:s', time() + $expireSeconds )]
            );

                                                 // ПОЛУЧАЕМ ID напрямую из $db
            $lastInsertId = $db->lastInsertId(); // <--- Исправлено здесь
            $session->set( 'current_token_id', $lastInsertId );

            // Устанавливаем куку
            Flight::cookie()->set( 'remember_token', $rawToken, $expireSeconds, '/', '', false, true );
        }

        // Обновляем время входа в таблице users
        $db->runQuery( 'UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']] );

        $session->set( 'flash_message', 'Добро пожаловать, ' . $user['username'] );

        // Редирект по роли
        if ( $user['role'] === 'admin' ) {
            Flight::redirect( '/admin/settings' );
        } else {
            Flight::redirect( '/home/wellcome' );
        }

    } else {
        $session->set( 'flash_message', 'Неверный логин или пароль' );
        Flight::redirect( '/login?error=Access+Denied' );
    }
} );

// Выход
Flight::route( '/logout', function () {
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

    Flight::route( '/sessions', function () {
        $db     = Flight::db();
        $userId = Flight::session()->get( 'user_id' );

        $sessions = $db->fetchAll(
            'SELECT id, user_agent, created_ip, last_used_at
         FROM user_tokens
         WHERE user_id = ? AND expires_at > NOW()
         ORDER BY last_used_at DESC',
            [$userId]
        );
        // "Причесываем" данные перед отправкой в шаблон
        foreach ( $sessions as &$s ) {
            $s['device_info'] = Flight::auth()->parseUserAgent( $s['user_agent'] );
        }
        Flight::render( 'admin/pages/sessions.tpl.html', ['sessions' => $sessions] );
    } );

    Flight::route( 'POST /sessions/revoke', function () {
        $db      = Flight::db();
        $session = Flight::session();

        // Получаем ID токена из скрытого поля формы
        $tokenId = Flight::request()->data->token_id;
        $userId  = $session->get( 'user_id' );

        if ( $tokenId && $userId ) {
            // Удаляем токен, проверяя, что он принадлежит именно этому пользователю (безопасность!)
            $db->runQuery(
                'DELETE FROM user_tokens WHERE id = ? AND user_id = ?',
                [$tokenId, $userId]
            );

            // Если пользователь удалил текущую сессию (токен которой в куке)
            $currentRawToken = Flight::cookie()->get( 'remember_token' );
            if ( $currentRawToken ) {
                $currentTokenHash = hash( 'sha256', $currentRawToken );
                $isCurrent        = $db->fetchRow(
                    'SELECT id FROM user_tokens WHERE id = ? AND token_hash = ?',
                    [$tokenId, $currentTokenHash]
                );

                // Если удаляемый ID совпадает с текущим токеном в куке — чистим куку
                if ( $isCurrent ) {
                    Flight::cookie()->set( 'remember_token', '', -3600, '/', '', false, true );
                }
            }

            $session->set( 'flash_message', 'Доступ для устройства отозван' );
        }

        Flight::redirect( '/admin/sessions' );
    } );

    // Этот маршрут сработает для любого пути, начинающегося на /admin/...
    Flight::route( '/*', function () {

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
