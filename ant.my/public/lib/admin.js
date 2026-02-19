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

// Функция для реального пинга сервера
async function pingServer() {
    try {
        const response = await fetch('/api/admin/ping');
        const data = await response.json();
        console.log('Сервер подтвердил продление:', data.time);
        return true;
    } catch (e) {
        console.error('Ошибка пинга:', e);
        return false;
    }
}

export function initSessionTimer(secondsLeft, redirectUrl = '/login?error=Session+Expired') {
    if(sessionInterval) clearInterval(sessionInterval);
    if(!secondsLeft || secondsLeft <= 0) { window.location.href = redirectUrl; return; }

    const initialSeconds = +secondsLeft;
    let currentSeconds = initialSeconds;
    const events = ['keydown', 'mousemove', 'touchstart', 'scroll'];
    let isListenersActive = false;

    const resetTimer = async () => {
        // Сначала пробуем продлить сессию на сервере
        const success = await pingServer();

        if (success) {
            console.log('Активность! Сессия продлена на сервере и в браузере.');
            currentSeconds = initialSeconds;

            // Снимаем слушатели, пока время снова не упадет до 60 сек
            events.forEach(event => window.removeEventListener(event, resetTimer));
            isListenersActive = false;
        }
    };

    sessionInterval = setInterval(() => {
        currentSeconds--;

        // Включаем слежку только в последнюю минуту
        if (currentSeconds <= 60 && !isListenersActive) {
            console.log('Осталась 1 минута. Нажмите любую клавишу для продления.');
            events.forEach(event => {
                window.addEventListener(event, resetTimer, { passive: true });
            });
            isListenersActive = true;
        }

        if (currentSeconds <= 0) {
            clearInterval(sessionInterval);
            events.forEach(event => window.removeEventListener(event, resetTimer));
            alert('Время вашей сессии истекло.');
            window.location.href = redirectUrl;
        }
    }, 1000);
}




export const currentSub = document.querySelector('input#current_sub').value;
export const primaryKey = document.querySelector('input#primary_key').value;