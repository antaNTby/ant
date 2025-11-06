import * as formatters from '../js/formatters.js';

export function getTableRowData(rowIndex, tableID = 'mainTable') {
    let row_data = {};
    for (let table of document.querySelectorAll(`table#${tableID}`)) {
        for (let tr of table.querySelectorAll(`tr[data-index="${rowIndex}"]`)) {
            for (let cell of tr.querySelectorAll('td[data-dt-name]')) {
                let name = cell.getAttribute('name');
                let input = cell.querySelector(
                    'input.smarty-control, select.smarty-control, textarea.smarty-control',
                );
                if (name && input) {
                    row_data[name] = input.value;
                }
            }
        }
    }
    return row_data;
}

export function getAllTableData(tableID = 'mainTable') {
    let data = {};
    for (let table of document.querySelectorAll(`table#${tableID}`)) {
        for (let tr of table.querySelectorAll(`tr[data-index]`)) {
            let ind = tr.dataset.index?.toString();
            let row_data = {};
            for (let cell of tr.querySelectorAll('td[data-dt-name]')) {
                let name = cell.getAttribute('name');
                let input = cell.querySelector(
                    'input.smarty-control, select.smarty-control, textarea.smarty-control',
                );
                if (name && input) {
                    row_data[name] = input.value;
                }
            }
            data[`${ind}`] = row_data;
            row_data = {};
        }
    }

    console.table(data);
    return data;
}

// export function UpdateHelperNoAI(event) {
//     let helperName = event.target.name;
//     let value = event.target.value;
//     const values = [value * 1.2, value, value / 1.2];
//     const formattedValues = values.map((val) => formatters.formatUsd(val, 4, '.', ','));

//     const str = helperName;
//     const parts = str.split(/[\[\]]/);
//     const obj = { fieldName: parts[0], id: Number(parts[1]) };

//     const container = document.querySelector(`div[data-helper="${obj.fieldName}"][data-index="${obj.id}"]`);
//     const sup = container?.querySelector('sup');
//     const sub = container?.querySelector('sub');

//     sup.innerText = formattedValues[0].toString();
//     sub.innerText = formattedValues[2].toString();

//     event.target.value = formatters.formatUsd(formattedValues[1], 4, '.', ',');
// }

export function UpdateHelper(event) {
    let helperName = event.target.name;
    let value = parseFloat(event.target.value); // Явное приведение к числу

    // Проверяем, является ли value числом
    if (isNaN(value)) return;

    // Генерируем массив значений
    const values = [value * 1.2, value, value / 1.2];
    const formattedValues = values.map((val) =>
        formatters.formatUsd(val, 4, '.', ','),
    );

    // Разбираем имя поля и индекс
    const parts = helperName.split(/[\[\]]/);
    if (parts.length < 2) return; // Проверка корректности формата

    const obj = { fieldName: parts[0], id: Number(parts[1]) };

    // Находим контейнер и его элементы
    const container = document.querySelector(
        `div[data-helper="${obj.fieldName}"][data-index="${obj.id}"]`,
    );
    if (!container) return; // Проверяем, существует ли container

    const sup = container.querySelector('sup');
    const sub = container.querySelector('sub');

    // Обновляем значения только если элементы существуют
    if (sup) sup.innerText = formattedValues[0];
    if (sub) sub.innerText = formattedValues[2];

    // Обновляем значение input
    event.target.value = formattedValues[1];
}

export const setToEnable = (btn) => {
    if (!btn) return;

    btn.removeAttribute('disabled'); // Убираем disabled
    btn.classList.replace('btn-outline-secondary', 'btn-success'); // Заменяем класс

    // Эффект мигания дважды
    for (let i = 1; i <= 2; i++) {
        setTimeout(() => {
            btn.classList.toggle('btn-success');
        }, i * 200);
    }
};

export const setToDisabled = (btn) => {
    if (!btn) return;

    btn.setAttribute('disabled', 'true'); // Устанавливаем disabled

    setTimeout(() => {
        btn.classList.replace('btn-success', 'btn-outline-secondary'); // Заменяем класс
    }, 600); // Задержка в 0,6 сек
};

export function debounce(callBack, delay = 1000) {
    let timeout;

    return (arg) => {
        clearInterval(timeout);

        timeout = setTimeout(() => {
            callBack(arg);
        }, delay);
    };
}

export function throttle(callBack, delay = 1000) {
    let isPaused = false;

    return (...arg) => {
        if (isPaused) return;

        callBack(...arg);
        isPaused = true;

        setTimeout(() => {
            isPaused = false;
        }, delay);
    };
}
