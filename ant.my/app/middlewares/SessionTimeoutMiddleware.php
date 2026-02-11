<?php
use flight\Session;

class SessionTimeoutMiddleware
{
    public function before()
    {
        $session = Flight::session();
        $now     = time();
        $timeout = 7200; // 2 часа

        if ( $session->has( 'user_id' ) ) {
            $lastActivity = $session->get( 'last_activity' );

            if ( $lastActivity && ( $now - $lastActivity > $timeout ) ) {
                // Устанавливаем flash-сообщение ПЕРЕД уничтожением основной сессии
                // Библиотека сама удалит его после первого же отображения
                $session->flash( 'error', 'Время сессии истекло. Пожалуйста, войдите снова.' );

                $session->destroy();
                Flight::redirect( '/login' );

                return false;
            }

            $session->set( 'last_activity', $now );
        }
    }
}
