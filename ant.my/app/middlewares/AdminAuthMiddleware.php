<?php
namespace app\middlewares;

use Flight;

class AdminAuthMiddleware extends BaseAuthMiddleware
{
    public function checkRole(): bool
    {
        return Flight::session()->get( 'user_role' ) === 'administrator';

    }
}
