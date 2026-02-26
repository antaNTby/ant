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

        // dd( $data );

        $username         = Flight::request()->data->username;
        $email            = Flight::request()->data->email;
        $password         = Flight::request()->data->password;
        $password_confirm = Flight::request()->data->password_confirm;

        // Предполагаем, что сервис зарегистрирован в Flight
        $result = Flight::auth()->register( $username, $email, $password, $password_confirm );

        if ( $result['success'] ) {

            Flight::flash( 'success', 'Добро пожаловать! Регистрация прошла успешно.' );
            Flight::redirect( '/' );
        } else {
            Flight::flash( 'danger', $result['message'] );
            Flight::render( 'register.tpl.html', ['old' => $data->getData()] );
        }

    }
}
