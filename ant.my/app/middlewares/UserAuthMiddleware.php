<?php
namespace app\middlewares;

use Flight;

class UserAuthMiddleware
{
    public function before()
    {
        // Вызываем объединенный метод
        if ( !Flight::authService()->checkAccess() ) {
            Flight::flash( 'danger', 'Доступ запрещен' );
            Flight::redirect( '/login?error=Access Denied' );
            exit;
        }

    }
}
