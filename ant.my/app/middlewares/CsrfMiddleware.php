<?php
namespace app\middlewares;

// app/middlewares/CsrfMiddleware.php
use Flight;

class CsrfMiddleware
{
    public function before( array $params ): void
    {
        $request = Flight::request();
        $session = Flight::session();

        if ( $request->method == 'POST' ) {
            $token        = $request->data->csrf_token;
            $sessionToken = $session->get( 'csrf_token' );
            Flight::flash( 'dark', 'Check CSRFtoken' );
            if ( !$token || !hash_equals( $sessionToken, $token ) ) {
                Flight::halt( 403, 'Invalid CSRFtoken' );
            }
        }
    }
}
