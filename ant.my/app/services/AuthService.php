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
        $rememberMe = false
    ) {
        $db      = Flight::db();
        $session = Flight::session();
        $request = Flight::request();

        // 1. Поиск пользователя
        $user = $db->fetchRow( 'SELECT * FROM users WHERE username = ?', [$username] );

        // 2. Валидация
        if ( !$user || !password_verify( $password, $user['password_hash'] ) ) {
            return ['success' => false, 'message' => 'Неверный логин или пароль'];
        }

        if ( !$user['is_active'] ) {
            return ['success' => false, 'message' => 'Ваш аккаунт заблокирован', 'error' => 'Banned'];
        }

        // 3. Создание сессии
        $session->regenerate( true );
        $session->set( 'user_id', $user['id'] );
        $session->set( 'user_name', $user['username'] );
        $session->set( 'user_role', $user['role'] );
        $session->set( 'is_admin', ( $user['role'] === 'admin' ) );
        $session->set( 'last_activity', time() );

        // 4. Логика Remember Me
        if ( $rememberMe ) {
            $expireSeconds = 2592000;
            $rawToken      = bin2hex( random_bytes( 32 ) );
            $tokenHash     = hash( 'sha256', $rawToken );

            $db->runQuery(
                'INSERT INTO user_tokens (user_id, token_hash, user_agent, created_ip, expires_at)
                 VALUES (?, ?, ?, ?, ?)',
                [$user['id'], $tokenHash, $request->user_agent, $request->ip, date( 'Y-m-d H:i:s', time() + $expireSeconds )]
            );

            $session->set( 'current_token_id', $db->lastInsertId() );
            Flight::cookie()->set( 'remember_token', $rawToken, $expireSeconds, '/', '', false, true );
        }

        // 5. Финализация
        $db->runQuery( 'UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']] );

        return ['success' => true, 'role' => $user['role'], 'username' => $user['username']];
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
        if ( preg_match( '/windows nt 10/i', $ua ) ) {
            $os = 'Windows 10/11';
        } elseif ( preg_match( '/android/i', $ua ) ) {
            $os = 'Android';
        } elseif ( preg_match( '/iphone|ipad/i', $ua ) ) {
            $os = 'iOS';
        } elseif ( preg_match( '/linux/i', $ua ) ) {
            $os = 'Linux';
        } elseif ( preg_match( '/macintosh|mac os x/i', $ua ) ) {
            $os = 'macOS';
        }

        // Определяем Браузер
        if ( preg_match( '/chrome/i', $ua ) && !preg_match( '/edge|opr|opera/i', $ua ) ) {
            $browser = 'Chrome';
        } elseif ( preg_match( '/firefox/i', $ua ) ) {
            $browser = 'Firefox';
        } elseif ( preg_match( '/safari/i', $ua ) && !preg_match( '/chrome/i', $ua ) ) {
            $browser = 'Safari';
        } elseif ( preg_match( '/edge|edg/i', $ua ) ) {
            $browser = 'Edge';
        } elseif ( preg_match( '/opera|opr/i', $ua ) ) {
            $browser = 'Opera';
        }

        return "$os, $browser";
    }

}
