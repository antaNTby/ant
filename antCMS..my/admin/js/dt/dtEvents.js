// dtEvents.js

export const tdBodyDtDblClick = (table) => {
    table.on('dblclick', 'tbody td', function() {
        // let data = table.cell(this).render('display');
        let data = table.cell(this).render('filter');
        console.info(data);
    });
};


export const changeVisibility = (table) => {

    table.on('column-visibility.dt', function(e, settings, column, state) {
        // console.log('Column ' + column + ' has changed to ' + (state ? 'visible' : 'hidden'));
        // // table.state.save();
        // // console.log(table.state())
        // let newState= table.stateRestore;
        //  console.log(newState(),table.state())
    });

};