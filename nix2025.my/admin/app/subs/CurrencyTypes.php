<?php
namespace subs;

/*
	"CID" => 1
	"Name" => "Доллары США"
	"code" => "$"
	"currency_value" => 1.0
	"where2show" => 0
	"sort_order" => 0
	"currency_iso_3" => "USD"
	"roundval" => 2
*/

/**
 * Класс ActiveRecord обычно единственное число
 *
 * Настоятельно рекомендуется добавлять свойства таблицы в виде комментариев здесь
 *
 * @property int    $CID
 * @property string $Name
 * @property string $code
 * @property float $currency_value
 * @property int $where2show
 * @property int $sort_order
 * @property string $currency_iso_3
 * @property int $roundval
 */
class CurrencyTypes extends \classes\StandartSub
{

    protected string $tableName      = 'UTF_currency_types';
    protected string $primaryKey     = 'CID';
    protected string $orderingFileld = 'sort_order';

    protected string $fieldsList = 'Name, code, currency_value, where2show, sort_order, currency_iso_3, roundval';

    public array $defaultValues = [
        'CID'            => -1,
        'Name'           => 'Уругвайские Ескудо',
        'code'           => 'xxx',
        'currency_value' => '10',
        'where2show'     => 1,
        'sort_order'     => -10,
        'currency_iso_3' => 'XXX',
        'roundval'       => 2,
    ];

    protected array $subRules      = ['controlType' => 'input_plaintext'];
    protected array $specificRules = [
        'CID'            => ['controlType' => 'input_plaintext', 'font_style' => 'fst-italic fw-semibold text-black', 'readonly' => 1, 'text_align' => 'text-center'],
        'Name'           => ['controlType' => 'input_text', 'text_align' => 'text-start'],
        'code'           => ['controlType' => 'input_text', 'text_align' => 'text-start'],
        'currency_iso_3' => ['controlType' => 'input_text', 'text_align' => 'text-center'],
        'currency_value' => ['controlType' => 'input_number', 'string_format' => '%.4f', 'text_align' => 'text-end', 'positive_only' => 1],
        'sort_order'     => ['controlType' => 'sort_order', 'string_format' => '%.0f'],
        'roundval'       => ['controlType' => 'input_number', 'string_format' => '%.0f', 'text_align' => 'text-end'],
        'where2show'     => ['controlType' => 'switch', 'reverse' => 1, 'label' => 'до\после', 'text_align' => 'text-center'],
    ];

    public array $td_names = [
        'N',
        'CID',
        'Name',
        'code',
        'currency_value',
        'where2show',
        'sort_order',
        'currency_iso_3',
        'roundval',
        'actions',
    ];

    public array $td_styles = [
        'N'              => 'width:3ch;min-width:3ch;max-width:3ch;',
        'CID'            => 'width:4ch;min-width:4ch;max-width:4ch;',
        'Name'           => 'width:12%!important;',
        'code'           => 'width:8ch;min-width:8ch;max-width:8ch;',
        'currency_value' => 'width:12ch;min-width:12ch;max-width:12ch;',
        'nds20'          => 'width:7ch;min-width:7ch;max-width:8ch;',
        'where2show'     => 'width:15ch;min-width:15ch;max-width:15ch;',
        'sort_order'     => 'width:12ch;min-width:12ch;max-width:12ch;',
        'currency_iso_3' => 'width:6ch;min-width:6ch;max-width:6ch;',
        'roundval'       => 'width:7%;',
        'actions'        => '',
    ];

    public array $th_styles = [
        'N'              => 'width:3ch;min-width:3ch;max-width:3ch;',
        'CID'            => 'width:4ch;min-width:4ch;max-width:4ch;',
        'Name'           => 'width:12%!important;',
        'code'           => 'width:8ch;min-width:8ch;max-width:8ch;',
        'currency_value' => 'width:12ch;min-width:12ch;max-width:12ch;',
        'nds20'          => 'width:7ch;min-width:7ch;max-width:8ch;padding:0;',
        'where2show'     => 'width:15ch;min-width:15ch;max-width:15ch;',
        'sort_order'     => 'width:12ch;min-width:12ch;max-width:12ch;',
        'currency_iso_3' => 'width:6ch;min-width:6ch;max-width:6ch;',
        'roundval'       => 'width:7%;',
        'actions'        => '',
    ];

    public array $th_titles = [
        'N'              => '#',
        'CID'            => 'id',
        'Name'           => 'Назва&shy;ние',
        'code'           => 'Сим&shy;вол',
        'currency_value' => 'Курс',
        'nds20'          => '&plusmn; НДС',
        'where2show'     => 'Пози&shy;ция',
        'sort_order'     => 'Сорти&shy;ровка',
        'currency_iso_3' => 'Код &shy;ISO3',
        'roundval'       => 'Округ&shy;ление',
        'actions'        => 'Дейст&shy;вия',
    ];

}
