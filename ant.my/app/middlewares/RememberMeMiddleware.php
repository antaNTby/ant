<?php
namespace app\middlewares;

use Flight;

class RememberMeMiddleware
{
    public function before()
    {
        $session = Flight::session();
        $db      = Flight::db();
        $request = Flight::request();

        // 1. Пытаемся восстановить сессию, только если она пуста
        if ( !$session->get( 'user_id' ) && $rawToken = Flight::cookie()->get( 'remember_token' ) ) {

            $tokenHash        = hash( 'sha256', $rawToken );
            $currentUserAgent = $request->user_agent;

            // 2. Ищем токен с проверкой User-Agent
            $tokenData = $db->fetchRow(
                'SELECT id, user_id FROM user_tokens
                 WHERE token_hash = ? AND user_agent = ? AND expires_at > NOW()',
                [$tokenHash, $currentUserAgent]
            );

            if ( $tokenData ) {
                $user = $db->fetchRow( 'SELECT * FROM users WHERE id = ?', [$tokenData['user_id']] );

                // Проверяем, не забанен ли юзер (is_active)
                if ( $user && $user['is_active'] ) {

                    // --- РОТАЦИЯ ---
                    $db->runQuery( 'DELETE FROM user_tokens WHERE id = ?', [$tokenData['id']] );

                    $newRawToken   = bin2hex( random_bytes( 32 ) );
                    $newTokenHash  = hash( 'sha256', $newRawToken );
                    $expireSeconds = Flight::get( 'TOKEN_EXPIRE_TIMEOUT' );

                    $db->runQuery(
                        'INSERT INTO user_tokens (user_id, token_hash, user_agent, created_ip, expires_at, last_used_at)
                         VALUES (?, ?, ?, ?, ?, NOW())',
                        [
                            $user['id'],
                            $newTokenHash,
                            $currentUserAgent,
                            $request->ip,
                            date( 'Y-m-d H:i:s', time() + $expireSeconds ),
                        ]
                    );

                    // Получаем ID только что созданного токена (через SimplePDO/PDO)
                    $newTokenId = $db->getConnection()->lastInsertId();

                    // Обновляем куку
                    Flight::cookie()->set( 'remember_token', $newRawToken, $expireSeconds, '/', '', false, true );

                    // --- СЕССИЯ ---
                    $session->regenerate( true );
                    $session->set( 'user_id', $user['id'] );
                    $session->set( 'user_name', $user['username'] );
                    $session->set( 'user_role', $user['role'] );
                    $session->set( 'is_admin', ( $user['role'] === 'admin' ) );
                    $session->set( 'last_activity', time() );

                    // ВАЖНО: сохраняем ID токена для AdminAuthMiddleware->checkAccess()
                    $session->set( 'current_token_id', $newTokenId );

                    $db->runQuery( 'UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']] );
                }
            } else {
                // Чистим невалидную куку
                Flight::cookie()->set( 'remember_token', '', -3600, '/', '', false, true );
            }
        }

        // Данные для Smarty
        if ( $session->get( 'user_id' ) ) {
            Flight::view()->assign( 'user', [
                'name' => $session->get( 'user_name' ),
                'role' => $session->get( 'user_role' ),
            ] );
        }
    }
}
