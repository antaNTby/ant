<?php
declare ( strict_types = 1 ); // Для строгости типов данных

namespace app\controllers;

use Flight;

class AuthController
{
    /**
     * Отображение списка активных сессий пользователя.
     *
     * @return void
     */
    public function showSessions(): void
    {
        $sessions = Flight::authService()->getUserSessions();

        $subData = [
            'subName'  => 'new sessions',
            'title'    => 'Мои устройства',
            'sessions' => $sessions,
        ];

        // Flight::render( 'admin/dpt/subs/sessions.tpl.html', ['subData' => $subData] );
        Flight::Display( 'admin/dpt/subs/sessions.tpl.html', ['subData' => $subData] );
    }

    /**
     * Удаление просроченных токенов.
     *
     * @return void
     */
    public function handleDeleteExpiredTokens(): void
    {
        Flight::authService()->deleteExpiredTokens();
        Flight::flash( 'light', 'Удалены все истекшие токены' );
        Flight::redirect( '/admin/dashboard' );
    }

    /**
     * Выполнение выхода текущего пользователя.
     *
     * @return void
     */
    public function handleLogout(): void
    {
        Flight::authService()->attemptLogout(); // Очистка токена

        Flight::flash( 'success', 'Вы успешно вышли из системы' );
        Flight::redirect( '/login' ); // Переадресация на страницу входа
    }

    /**
     * Выполняет выход со всех устройств.
     *
     * @return void
     */
    public function handleLogoutEverywhere(): void
    {
        $success = Flight::authService()->logoutEverywhere();

        if ( $success ) {
            Flight::flash( 'success', 'Вы успешно вышли со всех устройств.' );
            $okey = 'All sessions terminated'; // Сообщение успеха
            Flight::redirect( '/login?okey=' . rawurlencode( $okey ) );
        } else {
            Flight::flash( 'warning', 'Сессия не найдена или уже завершена.' );
            $error = 'No active session found'; // Сообщение ошибки
            Flight::redirect( '/login?error=' . rawurlencode( $error ) );
        }
    }

    /**
     * Показ формы регистрации.
     *
     * @return void
     */
    public function showRegistrationForm(): void
    {
        Flight::Display( 'register.tpl.html' );
    }

    /**
     * Обработчик отправки формы регистрации.
     *
     * @return void
     */
    public function handleRegistrationForm(): void
    {
        $data = Flight::request()->data;

        $username         = $data->username;
        $email            = $data->email;
        $password         = $data->password;
        $password_confirm = $data->password_confirm;

        $result = Flight::authService()->registerUser( $username, $email, $password, $password_confirm );

        if ( $result['success'] ) {
            Flight::flash( 'success', 'Добро пожаловать! Регистрация прошла успешно.' );
            Flight::redirect( '/' );
        } else {
            $error = $result['message'] ?? 'Registration Failed';
            Flight::redirect( '/register?error=' . rawurlencode( $error ) );
        }
    }

    /**
     * Показ формы входа.
     *
     * @return void
     */
    public function showLoginForm(): void
    {

        Flight::Display( 'login.tpl.html' );
    }

    /**
     * Обработчик формы входа.
     *
     * @return void
     */
    public function handleLoginForm(): void
    {
        $request = Flight::request();
        $session = Flight::session();

        $result = Flight::authService()->attemptLogin(
            $request->data->username,
            $request->data->password,
            isset( $request->data->remember_me )
        );

        if ( $result['success'] ) {
            Flight::flash( 'success', 'Добро пожаловать! ' . $result['username'] );
            $url = ( $result['role'] === 'admin' )
            ? '/admin/settings'
            : '/b2b/welcome';
            Flight::redirect( $url );
        } else {
            Flight::flash( 'danger', 'Вход не удался' );
            $error = $result['error'] ?? 'Login Failed';
            $okey  = $result['okey'] ?? 'Life is Good';
            // Flight::redirect( '/login?error=' . rawurlencode( $error ) . '&okey=' . rawurlencode( $okey ) );
            Flight::redirect( '/login?error=' . rawurlencode( $error ) );
        }
    }
}
