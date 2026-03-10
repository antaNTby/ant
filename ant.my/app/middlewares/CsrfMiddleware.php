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
        Flight::flash( 'dark', 'Check CSRFtoken' );

        if ( $request->method == 'POST' ) {
            $token = $request->data->csrf_token;
            if ( $token !== $session->get( 'csrf_token' ) ) {
                Flight::halt( 403, 'Invalid CSRFtoken' );
            }
        }
    }
}
