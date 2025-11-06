<?php
/* Smarty version 5.6.0, created on 2025-11-06 13:26:45
  from 'file:footer.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.6.0',
  'unifunc' => 'content_690ca2156f00f1_57226892',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '874e86367b68be8e72a13f52682b1cc85f54829a' => 
    array (
      0 => 'footer.tpl.html',
      1 => 1757568371,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_690ca2156f00f1_57226892 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\ant\\ant.my\\app\\templates';
?><!-- Прилипший футер -->
<footer class="sticky-bottom d-flex p-0 mt-1 flex-column text-bg-dark opacity-25">
    <div class="container-fluid text-center">
        <div class="row align-items-end">
            <div class="col">
                <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format')(time(),'%Y-%m-%d %H:%M:%S');?>

            </div>
            <div class="col">
                One
            </div>
            <div class="col">
                © 2025 Права принадлежат тем, кто за них борется.
            </div>
        </div>
    </div>
</footer><?php }
}
