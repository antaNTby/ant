<?php
class RememberMeMiddleware
{
    public function before()
    {
        $session = Flight::session();
        $db      = Flight::db();

        // Если сессия пуста, но есть кука "Запомнить меня"
        if ( !$session->get( 'user_id' ) && $rawToken = Flight::cookie()->get( 'remember_token' ) ) {

            $tokenHash = hash( 'sha256', $rawToken );
            $tokenData = $db->fetchRow(
                'SELECT id, user_id FROM user_tokens WHERE token_hash = ? AND expires_at > NOW()',
                [$tokenHash]
            );

            if ( $tokenData ) {
                // 1. Авторизуем в сессию
                $session->set( 'user_id', $tokenData['user_id'] );

                // 2. РОТАЦИЯ: Создаем новый токен взамен использованного
                $newRawToken   = bin2hex( random_bytes( 32 ) );
                $newTokenHash  = hash( 'sha256', $newRawToken );
                $expireTime    = time() + ( 3600 * 24 * 30 ); // Еще на 30 дней
                $expireSeconds = 2592000;

                // Обновляем запись в БД
                $db->runQuery(
                    'INSERT INTO user_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)',
                    [$user['id'], $tokenHash, date( 'Y-m-d H:i:s', time() + $expireSeconds )]
                );

                // Обновляем куку в браузере
// 1. Название куки
// 2. ЗНАЧЕНИЕ куки (строка)
// 3. Настройки (массив)
                $expireSeconds = 3600 * 24 * 30; // 30 дней

                Flight::cookie()->set( 'remember_token', $rawToken, [
                    'expires'  => $expireSeconds, // Здесь только число секунд!
                    'httponly' => true,
                    'secure'   => true,
                    'path'     => '/',
                ] );

            } else {
                // Токен невалиден — чистим мусор
                Flight::cookie()->set( 'remember_token', '', ['expires' => time() - 3600] );
            }
        }
    }
}
