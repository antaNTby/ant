import * as bsToast from '../../apps/Toasts/appToasts.js';
import * as ui from '../../js/ui/uiAdmin.js';
import katzapskayaMova from './defaultKatzapskayaMova.js';
import * as standartEvents from './dtEvents.js';
import layoutDefault from './dtStandartLayout.js';
const fn = document.getElementById('jsonFN').value;
// console.log('FN=' + fn);
const res = await fetch(checkOnUrl('admin/' + fn));
const dataJsonFile = await res.json();

// console.log(dataJsonFile)
//
const orderBy = [
    [0, 'asc'],
    // [1, 'asc']
];
export function reloadTable(e, dt, button, config) {
    dt.ajax.reload();
}

export const table = new DataTable('#defaultDataTable' + '_' + ui.current_sub_id, {
    ajax: {
        url: checkOnUrl(document.location.href) + '&operation=initDefaultDataTable',
        type: 'POST',
        data: function(d) {
            const postData = {
                // statusID: 2,
                // orderID: document.querySelector('input#thisOrderID').value,
                // currencyValue: getCurrencyValue(),
                // tableID: document.querySelector('select#selectItemSource').value,
                // params: FilterParams(),
            };
            d.DATA = {
                //
                ...postData
                //
            };
        },
    },
    columns: dataJsonFile,
    serverSide: true,
    processing: true,
    ordering: true,
    order: orderBy,
    background: true,
    language: katzapskayaMova,
    // paging: true,
    pageLength: 8,
    searchDelay: 300,
    layout: layoutDefault,
    scrollX: true,
    autowidth: true,
    // select: true,
    rowId: 'id',

    initComplete: function() {
        this.api()
            .columns()
            .every(function() {
                let column = this;

                let startWrapperHtml = '';
                let endWrapperHtml = '';

                let wrapper = '<div class="dropdown">{content}</div>'

                let elements = wrapper.split('{content}');
                if (elements.length === 2) {
                    startWrapperHtml = elements[0];
                    endWrapperHtml = elements[1];
                }

                let title = column.footer().textContent;

                // Create input element
                let input = document.createElement('input');
                input.setAttribute('type', 'search');
                let input_id = 'colSearch_' + title.replace(' ', '');
                input.id = input_id;
                input.setAttribute('name', 'colSearch_' + title.replace(' ', ''));
                input.placeholder = 'Type ...';
                input.setAttribute('class', 'form-control form-control-sm bg-warning-subtle');

                // let inputColumnSearchHtml = ' <input type="search" class="form-control" id="colSearch_' + input_id + '" placeholder="' + title + '">'
                // let btnDropdown = ' <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" ' +
                //     ' aria-expanded="false" data-bs-auto-close="outside">' +
                //     'Dropdown form' +
                //     '</button>';
                // let _form =
                //     '<form class="dropdown-menu p-1">' +
                //     '<div class="my-1 parent" >' +
                //     '<label for="colSearch_' + input_id + '" class="form-label">' + title + '</label>' +
                //     '</div>'+'</form>';
                // let out = startWrapperHtml + btnDropdown + _form + endWrapperHtml;
                // console.log(column.footer())

                // column.footer().innerHtml=out;
                // let fff=document.createElement('out');
                // column.footer().appendChild(input);

                column.footer().replaceChildren(input);
                // Event listener for user input
                input.addEventListener('keyup', () => {
                    if (column.search() !== this.value) {
                        column.search(input.value).draw();
                    }
                });
            });
    },


});
//
//
//
standartEvents.tdBodyDtDblClick(table);
standartEvents.changeVisibility(table);

//
//
//
//
table.MakeCellsEditable(
    //
    {
        "onUpdate": dtDefaultCallbackFunction,
        "inputCss": "bg-warning-subtle",
        // "columns": dataJsonFile,
        "allowNulls": {
            "columns": [3],
            "errorClass": "error"
        },
        // "confirmationButton": true,
        "confirmAll": true,
        "listenToKeys": true,
        // could also be true
        // "confirmationButton": { // could also be true
        //     "confirmCss": "btn btn-sm btn-primary float-start mt-1",
        //     "cancelCss": "btn btn-sm btn-danger float-end mt-1",
        // },
        confirmationButton: true,
        "inputTypes": [
            //
            {
                "column": 0,
                "type": false,
            }, {
                "column": 4,
                "type": "number",
                "options": null
            },
            //
            {
                "column": 12,
                "type": "list-noconfirm",
                "options": [{
                    "value": "1",
                    "display": "Beaty"
                }, {
                    "value": "2",
                    "display": "Doe"
                }, {
                    "value": "3",
                    "display": "Dirt"
                }]
            }, {
                "column": 10,
                "type": "list",
                "options": [{
                    "value": "1",
                    "display": "Beaty"
                }, {
                    "value": "2",
                    "display": "Doe"
                }, {
                    "value": "3",
                    "display": "Dirt"
                }, {
                    "value": "4",
                    "display": "Beaty"
                }, {
                    "value": "5",
                    "display": "Doe"
                }, {
                    "value": "6",
                    "display": "Dirt"
                }]
            },
            //
            {
                "column": 3,
                "type": "text-confirm",
                "options": null
            }, {
                "column": 5,
                "type": "textarea-confirm",
                "options": null
            },
        ]
    }
    //
);

async function dtDefaultCallbackFunction(updatedCell, updatedRow, oldValue, columnIndex) {


// console.log(updatedCell.data(), columnIndex)
// console.info(updatedRow.data())

let row=updatedRow.data();
let field= row[columnIndex];

let keys = Object.keys(row);
let myKey= keys[columnIndex];
let primaryKey= keys[0];

console.log(row,keys, columnIndex,field,myKey)

    let url = checkOnUrl(document.location.href) + '&operation=editCell';

    // let editID = updatedRow.data().myKey;
    let editID = row[primaryKey];

    let DATA = {
        // "updatedRow": updatedRow.data(),
        "editID": +editID,
        "newValue": updatedCell.data(),
        "oldValue": oldValue,
        "columnIndex": columnIndex,
        "myKey": myKey,
        "primaryKey": primaryKey,
    };
    let defaultResponse = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(DATA)
    });
    let result = await defaultResponse.json();
    // alert(result.message);
    console.log("result", result)
    // console.log("myAjax", myAjax)
}
//
//
// стилизуем поиск DT
// const inputSearch = document.querySelectorAll('div.dt-search input[type='search']')[0];
// inputSearch.classList.add('w-50');
function destroyTable() {
    if ($.fn.DataTable.isDataTable('#defaultDataTable')) {
        table.destroy();
        table.MakeCellsEditable("destroy");
    }
}