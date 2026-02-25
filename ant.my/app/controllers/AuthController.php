<?php
namespace app\controllers;

use Flight;

class AuthController
{

    public function showRegistrationForm()
    {
        Flight::render( 'register.tpl.html',
            [

                'query_error' => Flight::request()->query->error, // Получаем ошибку из URL
                'query_okey'  => Flight::request()->query->okey,  // Получаем ok из URL
                'title'       => 'Регистрация',
                'year'        => date( 'Y' ),

            ] );
    }

    public function processRegistration()
    {
        $session = Flight::session();

        $data = Flight::request()->data;

        $username = Flight::request()->data->username;
        $email    = Flight::request()->data->email;
        $password = Flight::request()->data->password;

        $authService = Flight::auth(); // Предполагаем, что сервис зарегистрирован в Flight
        $result      = $authService->register( $username, $email, $password );

        if ( $result['success'] ) {
            // Flight::flash( 'success', $result['message'] );
            $session->set( 'flash_message', $result['message'] );
            $queryParam = isset( $result['okey'] ) ? $result['okey'] : 'Account+OK';
            Flight::redirect( '/login?okey=' . $queryParam );

        } else {
            $session->set( 'flash_message', $result['message'] );

            Flight::render( 'register.tpl.html', [
                'error'   => $result['error'],
                'message' => $result['message'],
                'old'     => $data->getData(), // Извлекаем массив из коллекции Flight
                'values'  => ['username' => $username, 'email' => $email],
            ] );
        }
    }
}
