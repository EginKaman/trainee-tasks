import './bootstrap';

// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap'

window.onload = function () {
    let file = document.querySelector('#file');
    if (file) {
        file.addEventListener('change', function () {
            let radios = document.getElementById('processing')
            radios.style['display'] = 'block';
            document.getElementById('convert-button').removeAttribute('disabled');
        });
    }
    if (window.location.hash === '#converter') {
        let someVarName = document.querySelector('#nav-converter-tab'); // theTabID of the tab you want to open
        let tab = new bootstrap.Tab(someVarName);
        tab.show();
    }
    if (window.location.hash === '#test') {
        let someVarName = document.querySelector('#nav-test-tab'); // theTabID of the tab you want to open
        let tab = new bootstrap.Tab(someVarName);
        tab.show();
    }
    if (window.location.hash === '#previous') {
        let someVarName = document.querySelector('#nav-previous-tab'); // theTabID of the tab you want to open
        let tab = new bootstrap.Tab(someVarName);
        tab.show();
    }
};
