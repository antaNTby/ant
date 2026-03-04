<?php
namespace app\middlewares;

use Flight;

abstract class BaseAuthMiddleware
{
    abstract public function checkRole(): bool;

    public function before()
    {
        // Проверка общего доступа
        if ( !Flight::authService()->checkAccess() ) {
            Flight::flash( 'danger', 'Доступ запрещен' );
            $error = 'Access Denied'; // Сообщение ошибки
            Flight::redirect( '/login?error=' . rawurlencode( $error ) );
            exit;
        }

        // Специфичная проверка роли
        if ( !$this->checkRole() ) {
            Flight::flash( 'danger', 'Недостаточно прав' );
            $error = 'No Permission'; // Сообщение ошибки
            Flight::redirect( '/login?error=' . rawurlencode( $error ) );
            exit;
        }
    }
}
