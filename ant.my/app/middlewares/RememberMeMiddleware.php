<?php
namespace app\middlewares;

use Flight;

class RememberMeMiddleware
{
    /**
     * Выполняется перед каждым роутом
     */
    public function before()
    {
        $session = Flight::session();
        $db      = Flight::db();

        // 1. Проверяем: если юзер НЕ авторизован в сессии, но есть КУКА
        if ( !$session->get( 'user_id' ) && $rawToken = Flight::cookie()->get( 'remember_token' ) ) {

            // Хэшируем токен из куки для сравнения с БД
            $tokenHash = hash( 'sha256', $rawToken );

            // 2. Ищем токен в базе данных
            $tokenData = $db->fetchRow(
                'SELECT user_id, id FROM user_tokens WHERE token_hash = ? AND expires_at > NOW()',
                [$tokenHash]
            );

            if ( $tokenData ) {
                // 3. Токен найден! Подгружаем данные пользователя
                $user = $db->fetchRow( 'SELECT * FROM users WHERE id = ?', [$tokenData['user_id']] );

                if ( $user ) {
                    // 4. Восстанавливаем сессию (аналогично вашему роуту /login)
                    $session->regenerate( true );
                    $session->set( 'is_admin', ( $user['role'] === 'admin' ) );
                    $session->set( 'user_id', $user['id'] );
                    $session->set( 'user_name', $user['username'] );
                    $session->set( 'user_role', $user['role'] );
                    $session->set( 'last_activity', time() );

                    // Обновляем время последнего входа
                    $db->runQuery( 'UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']] );
                }
            } else {
                // Если токен в куке есть, но в БД его нет (или просрочен) — чистим куку
                Flight::cookie()->set( 'remember_token', '', -3600, '/', '' );
            }
        }

        // 5. Прокидываем данные в Smarty, если сессия теперь активна
        if ( $session->get( 'user_id' ) ) {
            Flight::view()->assign( 'user_name', $session->get( 'user_name' ) );
            Flight::view()->assign( 'user_role', $session->get( 'user_role' ) );
        }
    }
}
