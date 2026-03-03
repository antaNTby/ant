<?php
declare ( strict_types = 1 ); // Для строгости типов данных

namespace app\controllers;

use Flight;

class RenderDataController
{

    /**
     * Подготовка базовых данных
     */
    private static function getBaseData( ?string $title ): array
    {

        $rawError = Flight::request()->query->error;
        $rawOkey  = Flight::request()->query->okey;

        $errorMsg = null;
        $okeyMsg  = null;

        if ( $rawError !== null ) {
            $errorDecoded = rawurldecode( $rawError );
            $messages     = [
                'Account is Banned' => 'Ваш аккаунт заблокирован администратором.',
                'Incorrect Account' => 'Неверный логин или пароль.',
                'Login Failed'      => 'Ошибка входа. Попробуйте ещё раз.',
            ];
            $errorMsg = $messages[$errorDecoded] ?? $errorDecoded;
        }

        if ( $rawOkey !== null ) {
            $okeyDecoded = rawurldecode( $rawOkey );
            $messages    = [
                'All sessions terminated' => 'Отлично! Все сессии завершены.',
                'Registration Success'    => 'Регистрация прошла успешно! Войдите.',
                'Life is Good'            => 'И слава Богу!',
            ];
            $okeyMsg = $messages[$okeyDecoded] ?? $okeyDecoded;
        }

        $data = [
            'title'         => $title ?: '-nix.by-',
            'current_date'  => date( 'Y-m-d' ),
            'current_time'  => date( 'H:i:s' ),
            'year'          => date( 'Y' ),
            'server_name'   => SERVER_NAME,
            'copyright'     => COPYRIGHT,
            'error_message' => $errorMsg ?? '',
            'okey_message'  => $okeyMsg ?? '',
            'query_error'   => Flight::request()->query->error, // Ошибка из URL
            'query_okey'    => Flight::request()->query->okey,  // OK из URL
            'user_id'       => Flight::session()->get( 'user_id' ),
            'user_name'     => Flight::session()->get( 'user_name' ),
            'user_role'     => Flight::session()->get( 'user_role' ),
            'is_auth'       => Flight::session()->get( 'user_id' ) !== null,
            'app'           => Flight::app(),

            // Сюда можно добавить меню, ссылки на ассеты и т.д.
        ];

        $Data['baseData'] = $data;

        return $Data;

    }

    public function showSubPage( $sub_page )
    {
        // 1. Формируем путь к шаблону
        $templatePath = "admin/dpt/subs/{$sub_page}.tpl.html";

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
                break;
            default:
                // Если страницы нет, кидаем 404 или редирект
                Flight::notFound();

                return;
        }

        $Data['subData'] = $data;

        // 4. Рендерим страницу
        // В твоем случае Flight::Display (с большой буквы, как в роутах)
        // Flight::Display($templatePath, $data);

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
