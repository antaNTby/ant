<?php
use flight\Engine;
use flight\Session;

class LoggedInMiddleware
{

    protected Engine $app;

    public function __construct( Engine $app )
    {
        $this->app = $app;
    }

    public function before( array $params )
    {
        $session = $this->app->session();
        if ( $session->get( 'logged_in' ) !== true ) {
            dd( $session );
            $this->app->redirect( '/login' );
            exit;
        }
    }
}
