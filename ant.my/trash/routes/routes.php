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

$authController = new \app\controllers\AuthController();

Flight::route( 'GET /sessions', [$authController, 'showSessions'] );
// Нам также понадобится удаление конкретной сессии
Flight::route( 'POST /logout-session', [$authController, 'handleLogoutSession'] );

Flight::route( '/@subNam', function ( $subName ) {
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

    Flight::Display( 'admin/dpt/subs/' . $subName . '.tpl.html', ['subData' => $subData] );
} );
