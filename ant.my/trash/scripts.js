/**
 * Функция пинга сервера для продления сессии
 */

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
        if (!response.ok) throw new Error('Network response was not ok');
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
    if (sessionInterval) clearInterval(sessionInterval);

    const initialSeconds = +secondsLeft;
    const minutesLeft = +secondsLeft / 60;
    console.log('Таймер запущен на ' + minutesLeft + ' мин. Редирект на: ' + redirectUrl);
    // console.log('Таймер запущен на ' + formatters.formatSessionTime(initialSeconds));

    const toastEl = document.getElementById('sessionToast');
    if (toastEl && typeof bootstrap !== 'undefined') {
        bsToast = new bootstrap.Toast(toastEl, { autohide: false });
    }

    let currentSeconds = initialSeconds;
    const events = ['keydown', 'mousemove', 'touchstart', 'scroll'];
    let isListenersActive = false;

    let isPinging = false; // Флаг для предотвращения множественных запросов

    // Функция, которая сбросит таймер при активности
    const resetTimer = async () => {
        // Если запрос уже идет, ничего не делаем
        if (isPinging) return;

        isPinging = true; // Ставим замок
        const success = await pingServer();

        if (success) {
            console.log('Сессия продлена успешно.');
            currentSeconds = initialSeconds;

            if (bsToast) bsToast.hide();

            events.forEach(event => window.removeEventListener(event, resetTimer));
            isListenersActive = false;
        }

        isPinging = false; // Снимаем замок
    };


    sessionInterval = setInterval(() => {
        currentSeconds--;

        // Добавьте это для отладки
        if (currentSeconds <= 10) console.log('Тик:', currentSeconds);

        // 1. Когда осталась 1 минута - начинаем следить за активностью
        if (currentSeconds <= 60 && !isListenersActive) {
            console.warn('Осталась минута! Ждем активности пользователя.');

            if (bsToast) bsToast.show(); // Показываем Bootstrap Toast

            events.forEach(event => {
                window.addEventListener(event, resetTimer, { passive: true });
            });
            isListenersActive = true;
        }

        // 2. Время вышло - редирект
        if (currentSeconds <= 0) {
            clearInterval(sessionInterval);

            // Чистим события
            events.forEach(event => window.removeEventListener(event, resetTimer));

            // СОЗДАЕМ ЗАТЕМНЕНИЕ
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show'; // Классы Bootstrap 5
            backdrop.style.zIndex = '1055'; // Чуть выше обычных элементов
            document.body.appendChild(backdrop);

            // Скрываем Toast, если он виден
            if (bsToast) bsToast.hide();

            // Небольшая задержка, чтобы глаз заметил затемнение перед блокирующим алерт
            setTimeout(() => {
                alert('Время вашей сессии истекло.');
                window.location.href = redirectUrl;
            }, 100);
        }
    }, 1000);
}
