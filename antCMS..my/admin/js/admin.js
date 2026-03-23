// admin.js
// глобальные функции, объекты и константы


function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function setSelectValue(id, val) {
    document.getElementById(id).value = val;
}

function zeroPad(num, numZeros) {
    if (num == 0) return "0"
    let an = Math.abs(num);
    let digitCount = 1 + Math.floor(Math.log(an) / Math.LN10);
    if (digitCount >= numZeros) {
        return num.toString();
    }
    let zeroString = Math.pow(10, numZeros - digitCount).toString().substr(1);
    return num < 0 ? '-' + zeroString + an : zeroString + an;
}

function nowLocale() {
    // создаем новый объект `Date`
    let today = new Date();
    // получаем дату и время
    let nowLocale = today.toLocaleString();
    return nowLocale;
}

function formatDateTime(date, $sep = ' ') {
    let hh = date.getHours();
    if (hh < 10) hh = '0' + hh;
    let ii = date.getMinutes();
    if (ii < 10) ii = '0' + ii;
    let ss = date.getSeconds();
    if (ss < 10) ss = '0' + ss;
    let dd = date.getDate();
    if (dd < 10) dd = '0' + dd;
    let mm = date.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;
    // let yy = date.getFullYear() % 100;
    // if (yy < 10) yy = '0' + yy;
    let yy = date.getFullYear();
    let result = yy + '-' + mm + '-' + dd + ' ' + $sep + ' ' + hh + ':' + ii + ':' + ss;
    return result;
}

function formatNowToFileName() {
    // создаем новый объект `Date`
    let date = new Date();
    let hh = date.getHours();
    if (hh < 10) hh = '0' + hh;
    let ii = date.getMinutes();
    if (ii < 10) ii = '0' + ii;
    let ss = date.getSeconds();
    if (ss < 10) ss = '0' + ss;
    let dd = date.getDate();
    if (dd < 10) dd = '0' + dd;
    let mm = date.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;
    // let yy = date.getFullYear() % 100;
    // if (yy < 10) yy = '0' + yy;
    let yy = date.getFullYear();
    let result = yy + '-' + mm + '-' + dd + ' ' + hh + '։' + ii + '։' + ss;
    return result;
}

function formatDate(date, $sep = ' ') {
    let dd = date.getDate();
    if (dd < 10) dd = '0' + dd;
    let mm = date.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;
    // let yy = date.getFullYear() % 100;
    // if (yy < 10) yy = '0' + yy;
    let yy = date.getFullYear();
    let result = yy + '-' + mm + '-' + dd;
    return result;
}

function formatTime(date, $sep = ' ') {
    let hh = date.getHours();
    if (hh < 10) hh = '0' + hh;
    let ii = date.getMinutes();
    if (ii < 10) ii = '0' + ii;
    let ss = date.getSeconds();
    if (ss < 10) ss = '0' + ss;
    let result = hh + ':' + ii + ':' + ss;
    return result;
}

function checkOnUrl(url) {
    let urlArrayDot = url.split('.');
    if (urlArrayDot[urlArrayDot.length - 1] === 'html') {
        urlArrayDot.pop();
        let newUrl = urlArrayDot.join('.');
        let urlArraySlash = newUrl.split('/');
        urlArraySlash.pop();
        newUrl = urlArraySlash.join('/') + '/';
        return newUrl;
    }
    return url;
}


function scrolToMyTop() {
    window.scrollTo(0, 0);
};

function scrolToMyBottom() {
    window.scrollTo(0, document.body.scrollHeight);
};

// функция раскодирования адресной строки
// query string: ?foo=lorem&bar=&baz
// let foo = getUrlComponent('foo'); // "lorem"
// let bar = getUrlComponent('bar'); // "" (present with empty value)
// let baz = getUrlComponent('baz'); // "" (present with no value)
// let qux = getUrlComponent('qux'); // null (absent)
function getUrlComponent(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function deleteUrlComponent(name, url) {
    let new_url;
    if (!url) {
        new_url = new URL(window.location.href);
    } else {
        new_url = new URL(url);
    }
    if (!name) {
        name = 'no_time_filter';
    }
    new_url.searchParams.delete(name);
    return window.history.pushState({}, document.title, new_url);
}