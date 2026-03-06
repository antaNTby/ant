<?php
declare ( strict_types = 1 ); // Для строгости типов данных

namespace app\controllers;

use Flight;

class AuthController
{

    /**
     * Удаление просроченных токенов.
     *
     * @return void
     */
    public function handleDeleteExpiredTokens(): void
    {
        Flight::flash( 'success', 'Удалены все истекшие токены' );
        Flight::authService()->deleteExpiredTokens();
        $okey = 'Stale tokens cleared'; // Сообщение ошибки
        Flight::redirect( '/admin/dpt/subs/sessions' );
    }

    public function handleSessionsRevoke(): void
    {
        $db      = Flight::db();
        $session = Flight::session();

        // Получаем ID токена из скрытого поля формы
        $tokenId = Flight::request()->data->token_id;
        $userId  = $session->get( 'user_id' );

        if ( $tokenId && $userId ) {
            // Удаляем токен, проверяя, что он принадлежит именно этому пользователю (безопасность!)
            // $db->runQuery(
            //     'DELETE FROM user_tokens WHERE id = ? AND user_id = ?',
            //     [$tokenId, $userId]
            // );

            $deleted = Flight::db()->delete( 'user_tokens', 'id = ? AND user_id = ?', [$tokenId, $userId] );
            // Если пользователь удалил текущую сессию (токен которой в куке)
            $currentRawToken = Flight::cookie()->get( 'remember_token' );

            if ( $currentRawToken ) {
                $currentTokenHash = hash( 'sha256', $currentRawToken );
                $isCurrent        = $db->fetchRow(
                    'SELECT id FROM user_tokens WHERE id = ? AND token_hash = ?',
                    [$tokenId, $currentTokenHash]
                );

                // Если удаляемый ID совпадает с текущим токеном в куке — чистим куку
                if ( $isCurrent ) {
                    Flight::cookie()->set( 'remember_token', '', -3600, '/', '', false, true );
                }
            }

            Flight::flash( 'warning', $deleted . ' токен(ов) удалены для пользователя :: ' . $session->get( 'user_name' ) );
            Flight::flash( 'warning', 'Доступ для браузера "' . Flight::request()->data->device_info . '" отозван' );
        }

        Flight::redirect( '/admin/dpt/subs/sessions' );
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
        $session = Flight::session();

        Flight::Display( 'register.tpl.html', ['old' => $session->get( 'old' )] );
    }

    /**
     * Обработчик отправки формы регистрации.
     *
     * @return void
     */
    public function handleRegistrationForm(): void
    {
        $data    = Flight::request()->data;
        $session = Flight::session();

        $username         = $data->username;
        $email            = $data->email;
        $password         = $data->password;
        $password_confirm = $data->password_confirm;

        $session->set( 'old',
            [
                'username' => $username,
                'email'    => $email,
            ] );

        $result = Flight::authService()->registerUser( $username, $email, $password, $password_confirm );

        if ( $result['success'] ) {
            $url = ( $result['role'] === 'administrator' )
            ? '/admin/dashboard'
            : '/b2b/welcome';
            Flight::flash( 'success', $result['message'] );
            Flight::redirect( $url );
        } else {
            $error = $result['error'] ?? 'Registration Failed';
            Flight::flash( 'danger', $result['message'] ?? 'Registration Failed' );
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
        // $result = Flight::authService()->attemptLogout();
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

        $result = Flight::authService()->attemptLogin(
            $request->data->username,
            $request->data->password,
            isset( $request->data->remember_me )
        );

        if ( $result['success'] ) {
            $url = ( $result['role'] === 'administrator' )
            ? '/admin/dashboard'
            : '/b2b/welcome';
            Flight::flash( 'success', $result['message'] );
            Flight::redirect( $url );
        } else {
            $error = $result['error'] ?? 'Login Failed';
            // dumpe( $result );
            Flight::flash( 'danger', $result['message'] ?? 'Login Failed' );
            Flight::redirect( '/login?error=' . rawurlencode( $error ) );
        }
    }
}
