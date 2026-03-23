const TR = document.querySelector('tr[data-index="-1"]');
const TDs = TR.querySelectorAll("td[data-dt-name]");
const TRcontrols = TR.querySelectorAll(
    "input.smarty-control,select.smarty-control",
);

console.log(TR, TDs);

Array.prototype.slice.call(TDs).forEach(function(td, index) {
    let fieldname = td.dataset.fieldname;
    let ind = TR.dataset.index;
    let inputElement = td.querySelector(
        "input.smarty-control,select.smarty-control",
    );

    if(inputElement) {
        let newValue = inputElement.value;

        let d = {}; // Corrected definition
        d.fieldname = fieldname;
        d.index = index;
        d.newValue = newValue;

        console.log(td, d);
    }
});

export function dataGrabber(event, params, removePrimaryKey = true) {
    let data = {
        currentSub: params.currentSub,
        primaryKey: params.primaryKey,
        action: event.currentTarget.dataset.action,
        index: event.currentTarget.dataset.index,
    };

    if(data.action?.toLowerCase() === "addnew" && data.index === "-1") {
        let rowData = getTableRowData(data.index);

        if(removePrimaryKey) {
            delete rowData[`add_${params.primaryKey}`];
        }

        data.data = rowData;
    }

    console.log(data);
    return data;
}

function getTableRowData(rowIndex) {
    let data = {};
    document.querySelectorAll(`tr[data-index="${rowIndex}"]`).forEach((tr) => {
        tr.querySelectorAll("td[data-dt-name]").forEach((cell) => {
            let name = cell.getAttribute("name");
            let input = cell.querySelector(
                "input.smarty-control, select.smarty-control, textarea.smarty-control",
            );

            if(name && input) {
                data[name] = input.value;
            }
        });
    });

    return data;
}

function getTableRowData(rowIndex) {
    let data = {};
    for(let tr of document.querySelectorAll(`tr[data-index="${rowIndex}"]`)) {
        for(let cell of tr.querySelectorAll("td[data-dt-name]")) {
            let name = cell.getAttribute("name");
            let input = cell.querySelector(
                "input.smarty-control, select.smarty-control, textarea.smarty-control",
            );

            if(name && input) {
                data[name] = input.value;
            }
        }
    }
    return data;
}


{
    "all": {

        "tabWidth": 4,
        "semi": true,
        "singleQuote": true,
        "trailingComma": "es5",
        "plugins": ["prettier-plugin-svelte"],
    },

    "js": {
        "indent_size": 4,
        "indent_char": " ",
        "brace_style": "collapse,preserve-inline",
        "break_chained_methods": false,
        "space_in_empty_paren": false,
        "space_in_paren": false,
        "space_before_conditional": false,
        "preserve_newlines": true,
        "max_preserve_newlines": 10,
        "end_with_newline": false,
        "keep_array_indentation": true,
        "unescape_strings": false,
        "jslint_happy": false,
        "wrap_line_length": 120,
        "indent_with_tabs": false,
        "comma_first": false,
        "e4x": false,
        "indent_empty_lines": false,
        "operator_position": "before-newline"
    }

}