<?php
namespace app\middlewares;

use Flight;

class UserAuthMiddleware extends BaseAuthMiddleware
{
    public function checkRole(): bool
    {
        return true; // Любой зарегистрированный пользователь проходит

    }
}
