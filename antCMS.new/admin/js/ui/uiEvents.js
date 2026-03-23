import * as ui from './uiAdmin.js';
// alert(import.meta.url); // ссылка на html страницу для встроенного скрипта
// События


//offcanvas menu
ui.switcherMenuOnLoad.addEventListener('change', function(e) {
    // console.log(e);
    if (e.target.checked) {
        localStorage.setItem('switcherMenuOnLoad', "1");
    } else {
        localStorage.setItem('switcherMenuOnLoad', "0");
    }
});
ui.switcherMenuPosition.addEventListener('change', function(e) {
    // console.log(e);
    if (e.target.checked) {
        localStorage.setItem('switcherMenuPosition', "1");
    } else {
        localStorage.setItem('switcherMenuPosition', "0");
    }
});

ui.switcherOnlyErrorsToastShow.addEventListener('change', function(e) {
    // console.log(e);
    if (e.target.checked) {
        localStorage.setItem('switcherOnlyErrorsToastShow', "1");
    } else {
        localStorage.setItem('switcherOnlyErrorsToastShow', "0");
    }
});

ui.btnCloseMenu.addEventListener('click', () => {
    ui.bsOffcanvasMenu.hide();
});

document.addEventListener("DOMContentLoaded", function() {
    // console.log(ui)
    let switcherMenuOnLoad = localStorage.getItem('switcherMenuOnLoad');
    if (switcherMenuOnLoad === "1") {
        ui.bsOffcanvasMenu.show();
        ui.switcherMenuOnLoad.checked = true;
    } else {
        ui.bsOffcanvasMenu.hide();
        ui.switcherMenuOnLoad.checked = undefined;
    }

    let switcherOnlyErrorsToastShow = localStorage.getItem('switcherOnlyErrorsToastShow');
    if (switcherOnlyErrorsToastShow === "1") {
        ui.switcherOnlyErrorsToastShow.checked = true;
    } else {
        ui.switcherOnlyErrorsToastShow.checked = undefined;
    }

    let switcherMenuPosition = localStorage.getItem('switcherMenuPosition');
    if (switcherMenuPosition === "1") {
        ui.elOffcanvasMenu.classList.remove('offcanvas-start');
        ui.elOffcanvasMenu.classList.add('offcanvas-end');
        ui.switcherMenuPosition.checked = true;
    } else {
        ui.elOffcanvasMenu.classList.add('offcanvas-start');
        ui.elOffcanvasMenu.classList.remove('offcanvas-end');
        ui.switcherMenuPosition.checked = undefined;
    }

});


// скролл страницы
window.addEventListener('scroll', function() {
    [].forEach.call(ui.arrowUp, function(El) {
        El.disabled = (pageYOffset < 100);
    });
    [].forEach.call(ui.arrowDown, function(El) {
        El.disabled = (pageYOffset > (document.body.scrollHeight - 100));
    });
    // console.log(pageYOffset)
});
[].forEach.call(ui.arrowUp, function(El) {
    El.onclick = scrolToMyTop;
    El.disabled = (pageYOffset < 100);
});
[].forEach.call(ui.arrowDown, function(El) {
    El.onclick = scrolToMyBottom;
    El.disabled = (pageYOffset > (document.body.scrollHeight - 100));
});