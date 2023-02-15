import _ from 'lodash';

window._ = _;

import * as $ from 'jquery'

window.$ = window.jQuery = $;

import 'overlayscrollbars/overlayscrollbars.css';
import {OverlayScrollbars} from 'overlayscrollbars';

window.OverlayScrollbars = OverlayScrollbars;

import 'admin-lte';

let container = document.querySelector('#info-form');
const delegate = (selector) => (cb) => (e) => e.target.matches(selector) && cb(e);

const inputDelegate = delegate('input');

const textareaDelegate = delegate('textarea');

container.addEventListener('input', inputDelegate(function (el) {
    if (document.getElementById('length-' + el.target.name)) {
        return document.getElementById('length-' + el.target.name).textContent = el.target.value.length;
    }
}));

container.addEventListener('input', textareaDelegate(function (el) {
    if (document.getElementById('length-' + el.target.name)) {
        return document.getElementById('length-' + el.target.name).textContent = el.target.value.length;
    }
}));
