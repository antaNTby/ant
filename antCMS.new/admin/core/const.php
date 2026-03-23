<?php
### КОНСТАНТЫ

// FROM connect.inc.php
const SLASH    = '/';                                 //   {$smarty.const.SLASH}
const SITE_URL = 'antCMS.os';                         //    {$smarty.const.SITE_URL}
                                                      // const CONF_FULL_SHOP_URL = 'antCMS.os/admin';                //    {$smarty.const.CONF_FULL_SHOP_URL}
const ADMIN_FILE        = '/admin/admin.php';         //    {$smarty.const.ADMIN_FILE}
const ADMIN_LOGOUT_LINK = ADMIN_FILE . '?logout=yes'; //    {$smarty.const.ADMIN_LOGOUT_LINK}

const FILEDEBUGJSON         = 'log/FILEDEBUG.JSON';   //  {$smarty.const.FILEDEBUGJSON}
const SQLDEBUG              = 'log/SQLDEBUG.JSON';    //  {$smarty.const.SQLDEBUG}
const SMARTYDEBUGJSON       = 'log/SMARTYDEBUG.JSON'; //  {$smarty.const.SMARTYDEBUGJSON}
const ADMIN_SMARTY_LOG_VARS = 0;                      //   {$smarty.const.ADMIN_SMARTY_LOG_VARS}

const ERROR_DB_INIT = SITE_URL . ' :: ' . 'Database connection problem!'; //   {$smarty.const.ERROR_DB_INIT} database system

const LOGO256 = '/media/logo256.jpg';
const LOGO64  = '/media/logo64.jpg';

const PATH_CORE     = 'core/';
const PATH_INCLUDES = 'core/includes/';

const PATH_CONFIGS      = 'core/configs/';
const PATH_DESCRIPTIONS = 'core/descriptions/';

const PATH_TPL     = 'tpl/';
const PATH_TPL_SUB = 'tpl/sub/';

const EXT_TPL = '.tpl.html';

const ERROR_FORBIDDEN = '<!doctype html>
<html lang="ru">
<meta charset="utf-8">
<!--[if IE]><meta content="IE=edge,chrome=1"http-equiv=X-UA-Compatible><![endif]-->
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width,initial-scale=1" name="viewport">
<link href="/media/favicon.ico" rel="shortcut icon" type="image/x-icon">
<title>Welcome to ' . SITE_URL . '</title>
<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.min.css">
<div class=container-fluid style="max-width:540px">
    <img alt="nix.by" class="d-block mx-auto rounded" src="' . LOGO256 . '" style="background-color:#fff;margin:60px">
    <form id="aushform" method="post">
        <div class="row my-5">
            <label class="col-form-label col-sm-3" for="user_login">login</label>
            <div class="col-sm-8 mb-3">
                <input class="form-control" id="user_login" name="user_login" value="admin">
            </div>

            <label class="col-form-label col-sm-3" for="user_pw">password</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="password" class="form-control" id="user_pw" name="user_pw">
                    <div class="input-group-text">
                     <input type="checkbox" class="form-check-input" aria-label="Checkbox for following text input" onclick="return show_hide_password(this);">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 my-5">
            <button class="btn btn-lg btn-outline-primary" type="submit">Sign in</button>
        </div>

        <div class="d-grid gap-2 mb-3">
            <figure class="text-end">
                <blockquote class="blockquote">
                    <p>Радость и веселье, отовсюду смех, мы вчера казнили недовольных всех...
                </blockquote>
                <figcaption class="blockquote-footer">edited by <cite title="Source Title">©antaNT64, 2024</cite></figcaption>
            </figure>
        </div>
    </form>
</div>
<script>
function show_hide_password(t) {
    var e = document.getElementById("user_pw");
    return "password" == e.getAttribute("type") ? (t.classList.add("view"), e.setAttribute("type", "text")) : (t.classList.remove("view"), e.setAttribute("type", "password")), !1
}
</script>';
const ERROR_FORBIDDEN_LOGIN = '<!doctype html>
<html lang="ru">
<meta charset="utf-8">
<!--[if IE]><meta content="IE=edge,chrome=1"http-equiv=X-UA-Compatible><![endif]-->
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width,initial-scale=1" name="viewport">
<link href="/media/favicon.ico" rel="shortcut icon" type="image/x-icon">
<title>Welcome to ' . SITE_URL . '</title>
<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.min.css">
<div class=container-fluid style="max-width:540px">
    <img alt="nix.by" class="d-block mx-auto rounded" src="' . LOGO256 . '" style="background-color:#fff;margin:60px">
    <form id="aushform" method="post">
        <div class="row my-5">
            <label class="col-form-label col-sm-3" for="user_login">login</label>
            <div class="col-sm-8 mb-3">
                <input class="form-control is-invalid" id="user_login" name="user_login" value="admin">
            </div>

            <label class="col-form-label col-sm-3" for="user_pw">password error</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="password" class="form-control is-invalid" id="user_pw" name="user_pw">
                    <div class="input-group-text">
                     <input type="checkbox" class="form-check-input is-invalid" aria-label="Checkbox for following text input" onclick="return show_hide_password(this);">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 my-5">
            <button class="btn btn-lg btn-outline-primary" type="submit">Sign in</button>
        </div>

        <div class="d-grid gap-2 mb-3">
            <figure class="text-end">
                <blockquote class="blockquote">
                    <p>Радость и веселье, отовсюду смех, мы вчера казнили недовольных всех...
                </blockquote>
                <figcaption class="blockquote-footer">edited by <cite title="Source Title">©antaNT64, 2024</cite></figcaption>
            </figure>
        </div>
    </form>
</div>
<script>
function show_hide_password(t) {
    var e = document.getElementById("user_pw");
    return "password" == e.getAttribute("type") ? (t.classList.add("view"), e.setAttribute("type", "text")) : (t.classList.remove("view"), e.setAttribute("type", "password")), !1
}
</script>';
