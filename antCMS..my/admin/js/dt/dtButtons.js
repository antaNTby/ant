// dtButtons.js

$.fn.dataTable.ext.buttons.separator = {
    text: '',
    attr: {
        style: 'width: 5px; height: 5px; ' +
            'padding-left: 0px; padding-right: 0px; cursor: auto;'
    },
    action: function() {}
};


export const btnNewState = {
    extend: 'createState',
    text: '<i class="bi bi-bookmark-plus"></i>',
    // title: 'Сохранть состояние таблицы',
    className: 'text-bg-success',
    config: {
        creationModal: true,
        toggle: {
            length: true,
            order: true,
            paging: true,
            search: true,
            // searchBuilder: true,
            // searchPanes: true,
            scroller: false,
            select: false
        },
    }
};
export const createStateShort = {
    extend: 'createState',
    text: '<i class="bi bi-bookmark"></i>',
    // title: 'Сохранть состояние таблицы',
    className: 'btn btn-light',
};

export const btnSavedStates = {
    extend: 'savedStates',
    // buttons: [
    //     'createState',
    //     'removeAllStates'
    // ]
    // buttons: [

    //     // 'createState',
    //     // {
    //     //     extend: 'spacer',
    //     //     style: 'bar'
    //     // },
    //     'removeAllStates', {
    //         extend: 'spacer',
    //         style: 'bar'
    //     },
    // ],
    className: 'class="btn btn-light position-relative"',
};

export const btnRemoveAllStates = {
    extend: 'removeAllStates',
    className: 'btn btn-light'
};


export const btnColVis = {
    extend: 'colvis',
    text: '<i class="bi bi-table"></i> ',
    className: 'btn btn-light',
    // columns: 'th:nth-child(n+2)',
    columnText: function(dt, idx, title) {
        return idx + 1 + ' ' + title;
    }
};

export const btnExportPDF = {
    extend: 'pdfHtml5',
    text: 'PDF',
    className: 'text-danger',
    filename: 'dtExport ' + formatNowToFileName(),
    pageSize: 'A4',
    orientation: 'landscape',
    margins: [20, 10, 10, 20],
    title: '',
    header: '',
    footer: '',
    exportOptions: {
        columns: ':visible',
        modifier: {
            page: 'current'
        }
    }
};

export const btnExportExcel = {
    extend: 'excelHtml5',
    text: 'XLS',
    className: 'text-success',
    filename: 'dtExport ' + formatNowToFileName(),
    // pageSize: 'A4',
    // orientation: 'landscape',
    // margins: [20, 10, 10, 20],
    title: '',
    header: '',
    footer: '',
    messageBottom: '',
    messageTop: '',
    exportOptions: {
        autoFilter: true,
        modifier: {
            page: 'all'
        },
        columns: ':visible',
    }
};

export const btnExportJSON = {
    text: 'JSON',
    action: function(e, dt, button, config) {
        var data = dt.buttons.exportData();
        DataTable.fileSave(new Blob([JSON.stringify(data)]), 'Export.json');
    }
};

export const btnExportCSV = {
    extend: 'csv',
    text: 'CSV ;',
    fieldSeparator: ";",
    escapeChar: "&quot;",
    fieldBoundary: "'",
    newline: "\r\n",
    charset: "utf-8",
    className: 'text-dark',
    filename: 'dtExport ' + formatNowToFileName(),
    exportOptions: {
        columns: ':visible',
        modifier: {
            page: 'current'
        }
    }
}

export const btnExportsMenu = {
    text: 'Export',
    className: 'btn btn-light',
    extend: 'collection',
    buttons: [
        btnExportExcel,
        // btnExportPDF,
        btnExportJSON,
        'separator',
        'excelHtml5',
        btnExportCSV,
        'csvHtml5',
    ]

};

const standartButtons = [
    btnNewState,
    btnRemoveAllStates,
    //
    {
        text: 'Select filter applied - object',
        extend: 'selectAll',
        selectorModifier: {
            search: 'applied'
        },
    },
    //
    {
        text: 'Select current page - function',
        extend: 'selectAll',
        selectorModifier: function() {
            return {
                page: 'current'
            };
        }
    },
    //
    'selectNone',
    //
    {
        extend: 'collection',
        text: 'Export',
        buttons: ['csv', 'excel', 'pdf']
    }


];

const otherButtons = [
    'csv',
    //
    {
        extend: 'collection',
        text: 'Show columns',
        buttons: ['columnsVisibility'],
        visibility: true
    },
    //
    {
        extend: 'collection',
        text: 'Hide columns',
        buttons: ['columnsVisibility'],
        visibility: false
    },
    //
    {
        extend: 'colvis',
        columnText: function(dt, idx, title) {
            return idx + 1 + ': ' + title;
        }
    }
];


export default standartButtons