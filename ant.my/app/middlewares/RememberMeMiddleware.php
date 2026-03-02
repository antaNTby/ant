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

        // 1. Проверяем наличие active session и remember_token
        if ( !$session->get( 'user_id' ) && ( $rawToken = Flight::cookie()->get( 'remember_token' ) ) ) {

            // Хэшируем raw-token
            $tokenHash = hash( 'sha256', $rawToken );

            // Получаем текущий User-Agent клиента
            $currentUserAgent = $request->user_agent;

            // Проверяем существование действующего токена в базе данных
            $tokenData = $db->fetchRow(
                'SELECT id, user_id FROM user_tokens
                 WHERE token_hash = :hash AND user_agent = :agent AND expires_at > NOW()',
                ['hash' => $tokenHash, 'agent' => $currentUserAgent]
            );

            if ( $tokenData ) {

                // Получаем профиль пользователя по его ID
                $user = $db->fetchRow( 'SELECT * FROM users WHERE id = ?', [$tokenData['user_id']] );

                // Проверяем активность пользователя
                if ( $user && $user['is_active'] ) {

                    // Ротация токена: удаление старого токена
                    $db->runQuery( 'DELETE FROM user_tokens WHERE id = ?', [$tokenData['id']] );

                    // Генерация нового токена
                    $newRawToken   = bin2hex( random_bytes( 32 ) );
                    $newTokenHash  = hash( 'sha256', $newRawToken );
                    $expireSeconds = Flight::get( 'TOKEN_EXPIRE_TIMEOUT' );

                    // Добавляем новый токен в базу данных
                    // Обновляем значение remember_token в браузере

                    $newTokenId = $db->insert( 'user_tokens', [
                        'user_id'    => $user['id'],
                        'token_hash' => $newTokenHash,
                        'user_agent' => $currentUserAgent,
                        'created_ip' => $request->ip,
                        'expires_at' => date( 'Y-m-d H:i:s', time() + $expireSeconds ),
                    ] );

                    Flight::cookie()->set( 'remember_token', $newRawToken, $expireSeconds, '/', '', false, true );

                    // Авторизуем пользователя и устанавливаем новую сессию

                    $result = Flight::authService()->createInternalSession( $user );

                    // Сохраняем ID токена в сессии
                    $session->set( 'current_token_id', $db->getConnection()->lastInsertId() );

                    // Обновляем последнее время входа пользователя
                    Flight::authService()->setLastLogin( $user );
                }
            } else {
                // Очищаем invalid token
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
