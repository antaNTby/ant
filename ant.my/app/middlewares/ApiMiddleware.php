<?php
namespace app\middlewares;

use Tracy\Debugger;

class ApiMiddleware
{
    public function before()
    {
        // Если класс Tracy существует, вырубаем бар для всех API-запросов
        if ( class_exists( Debugger::class ) ) {
            Debugger::$showBar = false;
        }
    }
}
