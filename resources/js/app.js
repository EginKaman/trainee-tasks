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
    let timezone = document.querySelector('input[name=timezone]');
    if (timezone) {
        timezone.value = new Date().getTimezoneOffset();
    }
};
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
