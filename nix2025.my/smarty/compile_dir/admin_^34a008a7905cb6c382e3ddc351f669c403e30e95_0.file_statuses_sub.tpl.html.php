<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:53
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\subs\statuses_sub.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e12d2c03_50523118',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '34a008a7905cb6c382e3ddc351f669c403e30e95' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\subs\\statuses_sub.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:subs/navigator.tpl.html' => 1,
  ),
))) {
function content_69c173e12d2c03_50523118 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\subs';
?><h2>Статусы заказов</h2>
<?php $_smarty_tpl->renderSubTemplate("file:subs/navigator.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('links'=>$_smarty_tpl->getValue('nav_links')), (int) 0, $_smarty_current_dir);
?>
<form id="main-form">
    <div class="table-responsive-xxl">
        <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'MAIN_TABLE');?>

    </div>
</form>
<hr class="my-3">
<form id="second-form">
    <div class="table-responsive-xxl">
        <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'SECOND_TABLE');?>

    </div>
</form>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "TABLE_THEAD", null, null);?>
<thead class="table-light">
    <tr class="align-self-center align-middle text-center">
        <th style='<?php echo $_smarty_tpl->getValue('th_styles')['N'];?>
' class='text-end align-middle font-monospace border-end'><?php echo $_smarty_tpl->getValue('th_titles')['N'];?>
</th>
        <th style='<?php echo $_smarty_tpl->getValue('th_styles')['statusID'];?>
'><?php echo $_smarty_tpl->getValue('th_titles')['statusID'];?>
</th>
        <th style='<?php echo $_smarty_tpl->getValue('th_styles')['status_name'];?>
'><?php echo $_smarty_tpl->getValue('th_titles')['status_name'];?>
</th>
        <th style='<?php echo $_smarty_tpl->getValue('th_styles')['sort_order'];?>
'><?php echo $_smarty_tpl->getValue('th_titles')['sort_order'];?>
</th>
        <th style='<?php echo $_smarty_tpl->getValue('th_styles')['actions'];?>
' class="table-secondary fw-bolder text-center align-middle font-monospace border-start"><i class="bi bi-terminal-fill"></i>&nbsp;<?php echo $_smarty_tpl->getValue('th_titles')['actions'];?>
</th>
    </tr>
</thead>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "THEAD_FIELDNAMES", null, null);?>
<tr class="align-middle text-center text-secondary text-xs table-dark">
    <td></td>
    <td title="statusID">statusID</td>
    <td title="Name">status_name</td>
    <td title="sort_order">sort_order</td>
    <td></td>
</tr>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "MAIN_TABLE", null, null);?>
<table id="mainTable" class="table table-fixed-layout table-borderless table-hover table-sm caption-top text-end border border-2" style="max-width:60%">
    <caption>Статусы заказов</caption>
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
        <tr class="align-self-center align-middle text-center" data-index="<?php echo $_smarty_tpl->getValue('data')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['statusID'];?>
">
            <td style='<?php echo $_smarty_tpl->getValue('td_styles')['N'];?>
' class="table-secondary text-sm text-end align-middle font-monospace border-end"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('zeroPad')(($_smarty_tpl->getValue('__smarty_section_cc')['iteration'] ?? null),2);?>
</td>
            <td style='<?php echo $_smarty_tpl->getValue('td_styles')['statusID'];?>
' name="statusID" data-dt-name="statusID"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][0]->getHtml();?>
</td>
            <td style='<?php echo $_smarty_tpl->getValue('td_styles')['status_name'];?>
' name="status_name" data-dt-name="status_name"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][1]->getHtml();?>
</td>
            <td style='<?php echo $_smarty_tpl->getValue('td_styles')['sort_order'];?>
' name="sort_order" data-dt-name="sort_order" class="text-end"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)][2]->getHtml();?>
</td>
            <th style='<?php echo $_smarty_tpl->getValue('td_styles')['actions'];?>
' class="table-white text-start align-middle font-monospace border-start"><?php echo $_smarty_tpl->getValue('controls')[($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['actions']->getHtml();?>
</th>
        </tr>
        <?php
}
}
?>
    </tbody>
</table>
<div class="d-flex pt-2">
    <div class="p-2 bg-body"><?php echo $_smarty_tpl->getValue('controls')['mainToolbar']['actions']->getHtml();?>
</div>
</div>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "SECOND_TABLE", null, null);?>
<table id="secondTable" class="table table-fixed-layout table-borderless table-hover table-sm caption-top text-end border border-2" style="max-width:60%">
    <caption>Добавить новый статус</caption>
    <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'TABLE_THEAD');?>

    <tbody class="table-group-divider">
        <tr class="align-self-center align-middle text-center" data-index="-1">
            <td style='<?php echo $_smarty_tpl->getValue('td_styles')['N'];?>
' class="text-end align-middle font-monospace border-end"><?php echo ($_smarty_tpl->getValue('__smarty_section_cc')['iteration'] ?? null);?>
</td>
            <td style='<?php echo $_smarty_tpl->getValue('td_styles')['statusID'];?>
' name="add_statusID" data-dt-name="statusID"><?php echo $_smarty_tpl->getValue('controls')['addNew']['statusID']->getHtml();?>
</td>
            <td style='<?php echo $_smarty_tpl->getValue('td_styles')['status_name'];?>
' name="add_status_name" data-dt-name="status_name"><?php echo $_smarty_tpl->getValue('controls')['addNew']['status_name']->getHtml();?>
</td>
            <td style='<?php echo $_smarty_tpl->getValue('td_styles')['sort_order'];?>
' name="add_sort_order" data-dt-name="sort_order" class="text-end"><?php echo $_smarty_tpl->getValue('controls')['addNew']['sort_order']->getHtml();?>
</td>
            <th style='<?php echo $_smarty_tpl->getValue('td_styles')['actions'];?>
' class="table-white text-start align-middle font-monospace border-start"><?php echo $_smarty_tpl->getValue('controls')['addNew']['actions']->getHtml();?>
</th>
        </tr>
    </tbody>
</table>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
}
}
