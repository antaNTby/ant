<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:53
  from 'file:subs/navigator.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e15e6dc2_49735957',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1adebe8790d3efafea911c365cf62d35742899f2' => 
    array (
      0 => 'subs/navigator.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173e15e6dc2_49735957 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\subs';
$_smarty_tpl->assign('firstItem', ($_smarty_tpl->getValue('nav_links')['current_page']-1)*$_smarty_tpl->getValue('nav_links')['items_per_page']+1, false, NULL);
$_smarty_tpl->assign('lastItem', $_smarty_tpl->getValue('nav_links')['current_page']*$_smarty_tpl->getValue('nav_links')['items_per_page'], false, NULL);
if ($_smarty_tpl->getValue('lastItem') > $_smarty_tpl->getValue('nav_links')['total_items']) {
$_smarty_tpl->assign('lastItem', $_smarty_tpl->getValue('nav_links')['total_items'], false, NULL);
}?>
<div class="d-flex justify-content-start mb-0">
    <small class="text-body-secondary">
        <?php if ((true && (true && null !== ($_smarty_tpl->getValue('nav_links')['show_all'] ?? null)))) {?>
        <?php if ($_smarty_tpl->getValue('nav_links')['items_per_page'] != $_smarty_tpl->getValue('nav_links')['total_items']) {?>
        Показано <strong><?php echo $_smarty_tpl->getValue('firstItem');?>
–<?php echo $_smarty_tpl->getValue('lastItem');?>
</strong> из <strong><?php echo $_smarty_tpl->getValue('nav_links')['total_items'];?>
</strong> элемент(-а |-ов ) —
        всего <strong><?php echo $_smarty_tpl->getValue('nav_links')['total_pages'];?>
</strong> страниц.
        <a class="btn-link" href="<?php echo $_smarty_tpl->getValue('nav_links')['show_all']['href'];?>
"><?php echo $_smarty_tpl->getValue('nav_links')['show_all']['content'];?>
</a>
        <?php } else { ?>
        Показаны все <strong><?php echo $_smarty_tpl->getValue('nav_links')['total_items'];?>
</strong> элемент(-а| -ов).
        <a class="btn-link" href="<?php echo $_smarty_tpl->getValue('nav_links')['show_all']['href'];?>
"><?php echo $_smarty_tpl->getValue('nav_links')['show_all']['content'];?>
</a>
        <?php }?>
        <?php } else { ?>
        Показано <strong><?php echo $_smarty_tpl->getValue('firstItem');?>
–<?php echo $_smarty_tpl->getValue('lastItem');?>
</strong> из <strong><?php echo $_smarty_tpl->getValue('nav_links')['total_items'];?>
</strong> элемент(-а |-ов ) —
        всего <strong><?php echo $_smarty_tpl->getValue('nav_links')['total_pages'];?>
</strong> страниц.
        <?php }?>
    </small>
</div>
<?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('nav_links')['data']) > 1) {?>
<nav class="pagination-wrapper" aria-label="Page navigation">
    <ul class="pagination justify-content-start mb-0">
        <li class="page-item<?php if ((true && (true && null !== ($_smarty_tpl->getValue('nav_links')['previous']['disabled'] ?? null))) && $_smarty_tpl->getValue('nav_links')['previous']['disabled']) {?> disabled<?php }?>">
            <a class="page-link" href="<?php echo $_smarty_tpl->getValue('nav_links')['previous']['href'];?>
">Previous</a>
        </li>
        <?php
$__section_cc_0_loop = (is_array(@$_loop=$_smarty_tpl->getValue('nav_links')['data']) ? count($_loop) : max(0, (int) $_loop));
$__section_cc_0_total = $__section_cc_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_cc'] = new \Smarty\Variable(array());
if ($__section_cc_0_total !== 0) {
for ($__section_cc_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_cc']->value['index'] = 0; $__section_cc_0_iteration <= $__section_cc_0_total; $__section_cc_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_cc']->value['index']++){
?>
        <li class="page-item
            <?php if ((true && (true && null !== ($_smarty_tpl->getValue('nav_links')['data'][($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['active'] ?? null))) && $_smarty_tpl->getValue('nav_links')['data'][($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['active']) {?> active<?php }?>
            <?php if ((true && (true && null !== ($_smarty_tpl->getValue('nav_links')['data'][($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['disabled'] ?? null))) && $_smarty_tpl->getValue('nav_links')['data'][($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['disabled']) {?> disabled<?php }?>">
            <a class="page-link" href="<?php echo $_smarty_tpl->getValue('nav_links')['data'][($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['href'];?>
" title="<?php echo ($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null);?>
">
                <?php echo $_smarty_tpl->getValue('nav_links')['data'][($_smarty_tpl->getValue('__smarty_section_cc')['index'] ?? null)]['content'];?>

            </a>
        </li>
        <?php
}
}
?>
        <li class="page-item<?php if ((true && (true && null !== ($_smarty_tpl->getValue('nav_links')['next']['disabled'] ?? null))) && $_smarty_tpl->getValue('nav_links')['next']['disabled']) {?> disabled<?php }?>">
            <a class="page-link" href="<?php echo $_smarty_tpl->getValue('nav_links')['next']['href'];?>
">Next</a>
        </li>
    </ul>
</nav>
<?php }
}
}
