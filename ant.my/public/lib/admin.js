import * as formatters from '../js/formatters.js';
// import * as ui from '../js/userInterface.js';
// import { currentSub, primaryKey } from '../js/uiElements.js';

// console.log(currentSub);

// export const currentSub = document.querySelector('input#current_sub').value;
// export const primaryKey = document.querySelector('input#primary_key').value;


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
