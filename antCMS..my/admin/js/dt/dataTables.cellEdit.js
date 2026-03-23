/*! CellEdit 1.0.19
 * ©2016 Elliott Beaty - datatables.net/license
 */
/**
 * @summary     CellEdit
 * @description Make a cell editable when clicked upon
 * @version     1.0.19
 * @file        dataTables.editCell.js
 * @author      Elliott Beaty
 * @contact     elliott@elliottbeaty.com
 * @copyright   Copyright 2016 Elliott Beaty
 *
 * This source file is free software, available under the following license:
 *   MIT license - http://datatables.net/license/mit
 *
 * This source file is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 *
 * For details please refer to: http://www.datatables.net
 */
jQuery.fn.dataTable.Api.register('MakeCellsEditable()', function(settings) {
    let table = this.table();
    jQuery.fn.extend({
        // UPDATE
        updateEditableCell: function(callingElement) {
            // Need to redeclare table here for situations where we have more than one datatable on the page. See issue6 on github
            let table = $(callingElement).closest("table").DataTable().table();
            let row = table.row($(callingElement).parents('tr'));
            let cell = table.cell($(callingElement).parents('td, th'));
            let columnIndex = cell.index().column;
            let inputField = getInputField(callingElement);
            // Update
            let newValue = inputField.val();
            if (!newValue && ((settings.allowNulls) && settings.allowNulls != true)) {
                // If columns specified
                if (settings.allowNulls.columns) {
                    // If current column allows nulls
                    if (settings.allowNulls.columns.indexOf(columnIndex) > -1) {
                        _update(newValue);
                    } else {
                        _addValidationCss();
                    }
                    // No columns allow null
                } else if (!newValue) {
                    _addValidationCss();
                }
                //All columns allow null
            } else if (newValue && settings.onValidate) {
                if (settings.onValidate(cell, row, newValue)) {
                    _update(newValue);
                } else {
                    _addValidationCss();
                }
            } else {
                _update(newValue);
            }

            function _addValidationCss() {
                // Show validation error
                if (settings.allowNulls.errorClass) {
                    $(inputField).addClass(settings.allowNulls.errorClass);
                } else {
                    $(inputField).css({
                        "border": "red solid 1px"
                    });
                }
            }

            function _update(newValue) {
                let oldValue = cell.data();
                cell.data(newValue);
                //Return cell & row.
                settings.onUpdate(cell, row, oldValue, columnIndex);
            }

            // Get current page
            let currentPageIndex = table.page.info().page;
            //Redraw table
            table.page(currentPageIndex).draw(false);
        },
        // CANCEL
        cancelEditableCell: function(callingElement) {
            let table = $(callingElement.closest("table")).DataTable().table();
            let cell = table.cell($(callingElement).parents('td, th'));
            // Set cell to it's original value
            cell.data(cell.data());
            // Redraw table
            table.draw();
        },
        // restore
        restoreCellData: function(callingElement) {
            let table = $(callingElement.closest("table")).DataTable().table();
            let cell = table.cell($(callingElement).parents('td, th'));
            // Set cell to it's original value
            cell.data(cell.data());
            // No Redraw table
            return cell.data();
        }
    });
    // Destroy
    if (settings === "destroy") {
        $(table.body()).off("dblclick", "td");
        table = null;
    }
    if (table != null) {
        // On cell dblclick
        $(table.body()).on('dblclick', 'td', function() {
            let currentColumnIndex = table.cell(this).index().column;

            // удалить другие CAN BE EDITED
            let editedCells = $('[data-id="editedCell"]');
            $.each(editedCells, function(index, abortedCell) {
                $(abortedCell).attr("data-id", "Photo by Kelly Clark");
                $(abortedCell).restoreCellData($(abortedCell));
            });

            // DETERMINE WHAT COLUMNS CAN BE EDITED
            if ((settings.columns && settings.columns.indexOf(currentColumnIndex) > -1) || (!settings.columns)) {
                let row = table.row($(this).parents('tr'));
                editableCellsRow = row;
                let cell = table.cell(this).node();
                let oldValue = table.cell(this).data();
                // Sanitize value
                oldValue = sanitizeCellValue(oldValue);
                // Show input
                if (!$(cell).find('input').length && !$(cell).find('select').length && !$(cell).find('textarea').length) {
                    // Input CSS
                    let input = getInputHtml(currentColumnIndex, settings, oldValue);
                    $(cell).html(input.html);
                    if (input.focus) {
                        $('[data-id="editedCell"]').focus();
                    }
                }
            }
        });
    }
});

