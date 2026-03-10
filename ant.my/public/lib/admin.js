import * as formatters from '../js/formatters.js';
import * as ui from '../js/userInterface.js';
// import { currentSub, primaryKey } from '../js/uiElements.js';

// console.log(currentSub);

export const currentSub = document.querySelector('input#current_sub').value;
export const primaryKey = document.querySelector('input#primary_key').value;


document.addEventListener('click', function (e) {
    if (e.target.classList.contains('js-confirm-action')) {
        if (!confirm('Вы уверены?')) {
            e.preventDefault();
        }
    }
});


document.addEventListener('submit', function(e) {
    if (e.target.classList.contains('js-confirm-submit')) {
        const message = e.target.getAttribute('data-message') || 'Вы уверены?';
        if (!confirm(message)) {
            e.preventDefault();
        }
    }
});
