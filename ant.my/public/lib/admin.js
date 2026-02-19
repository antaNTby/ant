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

export function initSessionTimer(secondsLeft, redirectUrl = '/login?error=Session+Expired') {
    if(sessionInterval) clearInterval(sessionInterval);

    if(!secondsLeft || secondsLeft <= 0) {
        window.location.href = redirectUrl;
        return;
    }

    const initialSeconds = +secondsLeft;
    let currentSeconds = initialSeconds;
    const events = ['keydown', 'mousemove', 'touchstart', 'scroll'];
    let isListenersActive = false; // Флаг, чтобы не вешать слушатели многократно

    console.log('Таймер запущен на ' + initialSeconds + ' сек.');

    const resetTimer = () => {
        console.log('Активность зафиксирована! Таймер продлен.');
        currentSeconds = initialSeconds;

        // После сброса удаляем слушатели, пока время снова не упадет до 60 сек
        events.forEach(event => window.removeEventListener(event, resetTimer));
        isListenersActive = false;
    };

    sessionInterval = setInterval(() => {
        currentSeconds--;

        // 1. Если осталось 60 сек и слушатели еще не включены — включаем их
        if (currentSeconds <= 60 && !isListenersActive) {
            console.log('Внимание: осталась 1 минута. Начните активность для продления.');
            events.forEach(event => {
                window.addEventListener(event, resetTimer, { passive: true });
            });
            isListenersActive = true;
        }

        // Логирование (каждые 10 сек в последнюю минуту)
        if (currentSeconds <= 60 && currentSeconds % 10 === 0) {
            console.log(`До выхода: ${currentSeconds} сек.`);
        }

        // 2. Время вышло
        if (currentSeconds <= 0) {
            clearInterval(sessionInterval);
            if (isListenersActive) {
                events.forEach(event => window.removeEventListener(event, resetTimer));
            }
            alert('Время вашей сессии истекло.');
            window.location.href = redirectUrl;
        }
    }, 1000);
}



export const currentSub = document.querySelector('input#current_sub').value;
export const primaryKey = document.querySelector('input#primary_key').value;