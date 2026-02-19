<?php
// app/middlewares/AdminAuthMiddleware.php
namespace app\middlewares;

use Flight;
use Tracy\Debugger;

class AdminAuthMiddleware
{
    public function before()
    {
        // 0. Отключаем Tracy Debug Bar для API-запросов (пинга), чтобы не портить JSON В Middleware лучше проверять так (чтобы поймать любой вариант пути):
        if ( strpos( Flight::request()->url, '/api/admin/ping' ) !== false && class_exists( '\Tracy\Debugger' ) ) {
            \Tracy\Debugger::$showBar = false;
        }

        $session = Flight::session();
        $timeout = Flight::get( 'SESSION_EXPIRE_TIMEOUT' ) ?? 2 * 60 * 60; // Тайм-аут в секундах (например, 30 минут)
        $now     = time();

        // 1. Проверка авторизации
        if ( $session->get( 'is_admin' ) !== true ) {
            $session->set( 'flash_message', 'Неверный аккаунт' );
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

        /**
         * АВТОМАТИЧЕСКАЯ РЕГЕНЕРАЦИЯ
         * true — удаляет старый файл сессии на сервере
         */
        // Регенерируем только если это НЕ AJAX запрос
        if ( !Flight::request()->ajax ) {
            $session->regenerate( true );
        }

        // 3. Обновляем метку времени текущей активностью
        $session->set( 'last_activity', $now );
        $session->set( 'time', date( 'Y-m-d H:i:s' ) );
/*
        // ... внутри метода before() после обновления last_activity
        $timeLeft = $timeout - ( $now - $session->get( 'last_activity' ) );
        Flight::view()->assign( 'session_timeout_seconds', $timeLeft );
*/
        // Передаем остаток времени в Smarty для JS-таймера
        $timeLeft = $timeout;
        Flight::view()->assign( 'session_timeout_seconds', $timeLeft );

    }
}
