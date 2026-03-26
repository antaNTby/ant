<?php
namespace classes;

use Smarty\Smarty;

class SmartyControl
{
    private const DEFAULT_TYPE    = 'input_text';
    private const ERROR_TEMPLATE  = 'errorSmartyControlTemplate.tpl.html';
    private const TEMPLATE_FOLDER = 'smartyControls';
    private const NAME_SEPARATOR  = '__';

    private Smarty $smarty;

    private string $controlName;
    private string $currentValue;
    private string $html;
    private string $index;
    private string $name;
    private string $template;
    private string $templateDir;
    private string $type;

    public array $availableTemplates = [
        'row_buttons'     => 'row_buttons.tpl.html',
        'button_toolbar'  => 'button_toolbar.tpl.html',
        'input_text'      => 'input_text.tpl.html',
        'input_number'    => 'input_number.tpl.html',
        'input_unp'       => 'input_unp.tpl.html',
        'input_plaintext' => 'input_plaintext.tpl.html',
        'input_datetime'  => 'input_datetime.tpl.html',
        'textarea'        => 'textarea.tpl.html',
        'toggle'          => 'switch.tpl.html',
        'switch'          => 'switch.tpl.html',
        'checkbox'        => 'checkbox.tpl.html',
        'radio'           => 'radio.tpl.html',
        'sort_order'      => 'sort_order.tpl.html',
    ];

    private array $standartRules = [
        'controlType'   => 'input_number',
        'font_style'    => null,
        'group_index'   => null,
        'indeterminate' => null,
        'inline'        => null,
        'label'         => '',
        'positive_only' => 0,
        'readonly'      => 0,
        'reverse'       => null,
        'string_format' => null,
        'text_align'    => 'text-end',
    ];

    public function __construct(
        array   $config = [],
        ?Smarty $smarty = null,
        ?string $type = null,
    ) {
        $config = array_merge( $this->standartRules, $config );

        $this->smarty      = $smarty ?? \Flight::view();
        $this->templateDir = $this->smarty->getTemplateDir( 0 ) . self::TEMPLATE_FOLDER;

        $this->type         = $config['controlType'] ?? self::DEFAULT_TYPE;
        $this->controlName  = $config['controlName'] ?? '';
        $this->index        = $config['index'] ?? '';
        $this->currentValue = $config['current_value'] ?? '';
        $this->name         = $config['name'] ?? ( $this->controlName ?: $type ) . self::NAME_SEPARATOR . $type;

        $this->template = $this->templateDir . DIRECTORY_SEPARATOR . $this->availableTemplates[$this->type];

        $this->html = $this->smarty->templateExists( $this->template )
        ? $this->smarty->fetch( $this->template, $config )
        : $this->smarty->fetch( $this->templateDir . DIRECTORY_SEPARATOR . self::ERROR_TEMPLATE, [
            'controlName'   => $this->controlName,
            'type'          => $this->type,
            'index'         => $this->index,
            'template'      => $this->type . '.tpl.html',
            'template_path' => $this->template,
        ] );
    }

    // Геттеры
    public function getTemplate(): string
    {return $this->template;}
    public function getType(): string
    {return $this->type;}
    public function getName(): string
    {return $this->name;}
    public function getControlName(): string
    {return $this->controlName;}
    public function getIndex(): string
    {return $this->index;}
    public function getCurrentValue(): string
    {return $this->currentValue;}
    public function getHtml(): string
    {return $this->html;}
}
