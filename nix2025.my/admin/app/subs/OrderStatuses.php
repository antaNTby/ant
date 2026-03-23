<?php
namespace subs;

/**
 * Класс ActiveRecord обычно единственное число
 *
 * Настоятельно рекомендуется добавлять свойства таблицы в виде комментариев здесь
 *
 * @property int    $statusID
 * @property string $status_name
 * @property int $sort_order

 * @property int $roundval
 */
class OrderStatuses extends \classes\StandartSub
{

    protected string $tableName  = 'UTF_order_status';
    protected string $primaryKey = 'statusID';

    public array $defaultValues = [
        'statusID'    => -1,
        'status_name' => 'Неопределенный',
        'sort_order'  => -10,
    ];
    protected string $fieldsList = 'status_name, sort_order';

    protected array $specificRules = [
        'statusID'    => ['controlType' => 'input_plaintext', 'font_style' => 'fst-italic fw-semibold text-black', 'readonly' => 1, 'text_align' => 'text-center'],
        'status_name' => ['controlType' => 'input_text', 'text_align' => 'text-start'],
        'sort_order'  => ['controlType' => 'sort_order', 'string_format' => '%.0f', 'text_align' => 'text-end'],
    ];

    public array $td_names = [
        'N',
        'statusID',
        'status_name',
        'sort_order',
        'actions',
    ];

    public array $td_styles = [
        'N'           => 'width:3ch;min-width:3ch;max-width:3ch;',
        'statusID'    => 'width:4ch;min-width:4ch;max-width:4ch;',
        'status_name' => 'width:33%!important;',
        'sort_order'  => 'width:12ch;min-width:12ch;max-width:12ch;',
        'actions'     => '',
    ];

    public array $th_styles = [
        'N'           => 'width:3ch;min-width:3ch;max-width:3ch;',
        'statusID'    => 'width:4ch;min-width:4ch;max-width:4ch;',
        'status_name' => 'width:33%!important;',
        'sort_order'  => 'width:12ch;min-width:12ch;max-width:12ch;',
        'actions'     => '',
    ]; //writing-mode: sideways-lr;

    public array $th_titles = [
        'N'           => '#',
        'statusID'    => 'id',
        'status_name' => 'Назва&shy;ние',
        'sort_order'  => 'Сорти&shy;ровка',
        'actions'     => 'Дейст&shy;вия',
    ];
}
