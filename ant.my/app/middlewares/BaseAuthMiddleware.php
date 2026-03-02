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
            Flight::redirect( '/login?error=Access%20Denied' );
            exit;
        }

        // Специфичная проверка роли
        if ( !$this->checkRole() ) {
            Flight::flash( 'danger', 'Недостаточно прав' );
            Flight::redirect( '/login?error=No%20Permission' );
            exit;
        }
    }
}
