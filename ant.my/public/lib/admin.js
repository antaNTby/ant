import * as formatters from '../js/formatters.js';
import * as ui from '../js/userInterface.js';
// import { currentSub, primaryKey } from '../js/uiElements.js';

// console.log(currentSub);
/**
 * Функция управления таймером сессии
 * @param {number} secondsLeft - количество секунд до истечения
 * @param {string} redirectUrl - куда отправить пользователя
 */
let sessionInterval = null;
let bsToast = null;

// Внутренняя функция для связи с сервером
async function pingServer() {
        try {
                const response = await fetch('/api/admin/ping');
                if(!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                return data.status === 'success';
        } catch (error) {
                console.error('Ping failed:', error);
                return false;
        }
}

/**
 * Инициализация таймера сессии
 * @param {number} secondsLeft - время жизни сессии в секундах
 * @param {string} redirectUrl - куда отправить при логауте
 */
export function initSessionTimer(secondsLeft, redirectUrl = '/login?error=Session+Expired') {
        // Сброс старого интервала, если функция вызвана повторно
        if(sessionInterval) clearInterval(sessionInterval);

        const initialSeconds = +secondsLeft;
        const minutesLeft = +secondsLeft / 60;
        console.log('Таймер запущен на ' + minutesLeft + ' мин. Редирект на: ' + redirectUrl);
        console.log('Таймер запущен на ' + formatters.formatSessionTime(initialSeconds));

        const toastEl = document.getElementById('sessionToast');
        if(toastEl && typeof bootstrap !== 'undefined') {
                bsToast = new bootstrap.Toast(toastEl, { autohide: false });
        }

        let currentSeconds = initialSeconds;
        const events = ['keydown', 'mousemove', 'touchstart', 'scroll'];
        let isListenersActive = false;

        // Функция, которая сбросит таймер при активности
        const resetTimer = async () => {
                const success = await pingServer();

                if(success) {
                        console.log('Сессия продлена успешно.');
                        currentSeconds = initialSeconds;

                        // Прячем уведомление
                        if(bsToast) bsToast.hide();

                        // Удаляем слушатели до тех пор, пока время снова не упадет до 60 сек
                        events.forEach(event => window.removeEventListener(event, resetTimer));
                        isListenersActive = false;
                }
        };

        sessionInterval = setInterval(() => {
                currentSeconds--;

                // 1. Когда осталась 1 минута - начинаем следить за активностью
                if(currentSeconds <= 60 && !isListenersActive) {
                        console.warn('Осталась минута! Ждем активности пользователя.');

                        if(bsToast) bsToast.show(); // Показываем Bootstrap Toast

                        events.forEach(event => {
                                window.addEventListener(event, resetTimer, { passive: true });
                        });
                        isListenersActive = true;
                }

                // 2. Время вышло - редирект
                if(currentSeconds <= 0) {
                        clearInterval(sessionInterval);
                        // На всякий случай чистим события
                        events.forEach(event => window.removeEventListener(event, resetTimer));

                        alert('Время вашей сессии истекло.');
                        window.location.href = redirectUrl;
                }
        }, 1000);
}




export const currentSub = document.querySelector('input#current_sub').value;
export const primaryKey = document.querySelector('input#primary_key').value;