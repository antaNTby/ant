<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:43
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\subs\currency_sub.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173d7649640_17008580',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '40dacf7ec16b3146cd91be03ce0e2c5913e0e900' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\subs\\currency_sub.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:subs/navigator.tpl.html' => 1,
  ),
))) {
function content_69c173d7649640_17008580 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\subs';
?><h2>Типы валют</h2>
<?php $_smarty_tpl->renderSubTemplate("file:subs/navigator.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('links'=>$_smarty_tpl->getValue('nav_links')), (int) 0, $_smarty_current_dir);
?>
<div class="table-responsive-xxl">
    <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'MAIN_TABLE');?>

</div>
<hr class="my-3">
<div class="table-responsive-xxl">
    <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'SECOND_TABLE');?>

</div>
<hr class="my-3">
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "TABLE_THEAD", null, null);?>
<thead class="table-light">
    <tr class="align-self-center align-middle text-center">
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['N'];?>
" class='text-end align-middle font-monospace border-end'><?php echo $_smarty_tpl->getValue('th_titles')['N'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['CID'];?>
"><?php echo $_smarty_tpl->getValue('th_titles')['CID'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['Name'];?>
"><?php echo $_smarty_tpl->getValue('th_titles')['Name'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['code'];?>
"><?php echo $_smarty_tpl->getValue('th_titles')['code'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['currency_value'];?>
" class="table-active"><?php echo $_smarty_tpl->getValue('th_titles')['currency_value'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['nds20'];?>
"><?php echo $_smarty_tpl->getValue('th_titles')['nds20'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['where2show'];?>
"><?php echo $_smarty_tpl->getValue('th_titles')['where2show'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['sort_order'];?>
"><?php echo $_smarty_tpl->getValue('th_titles')['sort_order'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['currency_iso_3'];?>
"><?php echo $_smarty_tpl->getValue('th_titles')['currency_iso_3'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['roundval'];?>
"><?php echo $_smarty_tpl->getValue('th_titles')['roundval'];?>
</th>
        <th style="<?php echo $_smarty_tpl->getValue('th_styles')['actions'];?>
" class="table-secondary fw-bolder text-center align-middle font-monospace border-start"><i class="bi bi-terminal-fill"></i>&nbsp;<?php echo $_smarty_tpl->getValue('th_titles')['actions'];?>
</th>
    </tr>
</thead>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "THEAD_FIELDNAMES", null, null);?>
<tr class="align-middle text-center text-secondary text-xs table-dark">
    <td></td>
    <td title="CID">CID</td>
    <td title="Name">Name</td>
    <td title="code">code</td>
    <td title="currency_value">currency_value</td>
    <td title="nds20">nds20</td>
    <td title="where2show">where2show</td>
    <td title="sort_order">sort_order</td>
    <td title="currency_iso_3">currency_iso_3</td>
    <td title="roundval">roundval</td>
    <td></td>
</tr>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "MAIN_TABLE", null, null);?>
<table id="mainTable" class="table table-fixed-layout table-borderless table-hover table-sm caption-top text-end border border-2" style="max-width:80%">
    <caption>Типы валют</caption>
    <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'TABLE_THEAD');?>

    <tbody class="table-group-divider">
        <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'THEAD_FIELDNAMES');?>

        <?php
$__section_cc_0_loop = (is_array(@$_loop=$_smarty_tpl->getValue('data')) ? count($_loop) : max(0, (int) $_loop));
$__section_cc_0_total = $__section_cc_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_cc'] = new \Smarty\Variable(array());
if ($__section_cc_0_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_cc']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_cc']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_cc']->value['iteration'] <= $__section_cc_0_total; $_smarty_tpl->tpl_vars['__smarty_section_cc']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_cc']->value['index']++){
?>
        <tr class="align-self-center align-middle text-center" data-index="<?php echo $_smarty_tpl->getValue('data')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['CID'];?>
">
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['N'];?>
" class="table-secondary text-sm text-end align-middle font-monospace border-end"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('zeroPad')(($_smarty_tpl->getValue('__smarty_section_cc')['iteration'] ?? null),2);?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['CID'];?>
" name="CID" data-dt-name="CID"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][0]->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['Name'];?>
" name="Name" data-dt-name="Name"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][1]->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['code'];?>
" name="code" data-dt-name="code"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][2]->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['currency_value'];?>
" class="table-active" name="currency_value" data-dt-name="currency_value"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][3]->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['nds20'];?>
" data-index="<?php echo $_smarty_tpl->getValue('data')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['CID'];?>
" data-old-currency-value="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('formatUsd')($_smarty_tpl->getValue('data')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['currency_value']);?>
">
                <div class="d-grid gap-1 text-end lh-1 fw-lighter font-monospace text-body-secondary" data-helper="currency_value" data-index="<?php echo $_smarty_tpl->getValue('data')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['CID'];?>
">
                    <sup class="m-0 p-0 d-block"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('formatUsd')((1.2*$_smarty_tpl->getValue('data')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['currency_value']));?>
</sup>
                    <sub class="m-0 p-0 d-block"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('formatUsd')((1/1.2*$_smarty_tpl->getValue('data')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['currency_value']));?>
</sub>
                </div>
            </td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['where2show'];?>
" name="where2show" data-dt-name="where2show"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][4]->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['sort_order'];?>
" name="sort_order" data-dt-name="sort_order"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][5]->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['currency_iso_3'];?>
" name="currency_iso_3" data-dt-name="currency_iso_3"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][6]->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['roundval'];?>
" name="roundval" data-dt-name="roundval"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][7]->getHtml();?>
</td>
            <th style="<?php echo $_smarty_tpl->getValue('td_styles')['actions'];?>
" class="table-white text-start align-middle font-monospace border-start"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['actions']->getHtml();?>
</th>
        </tr>
        <?php
}
}
?>
        <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'THEAD_FIELDNAMES');?>

    </tbody>
