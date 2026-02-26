<?php
namespace app\middlewares;

use Flight;

class AdminAuthMiddleware
{
    public function before()
    {
        // Вызываем объединенный метод
        if ( !Flight::authService()->checkAccess() ) {
            Flight::flash( 'danger', 'Доступ запрещен' );
            Flight::redirect( '/login?error=Access+Denied' );
            exit;
        }

        // Проверка роли (админ или нет)
        if ( Flight::session()->get( 'user_role' ) !== 'admin' ) {
            Flight::flash( 'danger', 'Нет прав' );
            Flight::redirect( '/login?error=No+Permission' );
            exit;
        }
    }
}
