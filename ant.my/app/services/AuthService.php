<?php
namespace app\services;

use Flight;

class AuthService
{

    private function registerUser(
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
            $this->createInternalSession( $user );
            $this->setLastLogin( $user );

            Flight::flash( 'light', 'Вы вошли как "' . $username . '" / ' . $email );

            return ['success' => true, 'message' => 'Вы вошли как "' . $username . '" / ' . $email];
        } catch ( \Exception $e ) {
            Flight::flash( 'dark', 'Ошибка базы данных' );

            return ['success' => false, 'error' => 'DB error', 'message' => 'Ошибка базы данных'];
        }

    }

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
        $user = $db->fetchRow( 'SELECT * FROM users WHERE username = ? OR email = ?', [$username, $username] );

        // 2. Валидация
        if ( !$user || !password_verify( $password, $user['password_hash'] ) ) {
            Flight::flash( 'danger', 'Неверный логин/email или пароль' );

            return ['success' => false, 'error' => 'Incorrect+Account', 'message' => 'Ошибка авторизации'];
        }

        if ( !$user['is_active'] ) {
            Flight::flash( 'danger', 'Ваш аккаунт заблокирован' );

            return ['success' => false, 'error' => 'Account+is+Banned', 'message' => 'Ваш аккаунт заблокирован'];
        }

        // 3. Создание сессии
        $this->createInternalSession( $user );
        $this->setLastLogin( $user );

        // bdump( $rememberMe );
        // 4. Логика Remember Me
        if ( $rememberMe ) {
            $expireSeconds = Flight::get( 'TOKEN_EXPIRE_TIMEOUT' ) ?? 3600 * 24 * 30; // 30 дней по дефолту
            $rawToken      = bin2hex( random_bytes( 32 ) );
            $tokenHash     = hash( 'sha256', $rawToken );

            $db->runQuery(
                'INSERT INTO user_tokens (user_id, token_hash, user_agent, created_ip, expires_at)
                 VALUES (?, ?, ?, ?, ?)',
                [
                    $user['id'],
                    $tokenHash,
                    $request->user_agent,
                    $request->ip,
                    date( 'Y-m-d H:i:s', time() + $expireSeconds ),
                ]
            );

            $session->set( 'current_token_id', $db->lastInsertId() );
            Flight::cookie()->set( 'remember_token', $rawToken, $expireSeconds, '/', '', false, true );
        } else {
            // 1. Находим текущую куку, если она есть
            $oldToken = Flight::cookie()->get( 'remember_token' );

            if ( $oldToken ) {
                // Удаляем конкретно этот токен по хешу
                $db->runQuery(
                    'DELETE FROM user_tokens WHERE token_hash = ?',
                    [hash( 'sha256', $oldToken )]
                );
            }

            // 2. Дополнительная зачистка: удаляем ВСЕ токены этого юзера для ДАННОГО браузера
            // Это подстраховка, если куки уже нет, а записи в БД остались
            $db->runQuery(
                'DELETE FROM user_tokens WHERE user_id = ? AND user_agent = ?',
                [$user['id'], $request->user_agent]
            );

            // 3. Обязательно затираем куку в браузере (ставим дату в прошлом)
            Flight::cookie()->set( 'remember_token', '', -3600, '/' );
        }

        // 5. Финализация
        Flight::flash( 'dark', 'С возвращением, ' . $user['username'] . '!' );

        return ['success' => true, 'okey' => 'last_login updated', 'message' => 'ok', 'role' => $user['role'], 'username' => $user['username']];
    }

    /**
     * Выход со всех устройств (удаление всех токенов в БД)
     */
    public function logoutEverywhere()
    {
        $db      = Flight::db();
        $session = Flight::session();
        $userId  = $session->get( 'user_id' );

        if ( $userId ) {
            // 1. Удаляем ВСЕ токены пользователя из базы данных
            $db->runQuery( 'DELETE FROM user_tokens WHERE user_id = ?', [$userId] );

            // 2. Очищаем куку в текущем браузере
            Flight::cookie()->set(
                'remember_token',
                '',
                -3600,
                '/',
                '',
                false, // поставьте true, если используете HTTPS
                true
            );

            // 3. Получаем текущий ID сессии ПЕРЕД её уничтожением
            $sessionId = session_id();

            // 4. Очищаем данные (массив $_SESSION)
            $session->clear();

            // 5. Уничтожаем саму сессию на сервере (теперь с аргументом)
            $session->destroy( $sessionId );

            // ВНИМАНИЕ: После destroy() сессия мертва.
            // Чтобы Flash сработал, его нужно вызвать ДО destroy или использовать
            // стандартный $_SESSION, но в 0.3.0 лучше просто сделать редирект.
        }

        return true;
    }

    /**
     * Универсальный выход из системы
     */
    public function clearSession()
    {

        $session = Flight::session();
        $session->clear();

        return true;
    }

    public function clearToken()
    {
        $db = Flight::db();

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

        return true;
    }

/**
 * Удаляет все истекшие токены (Remember Me) из базы данных
 */
    public function deleteExpiredTokens(): bool
    {
        try {
            $db = Flight::db();
            // Используем встроенную функцию БД для сравнения времени
            $db->runQuery( 'DELETE FROM user_tokens WHERE expires_at < NOW()' );

            return true;
        } catch ( \Exception $e ) {
            // Если что-то пошло не так, пишем в лог (у тебя в стеке monolog)
            Flight::get( 'logger' )?->error( 'Failed to delete expired tokens: ' . $e->getMessage() );

            return false;
        }
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
        /**/
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
