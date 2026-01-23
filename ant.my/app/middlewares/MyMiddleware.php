<?php
declare ( strict_types = 1 );

namespace app\middlewares;

class MyMiddleware
{
    public function before( $params )
    {
        echo 'Middleware first!';
    }

    public function after( $params )
    {
        echo 'Middleware last!';
    }
}
