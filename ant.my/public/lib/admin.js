import * as formatters from '../js/formatters.js';
import * as ui from '../js/userInterface.js';
// import { currentSub, primaryKey } from '../js/uiElements.js';

// console.log(currentSub);

export const currentSub = document.querySelector('input#current_sub').value;
export const primaryKey = document.querySelector('input#primary_key').value;


/*document.addEventListener('click', function (e) {
    if (e.target.classList.contains('js-confirm-action')) {
        if (!confirm('Вы уверены?')) {
            e.preventDefault();
        }
    }
});


document.addEventListener('submit', function(e) {
    if (e.target.classList.contains('js-confirm-submit')) {
        const message = e.target.getAttribute('data-message') || 'Вы уверены?';
        if (!confirm(message)) {
            e.preventDefault();
        }
    }
});*/
/*

// Функция-обертка для вызова модалки
function showConfirm(message) {
    const modal = document.getElementById('custom-confirm');
    const msgElem = document.getElementById('confirm-message');
    const yesBtn = document.getElementById('confirm-yes');
    const noBtn = document.getElementById('confirm-no');

    msgElem.textContent = message || 'Вы уверены?';
    modal.style.display = 'flex';

    return new Promise((resolve) => {
        const handleResponse = (result) => {
            modal.style.display = 'none';
            yesBtn.removeEventListener('click', onYes);
            noBtn.removeEventListener('click', onNo);
            resolve(result);
        };

        const onYes = () => handleResponse(true);
        const onNo = () => handleResponse(false);

        yesBtn.addEventListener('click', onYes);
        noBtn.addEventListener('click', onNo);
    });
}

// Обработчик кликов и форм
document.addEventListener('click', async function (e) {
    const target = e.target.closest('.js-confirm-action'); // Улучшено: сработает и на иконке внутри кнопки
    if (target) {
        e.preventDefault(); // Сначала всегда отменяем
        const confirmed = await showConfirm();
        if (confirmed) {
            // Если подтвердили — выполняем действие (например, переход по ссылке)
            window.location.href = target.href || window.location.href;
        }
    }
});

document.addEventListener('submit', async function (e) {
    if (e.target.classList.contains('js-confirm-submit')) {
        if (!e.target.dataset.confirmed) { // Проверяем, не подтверждена ли уже форма
            e.preventDefault();
            const message = e.target.getAttribute('data-message');
            const confirmed = await showConfirm(message);
            if (confirmed) {
                e.target.dataset.confirmed = "true"; // Ставим метку
                e.target.submit(); // Отправляем форму заново
            }
        }
    }
});
*/


// Инициализируем модалку Bootstrap один раз
const confirmModalElem = document.getElementById('confirmModal');
const bsConfirmModal = new bootstrap.Modal(confirmModalElem);
const confirmBtn = document.getElementById('confirmModalTrue');
const confirmLabel = document.getElementById('confirmModalLabel');

// Универсальная функция вызова
function askConfirmation(message) {
    if (message) confirmLabel.textContent = message;
    bsConfirmModal.show();

    return new Promise((resolve) => {
        confirmBtn.onclick = () => {
            bsConfirmModal.hide();
            resolve(true);
        };
        confirmModalElem.addEventListener('hidden.bs.modal', () => resolve(false), { once: true });
    });
}

// 1. Обработка кликов (ссылки/кнопки)
document.addEventListener('click', async (e) => {
    const target = e.target.closest('.js-confirm-action');
    if (target) {
        e.preventDefault();
        if (await askConfirmation(target.dataset.message)) {
            // Если это ссылка — переходим, если кнопка — можно добавить свою логику
            if (target.href) window.location.href = target.href;
        }
    }
});

// 2. Обработка форм
document.addEventListener('submit', async (e) => {
    const form = e.target;
    if (form.classList.contains('js-confirm-submit') && !form.dataset.confirmed) {
        e.preventDefault();
        const msg = form.getAttribute('data-message');
        if (await askConfirmation(msg)) {
            form.dataset.confirmed = "true";
            form.submit();
        }
    }
});
