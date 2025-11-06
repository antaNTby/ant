import * as uiElem from '../js/uiElements.js';
import * as uiEvent from '../js/uiEvents.js';
import * as uiFunctions from '../js/uiFunctions.js';

/*
фильтруем нажатые клавиши
*/

const filteredInput = (el) => {
    el.classList.add(
        'text-success',
        'border',
        'border-success',
        'border-opacity-50',
    );
    el.setAttribute('title', 'Только числа с точкой и знаком минус');
    el.addEventListener('keydown', (event) =>
        uiEvent.FilteredInputKeydown(event),
    );
    el.addEventListener('paste', (event) => uiEvent.FilteredInputPaste(event));
    el.addEventListener('cut', (event) => uiEvent.FilteredInputCut(event));
    el.addEventListener('copy', (event) => uiEvent.FilteredInputCopy(event));
};

/*
Indeterminate checkbox example in docs and StackBlitz
для неопределенных чекбоксов добавляем класс indeterminate-checkbox
*/

const indeterminateCheckbox = (checkbox) => {
    checkbox.indeterminate = true;
    checkbox.value = -1;
    checkbox.removeAttribute('checked');
    checkbox.setAttribute('indeterminate', 'indeterminate');
    checkbox.classList.add('indeterminate-checkbox');
    console.log('indeterminate ' + checkbox.id);
};

/*
ДЛЯ ВСЕХ чекбоксов на сайте задаем поведение
*/
uiElem.allCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', uiEvent.CheckboxChange);
});

uiElem.indeterminateCheckboxes.forEach((checkbox) =>
    indeterminateCheckbox(checkbox),
);

/*
ДЛЯ цифровых иппутов смартиконтрол  на сайте задаем поведение
*/
uiElem.InputsNumber.forEach((el) => filteredInput(el));

uiElem.ButtonsAll.forEach((btn) =>
    btn.addEventListener('click', (event) => uiEvent.ActionButtonClick(event)),
);

// console.log(uiElem.InputsNumber);

/*
назначаем интерфейс для всех currency_value
*/
const currencyValueInputs = [...document.querySelectorAll('input')].filter(
    (input) => input.id.includes('currency_value'),
);
currencyValueInputs.forEach((input) =>
    input.addEventListener('change', (event) => {
        event.target.setAttribute(
            'title',
            'Только положительные числа с точкой',
        );
        uiEvent.CurrencyValueChange(event);
    }),
);

uiElem.InputsAll.forEach((input) => {
    input.addEventListener('change', uiEvent.EnableSaveRow);
    input.addEventListener('change', uiEvent.EnableSaveAll);
});

const updateDebounce = uiFunctions.debounce((aaa) => {
    uiEvent.EnableSaveRow(aaa);
    uiEvent.EnableSaveAll(aaa);
});

uiElem.InputsAll.forEach((input) => {
    input.addEventListener('input', (event) => {
        updateDebounce(event);
        updateDebounce(event);
    });
});

uiElem.InputsUnp.forEach((input) => {
    input.addEventListener('blur', (event) => {
        const regex = /^\d{3}\s\d{3}\s\d{3}$/;
        if (!regex.test(input.value)) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            console.error(
                'Пожалуйста, введите УНП в формате "123 456 789" в ' + input.id,
            );
            uiElem.ButtonsSaveRow.forEach(uiFunctions.setToDisabled); // Применяем функцию ко всем найденным кнопкам
            uiElem.ButtonsSaveAll.forEach(uiFunctions.setToDisabled); // Применяем функцию ко всем найденным кнопкам
        } else {
            input.classList.remove('is-invalid');
            // input.classList.add('is-valid');
        }
    });
});

uiElem.InputsDateTime.forEach((input) => {
    input.addEventListener('blur', (event) => {
        const regex = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/;
        if (!regex.test(input.value)) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            console.error(
                'Пожалуйста, введите дату в формате YYYY-MM-DD HH:MM:SS в ' +
                    input.id,
            );
            uiElem.ButtonsSaveRow.forEach(uiFunctions.setToDisabled); // Применяем функцию ко всем найденным кнопкам
            uiElem.ButtonsSaveAll.forEach(uiFunctions.setToDisabled); // Применяем функцию ко всем найденным кнопк
        } else {
            input.classList.remove('is-invalid');
            // input.classList.add('is-valid');
        }
    });
});

uiElem.CalendarPickers.forEach((doc) => {
    const monthSelect = doc.querySelector('[name="month"]');
    const yearSelect = doc.querySelector('[name="year"]');
    const calendarElem = doc.querySelector('[name="calendar"]');
    const output = doc.querySelector('[name="datetime-output"]');

    console.log({
        monthSelect: monthSelect,
        yearSelect: yearSelect,
        calendarElem: calendarElem,
        output: output,
    });

    for (let m = 0; m < 12; m++) {
        const opt = document.createElement('option');
        opt.value = m;
        opt.text = new Date(0, m).toLocaleString('ru', { month: 'long' });
        monthSelect.add(opt);
    }

    const currentYear = new Date().getFullYear();
    for (let y = currentYear - 10; y <= currentYear + 5; y++) {
        const opt = document.createElement('option');
        opt.value = y;
        opt.text = y;
        yearSelect.add(opt);
    }

    monthSelect.value = new Date().getMonth();
    yearSelect.value = new Date().getFullYear();

    function getDay(date) {
        let day = date.getDay();
        return day === 0 ? 6 : day - 1;
    }

    function formatDate(date) {
        return date.toISOString().slice(0, 10);
    }

    function generateCalendar(elem, year, month) {
        const d = new Date(year, month);
        let table =
            '<table><tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr><tr>';

        for (let i = 0; i < getDay(d); i++) table += '<td></td>';
        while (d.getMonth() === month) {
            const dateStr = formatDate(d);
            table += `<td data-date="${dateStr}">${d.getDate()}</td>`;
            if (getDay(d) % 7 === 6) table += '</tr><tr>';
            d.setDate(d.getDate() + 1);
        }
        table += '</tr></table>';
        elem.innerHTML = table;

        elem.querySelectorAll('td[data-date]').forEach((td) => {
            td.addEventListener('click', () => {
                calendarElem
                    .querySelectorAll('td')
                    .forEach((cell) => cell.classList.remove('selected'));
                td.classList.add('selected');

                const h = String(
                    doc.querySelector('[name="hours"]').value,
                ).padStart(2, '0');
                const m = String(
                    doc.querySelector('[name="minutes"]').value,
                ).padStart(2, '0');
                const s = String(
                    doc.querySelector('[name="seconds"]').value,
                ).padStart(2, '0');
                output.value = `${td.dataset.date} ${h}:${m}:${s}`;
            });
        });
    }

    monthSelect.addEventListener('change', () => {
        generateCalendar(
            calendarElem,
            parseInt(yearSelect.value),
            parseInt(monthSelect.value),
        );
    });

    yearSelect.addEventListener('change', () => {
        generateCalendar(
            calendarElem,
            parseInt(yearSelect.value),
            parseInt(monthSelect.value),
        );
    });

    generateCalendar(
        calendarElem,
        parseInt(yearSelect.value),
        parseInt(monthSelect.value),
    );
});
