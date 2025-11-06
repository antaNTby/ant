<?php
/* Smarty version 5.6.0, created on 2025-11-06 13:26:45
  from 'file:header_navbar.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.6.0',
  'unifunc' => 'content_690ca21568f766_73511538',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '877087a046f5567c21200e9cc296c5c987a704e6' => 
    array (
      0 => 'header_navbar.tpl.html',
      1 => 1757568371,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_690ca21568f766_73511538 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\ant\\ant.my\\app\\templates';
?><!-- Липкий навбар -->
<nav class="navbar navbar-dark bg-dark sticky-top">
            <a class="navbar-brand ms-3" href="/"><?php echo (defined('SERVER_NAME') ? constant('SERVER_NAME') : null);?>
</a>
        <div class="d-flex flex-row px-2 flex-fill">
            <ul class="navbar-nav list-group list-group-horizontal">
                <li class="nav-item px-1"><a class="nav-link" href="/">Главная</a></li>
                                <li class="nav-item px-1">
                    <a class="nav-link" aria-current="page" href="/admin/sub/currency">
                        Типы валют
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link" aria-current="page" href="/admin/sub/statuses">
                        Статусы заказов
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link" aria-current="page" href="/admin/sub/companies">
                        Организации
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link" aria-current="page" href="/admin/sub/customers">
                        Покупатели
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link" aria-current="page" href="/admin/settings">
                        Настройки сайта
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link" aria-current="page" href="/admin/log">
                        log
                    </a>
                </li>
            </ul>
        </div>
        <form class="d-flex d-inline-flex px-2 py-0" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
            <button class="btn btn-dark" type="submit">Search</button>
        </form>
    </nav><?php }
}
