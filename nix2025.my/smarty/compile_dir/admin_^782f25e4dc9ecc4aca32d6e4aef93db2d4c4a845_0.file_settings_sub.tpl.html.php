<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:12:53
  from 'file:C:\git\ant\nix2025.my\admin\tpl\subs\settings_sub.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c17495d86f64_20629816',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '782f25e4dc9ecc4aca32d6e4aef93db2d4c4a845' => 
    array (
      0 => 'C:\\git\\ant\\nix2025.my\\admin\\tpl\\subs\\settings_sub.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c17495d86f64_20629816 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\ant\\nix2025.my\\admin\\tpl\\subs';
?><form name="<?php echo $_smarty_tpl->getValue('current_sub');?>
_sub" action="submit/settings" method="post" accept-charset="utf-8">
    <input name="formName" type="hidden" value="<?php echo $_smarty_tpl->getValue('current_sub');?>
_sub">
    <div class="row">
        <div class="col-4">
            <h2>Ширина экрана</h2>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="widthIndex" id="widthIndex1" value="720p" <?php if ((null !== ($_COOKIE['COOKIE_WIDTH_INDEX'] ?? null)) && $_COOKIE['COOKIE_WIDTH_INDEX'] == '720p') {?> checked<?php }?>> <label class="form-check-label" for="widthIndex1">
                720p 1280x720
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="widthIndex" id="widthIndex2" value="1080p" <?php if ((null !== ($_COOKIE['COOKIE_WIDTH_INDEX'] ?? null)) && $_COOKIE['COOKIE_WIDTH_INDEX'] == '1080p') {?> checked<?php }?>> <label class="form-check-label" for="widthIndex2">
                1080p 1920x1080
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="widthIndex" id="widthIndex3" value="1440p" <?php if ((null !== ($_COOKIE['COOKIE_WIDTH_INDEX'] ?? null)) && $_COOKIE['COOKIE_WIDTH_INDEX'] == '1440p') {?> checked<?php }?>> <label class="form-check-label" for="widthIndex3">
                2k 2560x1440
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="widthIndex" id="widthIndex4" value="4k">
                <label class="form-check-label" for="widthIndex4" <?php if ((null !== ($_COOKIE['COOKIE_WIDTH_INDEX'] ?? null)) && $_COOKIE['COOKIE_WIDTH_INDEX'] == '4k') {?> checked<?php }?>> 4k 3840x2560 </label>
            </div>
        </div> 

        <div class="col-4">
            <h2>Положение бокового меню</h2>
            <select name="setMenuPosition" class="form-select" aria-label="Menu Position">
                                <option value="left" <?php if ((null !== ($_COOKIE['COOKIE_MENU_POSITION'] ?? null)) && $_COOKIE['COOKIE_MENU_POSITION'] == 'left') {?> selected<?php }?>>  Слева</option>
                <option value="right" <?php if ((null !== ($_COOKIE['COOKIE_MENU_POSITION'] ?? null)) && $_COOKIE['COOKIE_MENU_POSITION'] == 'right') {?> selected<?php }?>> Справа</option>
                <option value="off" <?php if ((null !== ($_COOKIE['COOKIE_MENU_POSITION'] ?? null)) && $_COOKIE['COOKIE_MENU_POSITION'] == 'off') {?> selected<?php }?>>  Отключено</option> 
                            </select>
        </div> 

        <div class="col-8">
            <button class="btn btn-outline-success btn-lg mt-3 w-100" type="submit">OK</button>
        </div>
   </div>
</form>


<hr>
<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('debug_print_var')($_COOKIE);?>

<hr>
<h5>
    <?php echo $_smarty_tpl->getValue('admin_main_content_template');?>

</h5><?php }
}
