/**
 * Функция пинга сервера для продления сессии
 */

async function pingServer() {
        try {
                const response = await fetch('api/admin/ping');

                // Если сервер ответил редиректом или ошибкой
                if(!response.ok || response.redirected) {
                        window.location.href = '/login?error=Session+Expired';
                        return false;
                }

                const contentType = response.headers.get("content-type");
                if(!contentType || !contentType.includes("application/json")) {
                        // Пришел HTML (страница логина) вместо JSON
                        window.location.href = '/login?error=Session+Expired';
                        return false;
                }

                const data = await response.json();
                console.log('Сессия продлена:', data.time);
                return true;
        } catch (e) {
                console.error('Критическая ошибка пинга:', e);
                // Не делаем редирект сразу, может просто инет моргнул
                return false;
        }
}


export function initSessionTimerWithPing(secondsLeft, redirectUrl = '/login?error=Session+Expired') {
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
                // Пингуем не чаще чем раз в 600 секунд, чтобы не спамить
                if(now - lastPing > 2 * 24 * 60 * 60) {
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