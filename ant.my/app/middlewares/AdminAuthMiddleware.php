<?php
// app/middlewares/AdminAuthMiddleware.php
namespace app\middlewares;

use Flight;

class AdminAuthMiddleware
{
    public function before()
    {
        $session = Flight::session();
        $timeout = Flight::get( 'SESSION_EXPIRE_TIMEOUT' ) ?? 1800; // Тайм-аут в секундах (например, 60 минут)
        $now     = time();

        // 1. Проверка авторизации
        if ( $session->get( 'is_admin' ) !== true ) {
            $session->set( 'flash_message', 'Неверный аккаунт :: middlewares' );
            Flight::redirect( '/login?error=Account+Missed' );
            exit;
        }

        // 2. Проверка активности (Session Timeout)
        $lastActivity = $session->get( 'last_activity' );

        if ( $lastActivity && ( $now - $lastActivity > $timeout + 1 ) ) {
            // Время истекло: очищаем сессию и выходим
            $session->clear();
            $session->set( 'flash_message', 'Время сессии истекло. Войдите снова.' );
            Flight::redirect( '/login?error=Session+Expired' );
            exit;
        }

        // 3. Обновляем метку времени текущей активностью
        $session->set( 'last_activity', $now );
        $session->set( 'time', date( 'Y-m-d H:i:s' ) );

        // ... внутри метода before() после обновления last_activity
        $timeLeft = $timeout - ( $now - $session->get( 'last_activity' ) );
        Flight::view()->assign( 'session_timeout_seconds', $timeLeft );
    }
}
