<?php
declare ( strict_types = 1 ); // Для строгости типов данных

namespace app\controllers;

use BaseSubController;
use Flight;
use flight\database\SimplePdo;
use flight\Engine;

class RenderDataController
{

    protected Engine $app;
    protected SimplePdo $db;

    public function __construct( Engine $app )
    {
        $this->app = $app;
        $this->db  = $app->db();
    }

    /**
     * Подготовка базовых данных
     */
    private static function getBaseData( ?string $title ): array
    {
        $session = Flight::session();

        $data = [
            // Собираем данные юзера
            'user_id'   => $session->get( 'user_id' ),
            'user_name' => $session->get( 'user_name' ),
            'user_role' => $session->get( 'user_role' ),
            'is_auth'   => $session->get( 'user_id' ) !== null,
        ];

        // Сливаем массивы сразу в ключе baseData

        return [
            'title'        => $title ?: '-nix.by-',
            'current_date' => date( 'Y-m-d' ),
            'current_time' => date( 'H:i:s' ),
            'year'         => date( 'Y' ),
            'server_name'  => SERVER_NAME,
            'copyright'    => COPYRIGHT,
            'baseData'     => array_merge( $data, self::getQueryMessages() ),
        ];
    }

    private static function getQueryMessages(): array
    {
        $query = Flight::request()->query;
        if ( !$query->error && !$query->okey ) {
            return [];
        }

        static $errors = [
            'Access Denied'           => 'Доступ запрещен.',
            'Account is Banned'       => 'Ваш аккаунт заблокирован администратором.',
            'Account is Blocked'      => 'Ваш аккаунт заблокирован.',
            'Authentication Failed'   => 'Неверный логин\почта или пароль',
            'Database Error'          => 'При регистрации произошла ошибка',
            'Duplicate Entry'         => 'Имя пользователя или адрес электронной почты заняты',
            'Incorrect Account'       => 'Неверный логин или пароль.',
            'Login Failed'            => 'Ошибка входа. Попробуйте ещё раз.',
            'No active session found' => 'Сессия не найдена или уже завершена.',
            'No Permission'           => 'Недостаточно прав.',
            'Password Mismatch'       => 'Пароли не совпадают',
            'Registration Failed'     => 'При регистрации произошла ошибка',
        ];

        static $successes = [
            'All sessions terminated' => 'Отлично! Все сессии завершены.',
            'Life is Good'            => 'И слава Богу!',
            'Registration Success'    => 'Регистрация прошла успешно! Войдите.',
            'Stale tokens cleared'    => 'Удалены все истекшие токены',
        ];

        $translate = fn( $val, $dict ) => $val ? ( $dict[rawurldecode( $val )] ?? rawurldecode( $val ) ) : '';

        return [
            'query_error'   => $query->error,
            'query_okey'    => $query->okey,
            'error_message' => $translate( $query->error, $errors ),
            'okey_message'  => $translate( $query->okey, $successes ),
        ];
    }

    public function showSubPageShowAll(
        $sub_page,
        $sortField,
        $sortDirection,
    ): void {

        $templatePath = "admin/dpt/subs/{$sub_page}.tpl.html";
        bdump(
            [
                $sortField,
                $sortDirection,
            ]
        );

    }

    public function showSubPage(
        $sub_page,
        $page,
        $limit,
        $sortField,
        $sortDirection,
    ): void {
        // 1. Формируем путь к шаблону
        $templatePath = "admin/dpt/subs/{$sub_page}.tpl.html";
        bdump(
            [
                $page,
                $limit,
                $sortField,
                $sortDirection,

            ]
        );
        // 2. Базовые данные, которые нужны всегда
        $data = [
            'title'        => ucfirst( $sub_page ) . ' - Панель управления',
            'current_page' => $sub_page,
            'time'         => date( 'H:i:s' ),

            'subName'      => $sub_page,

        ];

        // 3. Специфические данные для разных страниц (через switch или match)
        switch ( $sub_page ) {
            case 'companies':
                $data['list']      = [/* тут запрос к БД для компаний */];
                $data['companies'] = [/* тут запрос к БД для компаний */];
                break;
            case 'sessions':

                $sessions = Flight::authService()->getUserSessions();
                // $data['sessions'] = [/* данные сессий */];

                $data['sessions'] = $sessions;
                break;
            case 'tests':
                $data['test_status'] = 'Active';
                $controller          = new \app\controllers\BaseSubController( $this->app ); // Наследник BaseSubController

                dd( $controller );

                break;
            default:
                // Если страницы нет, кидаем 404 или редирект
                Flight::notFound();

                return;
        }

        $Data['subData'] = $data;

        // dd( $Data );
        self::display( $templatePath, $Data );
    }

    /**
     * Метод для вызова в роутах
     */
    public static function display(
        string $template,
        array  $data = []
    ): void {
        $smarty = Flight::view();

        // Объединяем базовые данные и специфичные для страницы
        $title   = $data['title'] ?? null;
        $allData = array_merge( self::getBaseData( $title ), $data );

        // Передаем данные в Smarty
        foreach ( $allData as $key => $value ) {
            $smarty->assign( $key, $value );
        }

        bdump( $allData );
        // Рендерим шаблон
        $smarty->display( $template );
    }

}
