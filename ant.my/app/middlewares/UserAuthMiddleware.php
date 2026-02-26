<?php
namespace app\middlewares;

use Flight;

class UserAuthMiddleware
{
    public function before()
    {
        // Вызываем объединенный метод
        if ( !Flight::auth()->checkAccess() ) {
            Flight::flash( 'danger', 'Доступ запрещен' );
            Flight::redirect( '/login?error=Session+Expired' );
            exit;
        }

    }
}
