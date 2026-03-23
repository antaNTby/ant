<?php
namespace subs;

/*

  `companyID` int(11) NOT NULL AUTO_INCREMENT,
  `company_title` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_unp` varchar(32) DEFAULT NULL,
  `company_okpo` varchar(32) DEFAULT NULL,
  `company_adress` text,
  `company_bank` text,
  `company_contacts` text,
  `company_director` text,
  `company_data` text,
  `company_admin` text,
  `read_only` int(1) DEFAULT '0',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
*/

/*

companyID
company_title
company_name
company_unp
company_okpo
company_adress
company_bank
company_contacts
company_director
company_data
company_admin
read_only
sort_order
update_time

*/

/**
 * Класс ActiveRecord обычно единственное число
 *
 * Настоятельно рекомендуется добавлять свойства таблицы в виде комментариев здесь
 *
 *
 *
 * @property int companyID
 * @property string company_title
 * @property string company_name
 * @property string company_unp
 * @property string company_okpo
 * @property string company_adress
 * @property string company_bank
 * @property string company_contacts
 * @property string company_director
 * @property string company_data
 * @property string company_admin
 * @property bool read_only
 * @property int sort_order
 * @property time update_time
 *
 */
class Companies extends \classes\StandartSub
{

    protected string $tableName      = 'UTF_companies';
    protected string $primaryKey     = 'companyID';
    protected string $orderingFileld = 'sort_order';

    protected string $fieldsList = 'company_title,company_name,company_unp,company_okpo,company_adress,company_bank,company_contacts,company_director,company_data,company_admin,read_only,sort_order,update_time';

    protected array $specificRules = [
        'companyID'        => ['controlType' => 'input_plaintext', 'font_style' => 'fst-italic fw-semibold text-black', 'readonly' => 1, 'text_align' => 'text-center'],
        'company_unp'      => ['controlType' => 'input_unp', 'text_align' => 'text-end'],
        'company_director' => ['controlType' => 'textarea', 'text_align' => 'text-start'],
        'company_title'    => ['controlType' => 'textarea', 'text_align' => 'text-start'],
        'company_name'     => ['controlType' => 'textarea', 'text_align' => 'text-start'],
        'company_adress'   => ['controlType' => 'textarea', 'text_align' => 'text-start'],
        'sort_order'       => ['controlType' => 'sort_order', 'string_format' => '%.0f'],
        'read_only'        => ['controlType' => 'switch'],
        'update_time'      => ['controlType' => 'input_datetime'],
    ];

    public array $defaultValues = [
        'companyID'        => -1,
        'company_title'    => 'Демо',
        'company_name'     => 'ОАО "Наше дело"',
        'company_unp'      => '987654321',
        'company_okpo'     => 'AAA',
        'company_adress'   => 'Республика Беларусь, 220000, г.Минск, ул.К.Маркса, д.38, оф.00',
        'company_bank'     => 'р/с AAAA BBBBB CCCC DDDD EEEE 0000 в ОАО "ЖПБанк"  по г. Минску и Минской области, г. Минск, код ABCDEFGH. Адрес банка: г. Минск, ул Оффшорная, 11.',
        'company_contacts' => '+375 29 1112233',
        'company_director' => "[0 => 'Гениальный Председатель Самый-Главный Р.Б.', 1 => 'Гениального Председателя Самый-Главного Р.Б.', 2 => 'Устава']",
        'company_data'     => '',
        'company_admin'    => '',
        'read_only'        => false,
        'sort_order'       => -10,
    ];

    public array $td_names = [
        'N',
        'companyID',
        'company_title',
        'company_name',
        'company_unp',
        'company_okpo',
        'company_adress',
        'company_bank',
        'company_contacts',
        'company_director',
        'company_data',
        'company_admin',
        'read_only',
        'sort_order',
        'update_time',
    ];

    public array $td_styles = [
        'N'                => 'width:3ch;min-width:3ch;max-width:3ch;',
        'companyID'        => 'width:6ch',
        'company_title'    => 'width:16ch;',
        'company_name'     => 'width:16ch;',
        'company_unp'      => 'width:12ch;',
        'company_okpo'     => 'width:16ch;',
        'company_adress'   => 'width:16ch;',
        'company_bank'     => 'width:16ch;',
        'company_contacts' => 'width:16ch;',
        'company_director' => 'width:18ch;',
        'company_data'     => 'width:16ch;',
        'company_admin'    => 'width:16ch;',
        'read_only'        => 'width:6ch;',
        'sort_order'       => 'width:12ch;min-width:12ch;max-width:12ch;',
        'update_time'      => 'width:24ch;',
        'actions'          => 'width:25%',
    ];

    public array $th_styles = [
        'N'                => 'width:3ch;min-width:3ch;max-width:3ch;',
        'companyID'        => 'width:6ch',
        'company_title'    => 'width:16ch;',
        'company_name'     => 'width:16ch;',
        'company_unp'      => 'width:12ch;',
        'company_okpo'     => 'width:16ch;',
        'company_adress'   => 'width:16ch;',
        'company_bank'     => 'width:16ch;',
        'company_contacts' => 'width:16ch;',
        'company_director' => 'width:18ch;',
        'company_data'     => 'width:16ch;',
        'company_admin'    => 'width:16ch;',
        'read_only'        => 'width:6ch;',
        'sort_order'       => 'width:12ch;min-width:12ch;max-width:12ch;',
        'update_time'      => 'width:24ch;',
        'actions'          => 'width:25%',
    ];

    public array $th_titles = [
        'N'                => '#',
        'companyID'        => 'ID',
        'company_title'    => 'Опи&shy;са&shy;ние',
        'company_name'     => 'Назва&shy;ние',
        'company_unp'      => 'УНП',
        'company_okpo'     => 'OKPO',
        'company_adress'   => 'Адреса',
        'company_bank'     => 'Банк',
        'company_contacts' => 'Контак&shy;ты',
        'company_director' => 'Руко&shy;води&shy;тель',
        'company_data'     => 'Све&shy;де&shy;ния',
        'company_admin'    => 'Замет&shy;ки Ад&shy;мини&shy;стра&shy;тора',
        'read_only'        => 'защ&shy;ита',
        'sort_order'       => 'Сорти&shy;ровка',
        'update_time'      => 'Добав&shy;лено',
        'actions'          => 'Дейст&shy;вия',
    ];

}
