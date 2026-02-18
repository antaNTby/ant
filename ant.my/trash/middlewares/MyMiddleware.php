<?php
use flight\Session;

class AuthMiddleware
{
    protected $publicRoutes;

    public function __construct( array $publicRoutes = [] )
    {
        $this->publicRoutes = $publicRoutes;
    }

    public function handle()
    {
        $path    = Flight::request()->url;
        $session = Flight::session();

        $isAuthorized = $session->get( 'user_id' );

        if ( !$isAuthorized && !in_array( $path, $this->publicRoutes ) ) {
            Flight::redirect( '/login' );
            exit;
        }
    }
}

// Инициализация приложения
$app = Flight::app();
$app->register( 'session', Session::class );

// Подключаем middleware
$authMiddleware = new AuthMiddleware( [
    '/login',
    '/register',
    '/logout',
] );

Flight::before( 'start', [$authMiddleware, 'handle'] );

// Маршрут логина
Flight::route( '/login', function () {
    $session = Flight::session();
    // Здесь логика аутентификации
    $session->set( 'user_id', 123 );
    $session->set( 'username', 'johndoe' );
    Flight::redirect( '/dashboard' );
} );

// Маршрут выхода
Flight::route( '/logout', function () {
    $session = Flight::session();
    $session->clear();
    Flight::redirect( '/login' );
} );

// Защищённый маршрут
Flight::route( '/dashboard', function () {
    $session = Flight::session();
    echo 'Добро пожаловать, ' . $session->get( 'username' );
} );

Flight::start();
