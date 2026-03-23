// uiAdmin.js

export const current_sub_id = getUrlComponent('sub', checkOnUrl(document.location.href));
export const current_dpt_id = getUrlComponent('dpt', checkOnUrl(document.location.href));

export const elH1 = document.querySelector("[data-id='PageH1']");
export const elTemplateNow = document.querySelector("[data-id='template-now']");
export const arrowUp = document.querySelectorAll("[name='arrowUp']");
export const arrowDown = document.querySelectorAll("[name='arrowDown']");

// ## ofcanvas
export const elOffcanvasMenu = document.querySelector('div#offcanvasMenu');
export const bsOffcanvasMenu = (elOffcanvasMenu)?new bootstrap.Offcanvas(elOffcanvasMenu):{};
export const btnCloseMenu = document.querySelector('button#btnCloseMenu');

export const btnOpenMenu = document.querySelector('a[data-bs-toggle="offcanvas"][data-bs-target="#offcanvasMenu"]');
export const switcherMenuOnLoad = document.querySelector('input#switcherMenuOnLoad');
export const switcherMenuPosition = document.querySelector('input#switcherMenuPosition');

export const offcanvasElementList = document.querySelectorAll('.offcanvas');
export const offcanvasList = [...offcanvasElementList].map(offcanvasEl => new bootstrap.Offcanvas(offcanvasEl));
// ## ofcanvas

export const allowedToastPlacementsList = document.querySelector('ul#allowedToastPlacementsList');
export const switcherOnlyErrorsToastShow = document.querySelector('input#switcherOnlyErrorsToastShow');
export const IS_ALLOW_SUCCESS_TOASTS_SHOW = switcherOnlyErrorsToastShow.checked;

// alert(import.meta.url); // ссылка на html страницу для встроенного скрипта

// <a class="navbar-brand" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
// <img alt="nix.by logo" src="/media/logo64.png" height="32"> antCMS.os</a>

// Indeterminate checkbox example in docs and StackBlitz
// для неопределенных чекбоксов добавляем класс indeterminate-checkbox и Indeterminate в аттрибут шв
export const indeterminate = document.querySelectorAll('input.indeterminate[type="checkbox"]')
    .forEach(checkbox => {
        // if (checkbox.id.includes('Indeterminate')) {
        checkbox.indeterminate = true
        // console.log("indeterminate");
        // }
    });









// import Noty from '../../../vendor/needim/noty/lib/noty.js';


// or

// const Noty = require('noty');

// new Noty({
//     text: 'Notification text'
// }).show();