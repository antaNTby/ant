<?php
namespace app\services;

use Flight;

class AuthService
{

    public function checkAccess()
    {
        $session = Flight::session();
        $db      = Flight::db();
        $now     = time();

        // 1. Базовая проверка сессии
        $userId = $session->get( 'user_id' );
        if ( !$userId ) {
            return false;
        }

        // 2. Проверка "Отзыва доступа" (Revoke)
        $currentTokenId = $session->get( 'current_token_id' );
        if ( $currentTokenId ) {
            // Быстрая проверка: существует ли еще этот токен в базе?
            $tokenExists = $db->fetchRow(
                'SELECT id FROM user_tokens WHERE id = ? AND expires_at > NOW()',
                [$currentTokenId]
            );

            if ( !$tokenExists ) {
                $this->logout(); // Метод очистки (опишем ниже)

                return false;
            }
        }

        // 3. Проверка бана (опционально, но полезно)
        $user = $db->fetchRow( 'SELECT is_active FROM users WHERE id = ?', [$userId] );
        if ( !$user || !$user['is_active'] ) {
            $this->logout();

            return false;
        }

        // 4. Обновление активности
        $session->set( 'last_activity', $now );

        return true;
    }

    public function attemptLogin(
        $username,
        $password,
        $rememberMe = true
    ) {
        $db      = Flight::db();
        $session = Flight::session();
        $request = Flight::request();

        // 1. Поиск пользователя
        $user = $db->fetchRow( 'SELECT * FROM users WHERE username = ?', [$username] );

        // 2. Валидация
        if ( !$user || !password_verify( $password, $user['password_hash'] ) ) {
            Flight::flash( 'dark', 'Неверный логин или пароль' );

            return ['success' => false, 'error' => 'Incorrect+Account', 'message' => 'Неверный логин или пароль'];
        }

        if ( !$user['is_active'] ) {
            Flight::flash( 'dark', 'Ваш аккаунт заблокирован' );

            return ['success' => false, 'error' => 'Account+is+Banned', 'message' => 'Ваш аккаунт заблокирован'];
        }

        // 3. Создание сессии
        $this->createInternalSession( $user );
        $this->setLastLogin( $user );

        // 4. Логика Remember Me
        if ( $rememberMe ) {
            $expireSeconds = Flight::get( 'TOKEN_EXPIRE_TIMEOUT' );
            $rawToken      = bin2hex( random_bytes( 32 ) );
            $tokenHash     = hash( 'sha256', $rawToken );

            $db->runQuery(
                'INSERT INTO user_tokens (user_id, token_hash, user_agent, created_ip, expires_at)
                 VALUES (?, ?, ?, ?, ?)',
                [$user['id'], $tokenHash, $request->user_agent, $request->ip, date( 'Y-m-d H:i:s', time() + $expireSeconds )]
            );

            $session->set( 'current_token_id', $db->lastInsertId() );
            Flight::cookie()->set( 'remember_token', $rawToken, $expireSeconds, '/', '', false, true );
        } else {

            // dd( [$user['id'], $user['user_agent'], $request->user_agent] );

            // удалим токен и куку если просили не хранить
            $db->runQuery( 'DELETE FROM user_tokens WHERE user_id = ? AND user_agent = ?', [$user['id'], $request->user_agent] );
            // Удаляем куку (используем те же параметры, что при создании)
            Flight::cookie()->set(
                'remember_token',
                '',
                -3600,
                '/',
                '',
                false, // поставьте true, если используете HTTPS
                true
            );
        }

        // 5. Финализация
        Flight::flash( 'light', 'Удачный вход' );

        return ['success' => true, 'okey' => 'last_login updated', 'message' => 'ok', 'role' => $user['role'], 'username' => $user['username']];
    }

    /**
     * Универсальный выход из системы
     */
    public function logout()
    {
        $db      = Flight::db();
        $session = Flight::session();

        // 1. Работа с Remember Me токеном
        $rawToken = Flight::cookie()->get( 'remember_token' );
        if ( $rawToken ) {
            $tokenHash = hash( 'sha256', $rawToken );
            $db->runQuery( 'DELETE FROM user_tokens WHERE token_hash = ?', [$tokenHash] );

            // Удаляем куку (используем те же параметры, что при создании)
            Flight::cookie()->set(
                'remember_token',
                '',
                -3600,
                '/',
                '',
                false, // поставьте true, если используете HTTPS
                true
            );
        }

        // 2. Очистка данных сессии
        // Используем clear(), чтобы сохранить объект сессии для flash_message
        $session->clear();

        return true;
    }

    public function parseUserAgent( $ua )
    {
        if ( empty( $ua ) ) {
            return 'Unknown Device';
        }

        $os      = 'Unknown OS';
        $browser = 'Unknown Browser';

        // Определяем ОС
        if ( preg_match( ' / windows nt10 / i', $ua ) ) {
            $os = 'Windows10 / 11';
        } elseif ( preg_match( ' / android / i', $ua ) ) {
            $os = 'Android';
        } elseif ( preg_match( ' / iphone | ipad / i', $ua ) ) {
            $os = 'iOS';
        } elseif ( preg_match( ' / linux / i', $ua ) ) {
            $os = 'Linux';
        } elseif ( preg_match( ' / macintosh | mac osx / i', $ua ) ) {
            $os = 'macOS';
        }

        // Определяем Браузер
        if ( preg_match( ' / chrome / i', $ua ) && !preg_match( ' / edge | opr | opera / i', $ua ) ) {
            $browser = 'Chrome';
        } elseif ( preg_match( ' / firefox / i', $ua ) ) {
            $browser = 'Firefox';
        } elseif ( preg_match( ' / safari / i', $ua ) && !preg_match( ' / chrome / i', $ua ) ) {
            $browser = 'Safari';
        } elseif ( preg_match( ' / edge | edg / i', $ua ) ) {
            $browser = 'Edge';
        } elseif ( preg_match( ' / opera | opr / i', $ua ) ) {
            $browser = 'Opera';
        }

        return "$os, $browser";
    }

    public function createInternalSession( $user )
    {
        $session = Flight::session();

        $session->regenerate( true );
        $session->set( 'user_id', $user['id'] );
        $session->set( 'user_name', $user['username'] );
        $session->set( 'user_role', $user['role'] );
        $session->set( 'is_admin', ( $user['role'] === 'admin' ) );
        $session->set( 'last_activity', time() );

        // Flight::db()->runQuery( 'UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']] );
    }
    public function setLastLogin( $user )
    {
        $session = Flight::session();
        Flight::db()->runQuery( 'UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']] );
    }

}
