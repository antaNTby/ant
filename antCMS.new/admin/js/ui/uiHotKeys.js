import * as ui from './uiAdmin.js';
//||AltLef||AltRight
// && (event.ctrlKey || event.metaKey)



document.addEventListener('keydown', function(event) {

    // console.log("event: ", event.altKey, event.ctrlKey, "[" + event.code + "]", event);

    if (event && event.code && (event.altKey)) {

        if (event.code == 'KeyM') {
            ui.bsOffcanvasMenu.toggle();
        }
    }

    if (event && event.code && (event.altKey) && (event.ctrlKey || event.metaKey)) {

        if (event.code == 'ArrowUp') {
            scrolToMyTop();
        }
        if (event.code == 'ArrowDown') {
            scrolToMyBottom();
        }
    }

        // console.info(event.code);
});