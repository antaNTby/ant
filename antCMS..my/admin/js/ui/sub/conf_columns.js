// conf_columns.js
import * as ui from '.././uiAdmin.js';
import * as cs from '../../../js/cs/uiCS.js';
import * as csEvents from '../../../js/cs/csEvents.js';
const legalOperations = ['addNewConfig', 'resetConfig'];

const columnsConfiguratorTable =
    document.querySelector('table#columnsConfiguratorTable');

const switcherFieldsetToggle = document.querySelector('[name="switcherFieldsetToggle"]');
const selectEl = document.querySelector('select[name="configSelector"]');
const fieldset = document.querySelector("#selectConfigFieldset");

const btnOperationAddNew = document.querySelector('button[data-operation="addNewConfig"]');

const configSelectedIndex = getUrlComponent('configSelectedIndex', checkOnUrl(document.location.href));
const operation = getUrlComponent('operation', checkOnUrl(document.location.href));


//conf_columns
switcherFieldsetToggle.addEventListener('change', function(event) {
    let switcher = event.target;
    let lablel = switcher.nextElementSibling;
    let ico = lablel.firstChild;
    if (switcher.checked) {
        fieldset.disabled = false;
        ico.className = "bi bi-unlock-fill text-primary";
        columnsConfiguratorTable.tHead.className="table-primary";
        cs.allControlSnippets.forEach(el => {
            const rowNumber = el.dataset.rowNumber;
            const switcherEnable = document.querySelector('input[type="checkbox"][data-row-number="' + rowNumber + '"][data-field-name="enable"]');
            if (switcherEnable.checked) {
                cs.unblockCS(el);
            }

            switcherEnable.classList.remove('opacity-50');
            switcherEnable.removeAttribute('disabled');
            switcherEnable.removeAttribute('readonly');

        });
    } else {
        fieldset.disabled = true;
        ico.className = "bi bi-lock-fill text-danger";
        columnsConfiguratorTable.tHead.className="table-secondary opacity-50";
        cs.allControlSnippets.forEach(el => {
            cs.blockCS(el);
        });
    }
});
selectEl.onchange = (event) => {
    let sender = event.target;
    deleteUrlComponent('configSelectedIndex');
    deleteUrlComponent('operation');
    let url = checkOnUrl(document.location.href) + '&configSelectedIndex=' + sender.value;
    // console.log(sender.value,url)
    window.location = url;
}

btnOperationAddNew.addEventListener('click', function(event) {
    let sender = event.target;
    deleteUrlComponent('operation');
    deleteUrlComponent('configSelectedIndex');
    let url = checkOnUrl(document.location.href) + '&configSelectedIndex=' + selectEl.value + '&operation=' + 'addNewConfig';
    // console.log(selectEl.value,url)
    window.location = url;
})