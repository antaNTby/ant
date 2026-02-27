<?php
/*use app\controllers\ApiExampleController;*/
/*use app\middlewares\SecurityHeadersMiddleware;*/
use flight\Engine;
use flight\net\Router;
use flight\Session;
use flight\util\Json;

// echo password_hash( '12345', PASSWORD_DEFAULT );

// Whip out the ol' router and we'll pass that to the routes file
$router         = $app->router();
$authController = new \app\controllers\AuthController();

// Инициализируем сессию ПЕРЕД роутами
$session = new \flight\Session();
// Регистрируем middleware
$authCheckAdmin = new \app\middlewares\AdminAuthMiddleware();
$authCheckUser  = new \app\middlewares\UserAuthMiddleware();
$rememberMe     = new \app\middlewares\RememberMeMiddleware();

################################################################
################################################################
################################################################
###############   routes login\logout    #######################
################################################################
################################################################
################################################################

// Регистрация
Flight::route( 'GET /register', [$authController, 'showRegistrationForm'] );
Flight::route( 'POST /register', [$authController, 'handleRegistrationForm'] );

// Авторизация
Flight::route( 'GET /login', [$authController, 'showLoginForm'] );
Flight::route( 'POST /login', [$authController, 'handleLoginForm'] );

// Выход
Flight::route( '/logout', [$authController, 'handleLogout'] );
Flight::route( 'POST /logout-all', [$authController, 'handleLogoutEverywhere'] );

Flight::route( 'GET /admin/links/deleteExpiredTokens', [$authController, 'handleDeleteExpiredTokens'] )->addMiddleware( $authCheckAdmin );
################################################################
################################################################
################################################################
###############      routes /admin       #######################
################################################################
################################################################
################################################################

// 1. Группа для админки (обрабатывается ПЕРВОЙ)
Flight::group( '/admin', function () {

    Flight::group( '/dpt/subs', function () {
        $authController = new \app\controllers\AuthController();

        Flight::route( 'GET /sessions', [$authController, 'showSessions'] );
        // Нам также понадобится удаление конкретной сессии
        Flight::route( 'POST /logout-session', [$authController, 'handleLogoutSession'] );

        Flight::route( '/@subNam5555e', function ( $subName ) {
            $db     = Flight::db();
            $userId = Flight::session()->get( 'user_id' );

            $subData = [
                'userId'  => $userId,
                'subName' => $subName,

            ];
            if ( $subName == 'sessions' ) {

                $sessions = $db->fetchAll(
                    'SELECT *
                     FROM user_tokens
                     WHERE user_id = ? AND expires_at > NOW()
                     ORDER BY last_used_at DESC',
                    [$userId]
                );
                // "Причесываем" данные перед отправкой в шаблон
                foreach ( $sessions as &$s ) {
                    $s['device_info'] = Flight::authService()->parseUserAgent( $s['user_agent'] );
                }

                $subData = [
                    'subName'  => $subName,
                    'sessions' => $sessions,

                ];
            }

            Flight::render( 'admin/dpt/subs/' . $subName . '.tpl.html', ['subData' => $subData] );
        } );

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

            $session->set( 'session_message', 'Доступ для устройства отозван' );
            Flight::flash( 'danger', 'Доступ для устройства отозван' );
        }

        Flight::redirect( '/dpt/subs/sessions' );
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
}, [$rememberMe, $authCheckAdmin] );

################################################################
################################################################
################################################################
###############      routes /b2b         #######################
################################################################
################################################################
################################################################

Flight::group( '/b2b', function () {

    // Этот маршрут сработает для любого пути, начинающегося на /admin/...
    Flight::route( '/*', function () {

        $renderData = [
            'app'   => Flight::app(),
            'title' => 'b2b: ' . date( 'H:i:s' ),
            // другие данные...
        ];

        Flight::render( 'b2b/index.tpl.html', $renderData );
    } );
}, [$rememberMe, $authCheckUser] );

################################################################
################################################################
################################################################
#################       routes root *        ###################
################################################################
################################################################
################################################################

// 2. Маршрут для главной страницы (точный путь '/')
Flight::route( '/', function () {
    $renderData = [
        'app'   => Flight::app(),
        'title' => 'Главная страница',
    ];
    Flight::render( 'b2b/index.tpl.html', $renderData );
} );

// 3. Глобальный "запасной" маршрут (только в самом КОНЦЕ)
// Сюда попадет всё, что не / и не начинается на /admin
Flight::route( '*', function () {
    $renderData = [
        'app'   => Flight::app(),
        'title' => '404 Страница не найдена или Home',
    ];
    Flight::render( 'b2b/index.tpl.html', $renderData );
} );