</table>
<div class="d-flex pt-2">
    <div class="p-2 bg-body"><?php echo $_smarty_tpl->getValue('controls')['mainToolbar']['actions']->getHtml();?>
</div>
</div>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "SECOND_TABLE", null, null);?>
<table id="secondTable" class="table table-fixed-layout table-borderless table-sm caption-top text-end border border-2" style="max-width:80%">
    <caption>Добавить новую валюту</caption>
    <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'TABLE_THEAD');?>

    <tbody class="table-group-divider">
        <tr class="align-self-center align-middle text-center" data-index="-1">
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['N'];?>
" class="table-secondary text-sm text-end align-middle font-monospace border-end"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('zeroPad')(($_smarty_tpl->getValue('__smarty_section_cc')['iteration'] ?? null),2);?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['CID'];?>
" name="add_CID" data-dt-name="CID"><?php echo $_smarty_tpl->getValue('controls')['addNew']['CID']->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['Name'];?>
" name="add_Name" data-dt-name="Name"><?php echo $_smarty_tpl->getValue('controls')['addNew']['Name']->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['code'];?>
" name="add_code" data-dt-name="code"><?php echo $_smarty_tpl->getValue('controls')['addNew']['code']->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['currency_value'];?>
" class="table-active" name="add_currency_value" data-dt-name="currency_value"><?php echo $_smarty_tpl->getValue('controls')['addNew']['currency_value']->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['nds20'];?>
" data-index="-1" data-old-currency-value="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('formatUsd')($_smarty_tpl->getValue('controls')['addNew']['currency_value']->getCurrentValue());?>
">
                <div class="d-grid gap-1 text-end lh-1 fw-lighter font-monospace text-body-secondary" data-helper="currency_value" data-index="-1">
                    <sup class="m-0 p-0 d-block"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('formatUsd')((1.2*$_smarty_tpl->getValue('controls')['addNew']['currency_value']->getCurrentValue()));?>
</sup>
                    <sub class="m-0 p-0 d-block"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('formatUsd')((1/1.2*$_smarty_tpl->getValue('controls')['addNew']['currency_value']->getCurrentValue()));?>
</sub>
                </div>
            </td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['where2show'];?>
" name="add_where2show" data-dt-name="where2show"><?php echo $_smarty_tpl->getValue('controls')['addNew']['where2show']->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['sort_order'];?>
" name="add_sort_order" data-dt-name="sort_order"><?php echo $_smarty_tpl->getValue('controls')['addNew']['sort_order']->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['currency_iso_3'];?>
" name="add_currency_iso_3" data-dt-name="currency_iso_3"><?php echo $_smarty_tpl->getValue('controls')['addNew']['currency_iso_3']->getHtml();?>
</td>
            <td style="<?php echo $_smarty_tpl->getValue('td_styles')['roundval'];?>
" name="add_roundval" data-dt-name="roundval"><?php echo $_smarty_tpl->getValue('controls')['addNew']['roundval']->getHtml();?>
</td>
            <th style="<?php echo $_smarty_tpl->getValue('td_styles')['actions'];?>
" class="table-white text-start align-middle font-monospace border-start"><?php echo $_smarty_tpl->getValue('controls')['addNew']['actions']->getHtml();?>
</th>
        </tr>
        <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'THEAD_FIELDNAMES');?>

    </tbody>
</table>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
}
}
