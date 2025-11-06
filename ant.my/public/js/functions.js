import * as uiFunctions from '../js/uiFunctions.js';
import * as formatters from '../js/formatters.js';

const getDataRow = function (event, params, removePrimaryKey = true) {
    let data = {
        currentSub: params.currentSub,
        primaryKey: params.primaryKey,
        action: event.currentTarget.dataset.action.toLowerCase(),
        index: event.currentTarget.dataset.index,
    };

    let rowData = [];
    // if (data.action && data.action.toLowerCase() === 'addnew') {
    if (data.action?.toLowerCase() === 'addnew' && data.index === '-1') {
        rowData = uiFunctions.getTableRowData(data.index, 'secondTable');
        if (removePrimaryKey) {
            delete rowData[`add_${params.primaryKey}`];
        }
        data.rowData = rowData;
    }

    if (data.action?.toLowerCase() === 'saverow' && +data.index > 0) {
        rowData = uiFunctions.getTableRowData(data.index, 'mainTable');
        data.rowData = rowData;
    }
    return data;
};

const getDataAll = function (event, params) {
    let data = {
        currentSub: params.currentSub,
        primaryKey: params.primaryKey,
        action: event.currentTarget.dataset.action.toLowerCase(),
        index: event.currentTarget.dataset.index,
    };
    if (data.action?.toLowerCase() === 'saveall') {
        let rowData = uiFunctions.getAllTableData('mainTable');
        data.allData = rowData;
    }
    return data;
};

const getUrl = function (event, data) {
    let url = `/admin/action/sub/${data.currentSub}/${data.action}`;
    if (data.primaryKey?.toString() !== '' && data.index && data.index > 0) {
        url = url + `/${data.index}`;
    }

    return url;
};

export const fetchData = function (event, params) {
    let data = {};
    let dataset = event.currentTarget.dataset;
    let action = dataset ? dataset.action.toLowerCase() : 'saveall';

    if (action == 'saveall') {
        data = getDataAll(event, params);
    } else if (action == 'addnew') {
        data = getDataRow(event, params, true);
    } else {
        data = getDataRow(event, params);
    }

    let url = getUrl(event, data);

    send(url, data);
};

async function send(url, data) {
    let defaultResponse = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify(data),
    });

    let result = await defaultResponse.json();

    if (result && data.action?.toLowerCase() === 'saverow') {
        const buttons = document.querySelectorAll(`[data-index="${data.index}"][data-action="SaveRow"]`);
        buttons.forEach(uiFunctions.setToDisabled); // Применяем функцию ко всем найденным кнопкам
        // alert('TADAAM!');
    }
    if (result && data.action?.toLowerCase() === 'saveall') {
        const buttonsAll = document.querySelectorAll(`button[data-action="SaveAll"]`);
        buttonsAll.forEach(uiFunctions.setToDisabled); // Применяем функцию ко всем найденным кнопкам
        // alert('TADAAM!');
    }

    console.log(result);

    if (result.error) return alert(result.error);
    if (result.redirect) window.location.href = result.redirect;
}
