export const allCheckboxes = document.querySelectorAll(
	'input[type="checkbox"]',
);
export const indeterminateCheckboxes = document.querySelectorAll(
	'input.indeterminate-checkbox[type="checkbox"]',
);

export const SmartyControls = document.querySelectorAll('.smarty-control');
export const InputsAll = document.querySelectorAll(
	'input.smarty-control[type],textarea.form-control.smarty-control',
);

export const Checkboxes = document.querySelectorAll(
	'input.smarty-control[type="checkbox"]',
);
export const InputsText = document.querySelectorAll(
	'input.smarty-control[type="text"]',
);
export const InputsNumber = document.querySelectorAll(
	'input.smarty-control[type="number"]',
);

export const ButtonsAll = document.querySelectorAll(
	'.smarty-control.btn[data-action]',
);
export const ButtonsClone = document.querySelectorAll(
	'.smarty-control[data-action="Clone"]',
);
export const ButtonsClear = document.querySelectorAll(
	'.smarty-control[data-action="Clear"]',
);
export const ButtonsDelete = document.querySelectorAll(
	'.smarty-control[data-action="Delete"]',
);
export const ButtonsAddNew = document.querySelectorAll(
	'.smarty-control[data-action="AddNew"]',
);
export const ButtonsSaveRow = document.querySelectorAll(
	'.smarty-control[data-action="SaveRow"]',
);

export const currentSub = document.querySelector('input#current_sub').value;
export const primaryKey = document.querySelector('input#primary_key').value;

export const ButtonsSaveAll = document.querySelectorAll(
	`[data-action="SaveAll"]`,
);
export const mainTable = document.querySelector(`table#mainTable`);
export const secondTable = document.querySelector(`table#secondTable`);

export const InputsDateTime = document.querySelectorAll(
	`[data-type="date-time"]`,
);
export const InputsUnp = document.querySelectorAll(`[data-type="unp"]`);

export const CalendarPickers = document.querySelectorAll(
	`[data-type="calendar"]`,
);
