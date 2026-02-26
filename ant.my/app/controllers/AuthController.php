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
        $result = $this->registrateNewUser( $username, $email, $password, $password_confirm );

        if ( $result['success'] ) {

            Flight::flash( 'success', 'Добро пожаловать! Регистрация прошла успешно.' );
            Flight::redirect( '/' );
        } else {
            Flight::flash( 'danger', $result['message'] );
            Flight::render( 'register.tpl.html', ['old' => $data->getData()] );
        }

    }

    private function registrateNewUser(
        string $username,
        string $email,
        string $password,
        string $password_confirm
    ) {
        $db = Flight::db();

        // 1. Проверяем, не занят ли логин или email
        $exists = $db->fetchRow(
            'SELECT id FROM users WHERE username = ? OR email = ?',
            [$username, $email]
        );

        if ( $exists ) {
            Flight::flash( 'dark', 'Пользователь с таким логином или email уже существуе' );

            return ['success' => false, 'error' => 'Incorrect Login', 'message' => 'Пользователь с таким логином или email уже существует'];
        }

        if ( $password !== $password_confirm ) {
            Flight::flash( 'dark', 'Пароли не совпадают' );

            return ['success' => false, 'error' => 'Password is not confirmed', 'message' => 'Пароли не совпадают'];
        }

        // 2. Хешируем пароль
        $passwordHash = password_hash( $password, PASSWORD_DEFAULT );

        // 3. Сохраняем в базу

        try {
            $db->runQuery(
                'INSERT INTO users (username, email, password_hash, role, is_active, created_at)
             VALUES (?, ?, ?, ?, 1, NOW())',
                [$username, $email, $passwordHash, 'user']
            );

            // Получаем ID только что созданного пользователя
            $userId = $db->lastInsertId();

            // Загружаем данные пользователя для сессии
            $user = $db->fetchRow( 'SELECT * FROM users WHERE id = ?', [$userId] );

            // ВХОДИМ АВТОМАТИЧЕСКИ
            Flight::authService()->createInternalSession( $user );
            Flight::authService()->setLastLogin( $user );

            Flight::flash( 'light', 'Вы вошли как "' . $username . '" / ' . $email );

            return ['success' => true, 'message' => 'Вы вошли как "' . $username . '" / ' . $email];
        } catch ( \Exception $e ) {
            Flight::flash( 'dark', 'Ошибка базы данных' );

            return ['success' => false, 'error' => 'DB error', 'message' => 'Ошибка базы данных'];
        }

    }

}