function getInputHtml(currentColumnIndex, settings, oldValue) {
    let inputSetting, input, inputCss, confirmCss, cancelCss;
    let endWrapperHtml = '';
    let startWrapperHtml = '';
    let listenToKeys = false;
    let wrapperHtmlDefault = '<div class="shadow shadow-lg rounded-1 border border-1 p-1 bg-dark-subtle">{content}</div>';
    let inputType = "textarea";
    input = {
        "focus": true,
        "html": null
    };
    if (settings.inputTypes) {
        $.each(settings.inputTypes, function(index, setting) {
            if (setting.column == currentColumnIndex) {
                inputSetting = setting;
                let isDenyEdit = false;
                if (inputSetting.type === false) {
                    isDenyEdit = true;
                }
                inputType = (isDenyEdit) ? null : inputSetting.type.toLowerCase();
            }
        });
    }
    if (inputType === null) {
        inputDenyEdit = {
            "focus": false,
            "html": `<em>${oldValue}</em>`
        };
        return inputDenyEdit;
    }
    //##
    if ((inputType == 'list') || (inputType == 'select') || (inputType == 'list-confirm') || (inputType == 'select-confirm') || (inputType == 'list-noconfirm') || (inputType == 'select-noconfirm')) {
        inputCss = "form-select form-select-sm";
    } else {
        inputCss = "form-control form-control-sm";
    }
    //##
    if (settings.inputCss) {
        inputCss += ' ' + settings.inputCss;
    }
    //##
    settings.wrapperHtml = wrapperHtmlDefault;
    if (settings.wrapperHtml) {
        let elements = settings.wrapperHtml.split('{content}');
        if (elements.length === 2) {
            startWrapperHtml = elements[0];
            endWrapperHtml = elements[1];
        }
    }
    //##
    // стили кнопоков конфирм
    let confirmCssDefault = "btn btn-sm btn-success";
    let cancelCssDefault = "btn btn-sm btn-danger";
    confirmCss = confirmCssDefault;
    cancelCss = cancelCssDefault;
    if (settings.confirmationButton && settings.confirmationButton.confirmCss) {
        confirmCss = settings.confirmationButton.confirmCss;
    }
    if (settings.confirmationButton && settings.confirmationButton.cancelCss) {
        cancelCss = settings.confirmationButton.cancelCss;
    }
    // стили кнопоков конфирм
    let needConfirmEdit = true; // по-умолчанию все Типы требуют подтверждения
    if (!!settings.confirmAll) {
        needConfirmEdit = true; // по-умолчанию все Типы требуют подтверждения
    }
    if (!settings.confirmAll) {
        needConfirmEdit = false;
    }
    // локальное type-confirm отменяет дефолтное
    if (!(inputType.endsWith('-noconfirm')) && !(inputType.endsWith('-confirm'))) {
        if (needConfirmEdit) {
            inputType = inputType + "-confirm";
        }
    }
    if (inputType.endsWith('-confirm')) {
        needConfirmEdit = true;
    }
    if (inputType.endsWith('-noconfirm')) {
        inputType = inputType.split('-noconfirm')[0];
        needConfirmEdit = false;
    }
    let antToolbar = "";
    let _onchangeEvent_ = " onchange='$(this).updateEditableCell(this);'";
    let _onfocusoutEvent_ = " onfocusout='$(this).updateEditableCell(this);'";
    let _onKeyupEvent_ = '';
    if (needConfirmEdit) {
        if (settings.confirmationButton && settings.confirmationButton.listenToKeys) {
            listenToKeys = settings.confirmationButton.listenToKeys;
        }
        if (settings && settings.listenToKeys) {
            listenToKeys = settings.listenToKeys;
        }
        _onchangeEvent_ = "";
        _onfocusoutEvent_ = "";
        antToolbar = "<div class='btn-toolbar d-flex flex-column flex-xxl-row justify-content-xxl-between mt-1'>";
        antToolbar += "<a href='javascript:void(0);' class='mt-1 text-nowrap " + confirmCss + "' onclick='$(this).updateEditableCell(this);'><i class=\"bi bi-check-lg\"></i> Ok!</a> ";
        antToolbar += "<a href='javascript:void(0);' class='mt-1 text-nowrap " + cancelCss + "' onclick='$(this).cancelEditableCell(this)'><i class=\"bi bi-x-lg\"> Cancel</i></a>";
        antToolbar += "</div";
        if (listenToKeys) {
            let keyUpScript = "if (event.keyCode == 13) {$(this).updateEditableCell(this);} else if (event.keyCode === 27) {$(this).cancelEditableCell(this);}";
            _onKeyupEvent_ = `onkeyup="${keyUpScript}"`;
        }
    }
    //##
    //##
    //##
    //##
    //##
    // console.log("INPUT_TYPE:" + inputType, "  listenToKeys:" + listenToKeys)
    //
    switch (inputType) {
        case "list":
        case "select":
        case "list-confirm": // List w/ confirm
        case "select-confirm": // List w/ confirm
            htmlOptions = "";
            $.each(inputSetting.options, function(index, option) {
                if (oldValue == option.value) {
                    htmlOptions += "<option value='" + option.value + "' selected>" + option.display + "</option>"
                } else {
                    htmlOptions += "<option value='" + option.value + "'>" + option.display + "</option>"
                }
            });
            html = startWrapperHtml;
            html += "<select data-currentColumnIndex='" + currentColumnIndex + "'ata-id='editedCell' class='" + inputCss + "'" + _onchangeEvent_ + ">"; //
            html += htmlOptions;
            html += "</select>";
            html += antToolbar;
            html += endWrapperHtml;
            input.html = html;
            input.focus = false;
            break;
            // case "datepicker": //Both datepicker options work best when confirming the values
            // case "datepicker-confirm":
            //     // Makesure jQuery UI is loaded on the page
            //     if (typeof jQuery.ui == 'undefined') {
            //         alert("jQuery UI is required for the DatePicker control but it is not loaded on the page!");
            //         break;
            //     }
            //     jQuery(".datepick").datepicker("destroy");
            //     input.html = startWrapperHtml + "<input data-id='editedCell' data-currentColumnIndex='"+currentColumnIndex+"' type='text' name='date' class='datepick " + inputCss + "'   value='" + oldValue + "'> "+toolbar + endWrapperHtml;
            //     setTimeout(function() { //Set timeout to allow the script to write the input.html before triggering the datepicker
            //         let icon = "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif";
            //         // Allow the user to provide icon
            //         if (typeof inputSetting.options !== 'undefined' && typeof inputSetting.options.icon !== 'undefined') {
            //             icon = inputSetting.options.icon;
            //         }
            //         let self = jQuery('.datepick').datepicker({
            //             showOn: "button",
            //             buttonImage: icon,
            //             buttonImageOnly: true,
            //             buttonText: "Select date"
            //         });
            //     }, 100);
            //     break;
        case "text-confirm": // text input w/ confirm
            input.html = startWrapperHtml + "<input data-id='editedCell' data-currentColumnIndex='" + currentColumnIndex + "' class='" + inputCss + "' value='" + oldValue + "'" + _onKeyupEvent_ + ">" + antToolbar + endWrapperHtml;
            break;
        case "undefined-confirm": // text input w/ confirm
            input.html = startWrapperHtml + "<input data-id='editedCell' data-currentColumnIndex='" + currentColumnIndex + "' class='" + inputCss + "' value='" + oldValue + "'" + _onKeyupEvent_ + ">" + antToolbar + endWrapperHtml;
            break;
        case "textarea":
            input.html = startWrapperHtml + "<textarea data-id='editedCell' data-currentColumnIndex='" + currentColumnIndex + "' class='" + inputCss + "'" + _onfocusoutEvent_ + ">" + oldValue + "</textarea>" + endWrapperHtml;
            break;
        case "textarea-confirm":
            input.html = startWrapperHtml + "<textarea data-id='editedCell' data-currentColumnIndex='" + currentColumnIndex + "' class='" + inputCss + "'>" + oldValue + "</textarea>" + antToolbar + endWrapperHtml;
            break;
        case "number":
            input.html = startWrapperHtml + "<input data-id='editedCell' data-currentColumnIndex='" + currentColumnIndex + "' type='number' class='" + inputCss + "' value='" + oldValue + "'>" + endWrapperHtml;
            break;
        case "number-confirm":
            input.html = startWrapperHtml + "<input data-id='editedCell' data-currentColumnIndex='" + currentColumnIndex + "' type='number' class='" + inputCss + "' value='" + oldValue + "'" + _onKeyupEvent_ + ">" + antToolbar + endWrapperHtml;
            break;
        default: // text input
            input.html = startWrapperHtml + "<input data-id='editedCell' data-currentColumnIndex='" + currentColumnIndex + "' class='" + inputCss + "' onfocusout='$(this).updateEditableCell(this)' value='" + oldValue + "'>" + endWrapperHtml;
            break;
    }
    return input;
}

function getInputField(callingElement) {
    // Update datatables cell value
    let inputField;
    let __switcher = $(callingElement).prop('nodeName').toLowerCase();
    switch (__switcher) {
        case 'a': // This means they're using confirmation buttons
            if ($(callingElement).parent().siblings('input').length > 0) {
                inputField = $(callingElement).parent().siblings('input');
            }
            if ($(callingElement).parent().siblings('select').length > 0) {
                inputField = $(callingElement).parent().siblings('select');
            }
            if ($(callingElement).parent().siblings('textarea').length > 0) {
                inputField = $(callingElement).parent().siblings('textarea');
            }
            break;
        default:
            inputField = $(callingElement);
    }
    return inputField;
}

function sanitizeCellValue(cellValue) {
    if (typeof(cellValue) === 'undefined' || cellValue === null || cellValue.length < 1) {
        return "";
    }
    // If not a number
    if (isNaN(cellValue)) {
        // escape single quote
        cellValue = cellValue.replace(/'/g, "&#39;");
    }
    return cellValue;
}