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
            $okey = 'All sessions terminated';
            Flight::redirect( '/login?okey=' . rawurlencode( $okey ) );
        } else {
            Flight::flash( 'warning', 'Сессия не найдена или уже завершена.' );
            $error = 'No active session found';
            Flight::redirect( '/login?error=' . rawurlencode( $error ) );
        }

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
            $error = $result['message'] ?? 'Registration Failed';
            Flight::redirect( '/register?error=' . rawurlencode( $error ) );
        }

    }

    public function showLoginFormOLD()
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

    public function showLoginForm()
    {
        // Flight автоматически делает urldecode, но для rawurldecode (RFC 3986)
        // лучше явно обработать, если в URL пришли %20
        $rawError = Flight::request()->query->error;
        $rawOkey  = Flight::request()->query->okey;

        $errorMsg = null;
        $okeyMsg  = null;

        if ( $rawError ) {
            $errorDecoded = rawurldecode( $rawError );
            $messages     = [
                'Account is Banned' => 'Ваш аккаунт заблокирован администратором.',
                'Incorrect Account' => 'Неверный логин или пароль.',
                'Login Failed'      => 'Ошибка входа. Попробуйте еще раз.',
            ];
            $errorMsg = $messages[$errorDecoded] ?? $errorDecoded;
        }

        if ( $rawOkey ) {
            $okeyDecoded = rawurldecode( $rawOkey );
            $messages    = [
                'All sessions terminated' => 'Отлично! Все другие сессии завершены.',
                'Registration Success'    => 'Регистрация прошла успешно! Войдите.',
                'Life is Good'            => 'И слава Богу!',
            ];
            $okeyMsg = $messages[$okeyDecoded] ?? $okeyDecoded;
        }

        Flight::render( 'login.tpl.html', [
            'error'         => $errorDecoded ?? '',
            'okey'          => $okeyDecoded ?? '',
            'error_message' => $errorMsg,
            'okey_message'  => $okeyMsg,
            'year'          => date( 'Y' ),
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
            $error = $result['error'] ?? 'Login Failed';
            $okey  = $result['okey'] ?? 'Life is Good';
            Flight::redirect( '/login?error=' . rawurlencode( $error ) . '&okey=' . rawurlencode( $okey ) );
        }
    }

}
