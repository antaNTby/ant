<?php
namespace app\controllers;

use Flight;

class AuthController
{

    public function showRegistrationForm()
    {
        Flight::render( 'register.tpl.html',
            [

                'title' => 'Регистрация',
                'year'  => date( 'Y' ),
                'error' => Flight::request()->query->error, // Получаем ошибку из URL
                'okey'  => Flight::request()->query->okey,  // Получаем ok из URL

            ] );
    }

    public function processRegistration()
    {
        $session = Flight::session();

        $username = Flight::request()->data->username;
        $email    = Flight::request()->data->email;
        $password = Flight::request()->data->password;

        $authService = Flight::auth(); // Предполагаем, что сервис зарегистрирован в Flight
        $result      = $authService->register( $username, $email, $password );

        if ( $result['success'] ) {
            // Flight::flash( 'success', $result['message'] );
            $session->set( 'flash_message', $result['message'] );
            $errorParam = isset( $result['error'] ) ? $result['error'] : 'Account+Error';
            Flight::redirect( '/login?result=' . $errorParam );
        } else {
            $session->set( 'flash_message', $result['message'] );
            Flight::render( 'register.tpl.html', [
                'error'  => $result['error'],
                'values' => ['username' => $username, 'email' => $email],
            ] );
        }
    }
}
