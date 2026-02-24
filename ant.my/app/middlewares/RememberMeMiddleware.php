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

        // 1. Если сессии нет, но есть кука
        if ( !$session->get( 'user_id' ) && $rawToken = Flight::cookie()->get( 'remember_token' ) ) {

            $tokenHash        = hash( 'sha256', $rawToken );
            $currentUserAgent = $request->user_agent;

            // 2. Ищем токен с ПРОВЕРКОЙ браузера (User-Agent)
            $tokenData = $db->fetchRow(
                'SELECT id, user_id FROM user_tokens
                 WHERE token_hash = ? AND user_agent = ? AND expires_at > NOW()',
                [$tokenHash, $currentUserAgent]
            );

            if ( $tokenData ) {
                // 3. Токен валиден — получаем юзера
                $user = $db->fetchRow( 'SELECT * FROM users WHERE id = ?', [$tokenData['user_id']] );

                if ( $user ) {
                    // --- МЕХАНИЗМ РОТАЦИИ (БЕЗОПАСНОСТЬ) ---
                    // Удаляем старый использованный токен из БД
                    $db->runQuery( 'DELETE FROM user_tokens WHERE id = ?', [$tokenData['id']] );

                    // Генерируем НОВЫЙ токен для следующего раза
                    $newRawToken   = bin2hex( random_bytes( 32 ) );
                    $newTokenHash  = hash( 'sha256', $newRawToken );
                    $expireSeconds = 2592000; // 30 дней

                    // Записываем новый токен в БД
// Внутри RememberMeMiddleware, после проверки $tokenData
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

                    // Обновляем куку в браузере на новую
                    Flight::cookie()->set( 'remember_token', $newRawToken, $expireSeconds, '/', '', false, true );
                    // ---------------------------------------

                    // 4. Восстанавливаем сессию
                    $session->regenerate( true );
                    $session->set( 'is_admin', ( $user['role'] === 'admin' ) );
                    $session->set( 'user_id', $user['id'] );
                    $session->set( 'user_name', $user['username'] );
                    $session->set( 'user_role', $user['role'] );
                    $session->set( 'last_activity', time() );

                    $db->runQuery( 'UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']] );
                }
            } else {
                // Если токен не найден или агент не совпал — чистим подозрительную куку
                Flight::cookie()->set( 'remember_token', '', -3600, '/', '' );
            }
        }

        // 5. Данные для Smarty
        if ( $session->get( 'user_id' ) ) {
            Flight::view()->assign( 'user_name', $session->get( 'user_name' ) );
            Flight::view()->assign( 'user_role', $session->get( 'user_role' ) );
        }
    }
}
