// import { getAllowToShowSuccessToasts() } from '../../../admin/js/ui/uiAdmin.js';

const btnSuccess = document.getElementById("btnSuccess");
const btnDanger = document.getElementById("btnDanger");
const btnShowToast = document.getElementById("btnShowToast");
const toastContainer = document.getElementById('toast-container');

const ErrorToastEl = document.querySelector('#myError');
const SuccessToastEl = document.querySelector('#mySuccess');
const myInvalidFormEl = document.querySelector('#myInvalidForm');

const toastElList = document.querySelectorAll('.toast'); //  все html класса тост
const timeElements = document.querySelectorAll('.showTime');


const getAllowToShowSuccessToasts = () => {

    let res = false;
    // const switcherOnlyErrorsToastShow = document.querySelector('input#switcherOnlyErrorsToastShow');
    // const res = (!switcherOnlyErrorsToastShow.checked) ? true : false;
    let switcherOnlyErrorsToastShow = localStorage.getItem('switcherOnlyErrorsToastShow');
    if (switcherOnlyErrorsToastShow === "1") {
        res = false;
    } else {
        res = true;
    }
    return res;
}

const IS_ALLOW_DANGER = true;

let htmlMessage = '';
const option = {};


// Create toast instance
const myError = new bootstrap.Toast(ErrorToastEl);
const mySuccess = new bootstrap.Toast(SuccessToastEl);
const myInvalidForm = new bootstrap.Toast(myInvalidFormEl);

const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, option)); // массив объекьов

const showError = function(text) {
    htmlMessage = text;
    if (IS_ALLOW_DANGER) myError.show();
}
const showSuccess = function(text) {
    htmlMessage = text;
    if (getAllowToShowSuccessToasts()) mySuccess.show();
}
const showInvalid = function(text) {
    htmlMessage = text;
    if (getAllowToShowSuccessToasts()) myInvalidForm.show();
}

function showToast(text = "ok", headerText = "", style = "") {

    let toastEl = document.createElement('div');
    toastEl.classList.add('toast');
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');

    if (headerText) {
        let toastHeader = document.createElement('div');
        toastHeader.classList.add('toast-header');


        if (style == "success") {
            toastHeader.classList.add('text-bg-success');
        } else if (style == "danger") {
            toastHeader.classList.add('text-bg-danger');
        } else {
            toastHeader.classList.add('text-bg-dark');
        }


        let strongEl = document.createElement('strong');
        // <strong class="me-auto">Bootstrap</strong>
        strongEl.classList.add('me-auto');
        strongEl.innerText = headerText;
        toastHeader.appendChild(strongEl);

        let btnClose = document.createElement('button');

        btnClose.classList.add('btn-close', 'btn-close-white', 'me-2', 'm-auto');
        btnClose.setAttribute('type', 'button');
        btnClose.setAttribute('data-bs-dismiss', 'toast');
        btnClose.setAttribute('aria-label', 'Close');

        if (btnClose) {
            toastHeader.appendChild(btnClose);
        }
        toastEl.appendChild(toastHeader);
    }

    let toastBody = document.createElement('div');
    toastBody.classList.add('toast-body');
    toastBody.innerText = text;


    toastEl.appendChild(toastBody);
    toastContainer.appendChild(toastEl);

    let bootstrapToast = new bootstrap.Toast(toastEl);
    if (getAllowToShowSuccessToasts()) bootstrapToast.show();
}


// const  getAllowToShowSuccessToasts()  = false;
document.addEventListener("DOMContentLoaded", function() {

    btnDanger.addEventListener("click", function() {
        // myError.show();
        showError("myError");
    });

    btnSuccess.addEventListener("click", function() {
        // mySuccess.show();
        showSuccess("mySuccess");
    });

    btnShowToast.addEventListener("click", function() {
        showToast('function showToast (text) ', ' пример сообщения ' + '<i class="bi bi-bug-fill"></i>', 'danger');
    });

    /* выводим СВОЁ время выполнения для всех toastElList*/
    [].forEach.call(toastElList, function(El) {
        El.addEventListener('show.bs.toast', () => {
            // console.info( htmlMessage );
            let thisTimeEl = El.querySelector('.showTime');
            let thisMessage = El.querySelector('.message-body');
            let today = new Date();
            thisTimeEl.innerHTML = formatTime(today);
            if (htmlMessage) thisMessage.innerHTML = htmlMessage;
        });
    });


});

export {
    showError,
    showSuccess,
    showInvalid,
    showToast,
    toastElList,
    toastList
}