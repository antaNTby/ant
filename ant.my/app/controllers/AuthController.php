<?php
namespace app\controllers;

use Flight;

class AuthController
{

    public function handleDeleteExpiredTokens()
    {

        Flight::authService()->deleteExpiredTokens();
        Flight::flash( 'light', 'Удалены все истекшие токены' );
        Flight::redirect( '/admin/dashboard' );
    }

    public function handleLogout()
    {
        // Вызываем централизованный метод выхода
        Flight::authService()->clearToken();
        Flight::authService()->clearSession();

        // Устанавливаем сообщение и уходим на логин
        // Flight::session()->set( 'session_message', 'Вы успешно вышли из системы' );
        Flight::flash( 'success', 'Вы успешно вышли из системы' );
        Flight::redirect( '/login' );
    }

    /**
     * Обработка выхода со всех устройств
     */
    public function handleLogoutEverywhere()
    {
        // Вызываем метод сервиса
        $success = Flight::authService()->logoutEverywhere();

        if ( $success ) {
            Flight::flash( 'success', 'Вы успешно вышли со всех устройств.' );
        } else {
            Flight::flash( 'warning', 'Сессия не найдена или уже завершена.' );
        }

        // После логаута всегда на вход
        Flight::redirect( '/login?okey=All+sessions+terminated' );
    }

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

    public function handleRegistrationForm()
    {
        $session = Flight::session();

        $data = Flight::request()->data;

        // dd( $data );

        $username         = Flight::request()->data->username;
        $email            = Flight::request()->data->email;
        $password         = Flight::request()->data->password;
        $password_confirm = Flight::request()->data->password_confirm;

        // Предполагаем, что сервис зарегистрирован в Flight
        $result = $this->registerUser( $username, $email, $password, $password_confirm );

        if ( $result['success'] ) {

            Flight::flash( 'success', 'Добро пожаловать! Регистрация прошла успешно.' );
            Flight::redirect( '/' );
        } else {
            Flight::flash( 'danger', $result['message'] );
            Flight::render( 'register.tpl.html', ['old' => $data->getData()] );
        }

    }

    public function showLoginForm()
    {
        $session = Flight::session();
        // Если уже залогинен — кидаем в админку
        if ( $session->get( 'user_role' ) === 'admin' ) {
            Flight::flash( 'success', 'Полный доступ' );
        } else {
            Flight::flash( 'warning', 'Клиентский доступ' );
        }

        Flight::render( 'login.tpl.html', [
            'year'  => date( 'Y' ),
            'error' => Flight::request()->query->error, // Получаем ошибку из URL
            'okey'  => Flight::request()->query->okey,  // Получаем ok из URL
        ] );
    }

    public function handleLoginForm()
    {
        $request = Flight::request();
        $session = Flight::session();

        // Вызываем сервис
        $result = Flight::authService()->attemptLogin(
            $request->data->username,
            $request->data->password,
            isset( $request->data->remember_me )
        );

        if ( $result['success'] ) {
            // $session->set( 'session_message', 'Добро пожаловать, ' . $result['username'] );
            Flight::flash( 'success', 'Добро пожаловать! ' . $result['username'] );
            // Редирект по роли
            $url = ( $result['role'] === 'admin' ) ? '/admin/settings' : '/b2b/wellcome';
            Flight::redirect( $url );
        } else {
            // $session->set( 'session_message', $result['message'] );
            Flight::flash( 'danger', 'Вход не удался' );
            $queryParam = isset( $result['error'] ) ? $result['error'] : 'Account+Error';
            Flight::redirect( '/login?error=' . $queryParam );
        }
    }

}
