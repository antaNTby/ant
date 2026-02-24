<?php
namespace app\middlewares;

use Flight;

class AdminAuthMiddleware
{
    public function before()
    {
        // Вызываем объединенный метод
        if ( !Flight::auth()->checkAccess() ) {
            Flight::redirect( '/login?error=Session+Expired' );
            exit;
        }

        // Проверка роли (админ или нет)
        if ( Flight::session()->get( 'user_role' ) !== 'admin' ) {
            Flight::redirect( '/login?error=No+Permission' );
            exit;
        }
    }
}

class AdminAuthMiddlewareOLD
{
    public function before()
    {

        $session = Flight::session();
        $timeout = Flight::get( 'SESSION_EXPIRE_TIMEOUT' ) ?? 2 * 60 * 60;
        $now     = time();

        // 1. ПРОВЕРКА АВТОРИЗАЦИИ
        // Если RememberMeMiddleware отработал успешно, здесь уже БУДЕТ user_id
        if ( !$session->get( 'user_id' ) ) {
            $session->set( 'flash_message', 'Неверные данные' );
            Flight::redirect( '/login?error=Access+Denied' );
            exit;
        }

        // 2. ПРОВЕРКА РОЛИ
        if ( $session->get( 'user_role' ) !== 'admin' ) {
            $session->set( 'flash_message', 'No Permission' );
            Flight::redirect( '/login?error=No+Permission' );
            exit;
        }

        // 3. ТАЙМАУТ СЕССИИ
        // ВАЖНО: Если пользователь нажал "Запомнить меня", таймаут сессии обычно игнорируют
        // или делают очень длинным. Но оставим вашу логику:
        $lastActivity = $session->get( 'last_activity' );

        if ( $lastActivity && ( $now - $lastActivity > $timeout ) ) {
            // ПРОВЕРКА: Если есть кука "запомнить меня", НЕ выкидываем пользователя,
            // а просто позволяем сессии обновиться (или полагаемся на RememberMeMiddleware)
            if ( Flight::cookie()->get( 'remember_token' ) ) {
                $session->set( 'flash_message', 'продлеваем время по токену' );
                $session->set( 'last_activity', $now ); // Продлеваем сессию "на лету"
            } else {
                $session->clear();
                $session->set( 'flash_message', 'Время сессии истекло' );
                Flight::redirect( '/login?error=Session+Expired' );
                exit;
            }
        }

        // 4. РЕГЕНЕРАЦИЯ И ОБНОВЛЕНИЕ
        if ( !Flight::request()->ajax ) {
            $session->regenerate( true );
        }

        $session->set( 'last_activity', $now );
        $session->set( 'time', date( 'Y-m-d H:i:s' ) );
        // Передаем данные в Smarty
        Flight::view()->assign( 'session_timeout_seconds', $timeout );
        Flight::view()->assign( 'user', [
            'name' => $session->get( 'user_name' ),
            'role' => $session->get( 'user_role' ),
        ] );
    }
}
