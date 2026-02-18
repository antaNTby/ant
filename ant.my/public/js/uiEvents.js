import { fetchData } from '../js/functions.js';
import * as uiFunctions from '../js/uiFunctions.js';
// import { currentSub, primaryKey, ButtonsSaveAll } from '../js/uiElements.js';
import { ButtonsSaveAll } from '../js/uiElements.js';

export const ActionButtonClick = (event) => {
    event.preventDefault();
    const params = {
        currentSub: currentSub,
        primaryKey: primaryKey,
    };

    // return dataGrabber(event, params);
    fetchData(event, params);
};

export const CheckboxChange = (event) => {
    const el = event.target;
    if (el.checked === true) {
        el.value = 1;
        el.setAttribute('checked', 'checked');
        el.removeAttribute('indeterminate');
        el.classList.remove('indeterminate-checkbox');
        el.checked = true;
        el.indeterminate = false;
    } else if (el.checked === false) {
        el.value = 0;
        el.removeAttribute('checked');
        el.removeAttribute('indeterminate');
        el.classList.remove('indeterminate-checkbox');
        el.checked = false;
        el.indeterminate = false;
    } else {
        el.value = -1;
        el.removeAttribute('checked');
        el.setAttribute('indeterminate', 'indeterminate');
        el.classList.add('indeterminate-checkbox');
        el.indeterminate = true;
    }
    // console.log(event.target.checked + ' ' + el.checked + '   ' + event.target.value + ' ' + el.value);
};

//подсвечиваетм кноки SaveRoW

export const EnableSaveRow = (event) => {
    const id = event.target.name;
    const match = id.match(/\[(\d+)\]/);
    const number = match ? Number(match[1]) : null;
    // Проверяем, что извлекли нужное число из []
    if (!number) return; // Выходим, если число не найдено
    // Находим все кнопки с нужным data-index и data-action
    const buttons = document.querySelectorAll(
        `[data-index="${number}"][data-action="SaveRow"][disabled]`,
    );
    buttons.forEach(uiFunctions.setToEnable); // Применяем функцию ко всем найденным кнопкам
};

export const EnableSaveAll = (event) => {
    ButtonsSaveAll.forEach((btn) => uiFunctions.setToEnable(btn));
};

export const FilteredInputKeydown = (event) => {
    // console.log(event.key);
    const input = event.target;
    const safeValue = input.value;
    input.dataset.safeValue = safeValue;

    const allowedCharsSet1 = new Set([
        '.',
        '-',
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        'Backspace',
        'Delete',
        'ArrowLeft',
        'ArrowUp',
        'ArrowRight',
        'ArrowDown',
        'Escape',
        'Enter',
        'Shift',
        'Control',
        'c',
        'x',
        'v',
        'a',
    ]);
    const allowedCharsSet2 = new Set(
        [...allowedCharsSet1].filter((char) => char !== '.' && char !== '-'),
    );

    const allowedCharsSet = input.value.includes('.')
        ? allowedCharsSet2
        : allowedCharsSet1;

    if (!allowedCharsSet.has(event.key)) {
        event.preventDefault();
        input.value = input.dataset.safeValue;
        console.log(event.key + ' Символ запрещен.');
    } else {
        input.dataset.safeValue = input.value;
    }
};

export const FilteredInputPaste = (event) => {
    const input = event.target;
    const safeValue = input.value;
    const pastedText = event.clipboardData.getDataRow('text');
    const allowedPattern = /^-?\d*\.?\d*$/; // Разрешает числа с точкой и знаком минус
    input.dataset.safeValue = safeValue;
    if (!allowedPattern.test(pastedText)) {
        event.preventDefault();
        input.value = input.dataset.safeValue;
        console.log(
            'Вставка запрещена. Разрешены только числа с точкой и знаком минус',
        );
    }
};

export const FilteredInputCut = (event) => {
    const input = event.target;
    const safeValue = input.value;
    input.dataset.safeValue = safeValue;
    console.log('Текст вырезан: ', document.getSelection().toString());
};

export const FilteredInputCopy = (event) => {
    const input = event.target;
    const safeValue = input.value;
    input.dataset.safeValue = safeValue;
    console.log('Текст скопирован: ', document.getSelection().toString());
};

export const CurrencyValueChange = (event) => {
    uiFunctions.UpdateHelper(event);
};
