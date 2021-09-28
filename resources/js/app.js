require('./bootstrap');

import Vue from 'vue';
import App from './Vue/app'

const app = new Vue({
    el: '#app',
    components: { App }
});