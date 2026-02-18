import * as formatters from '../js/formatters.js';
import * as ui from '../js/userInterface.js';
// import { currentSub, primaryKey } from '../js/uiElements.js';

// console.log(currentSub);
/**
 * Функция управления таймером сессии
 * @param {number} secondsLeft - количество секунд до истечения
 * @param {string} redirectUrl - куда отправить пользователя
 */

let sessionInterval = null; // Переменная внутри модуля, скрыта от global scope


/**
 * Функция пинга сервера для продления сессии
 */
async function pingServer() {
        try {
                const response = await fetch('/admin/ping');
                const data = await response.json();
                console.log('Сессия продлена на сервере:', data.time);
                return true;
        } catch (e) {
                console.error('Ошибка пинга:', e);
                return false;
        }
}

export function initSessionTimer(secondsLeft, redirectUrl = '/login?error=Session+Expired') {
        // Очищаем предыдущий таймер, если он был запущен
        if(sessionInterval) clearInterval(sessionInterval);

        const minutesLeft = +secondsLeft / 60;
        console.log('Таймер запущен на ' + minutesLeft + ' мин. Редирект на: ' + redirectUrl);

        const initialSeconds = secondsLeft; // Запоминаем исходный таймаут

        const tick = () => {
                secondsLeft--;
                if(secondsLeft <= 0) {
                        clearInterval(sessionInterval);
                        window.location.href = redirectUrl;
                }
        };

        sessionInterval = setInterval(tick, 1000);

        // Продление сессии при активности (throttle 30 сек)
        let lastPing = Date.now();
        const handleActivity = async () => {
                const now = Date.now();
                // Пингуем не чаще чем раз в 30 секунд, чтобы не спамить
                if(now - lastPing > 30000) {
                        const success = await pingServer();
                        if(success) {
                                secondsLeft = initialSeconds; // Сбрасываем локальный таймер
                                lastPing = now;
                        }
                }
        };

        // Слушаем движения мыши и нажатия клавиш
        window.addEventListener('mousemove', handleActivity);
        window.addEventListener('keydown', handleActivity);
}


export function initSessionTimerOLD(secondsLeft, redirectUrl = '/login?error=Session+Expired') {
        // Очищаем предыдущий таймер, если он был запущен
        if(sessionInterval) clearInterval(sessionInterval);

        // Если по какой-то причине пришел 0 или пустая строка, не запускаем
        if(!secondsLeft || secondsLeft <= 0) {
                console.warn('Таймер не запущен: некорректное время');
                // Опционально: сразу редирект, если сессия уже мертва
                window.location.href = redirectUrl;
                return;
        }
        const minutesLeft = +secondsLeft / 60;
        console.log('Таймер запущен на ' + minutesLeft + ' мин. Редирект на: ' + redirectUrl);

        sessionInterval = setInterval(() => {
                secondsLeft--;

                if(secondsLeft % 60 === 0 || secondsLeft <= 60) {
                        console.log(`До выхода: ${secondsLeft} сек.`);
                }

                if(secondsLeft <= 0) {
                        clearInterval(sessionInterval);
                        alert('Время вашей сессии истекло.');
                        window.location.href = redirectUrl;
                }
        }, 1000);
}



export const currentSub = document.querySelector('input#current_sub').value;
export const primaryKey = document.querySelector('input#primary_key').value;