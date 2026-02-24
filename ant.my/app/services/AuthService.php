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

    public function logout()
    {
        $session  = Flight::session();
        $rawToken = Flight::cookie()->get( 'remember_token' );

        if ( $rawToken ) {
            $tokenHash = hash( 'sha256', $rawToken );
            Flight::db()->runQuery( 'DELETE FROM user_tokens WHERE token_hash = ?', [$tokenHash] );
            Flight::cookie()->set( 'remember_token', '', -3600, '/', '', false, true );
        }

        $session->clear();
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
