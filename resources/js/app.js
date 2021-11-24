require('./bootstrap');

import Vue from 'vue';
import App from './Vue/app';

import Notifications from '@voerro/vue-notifications';
Vue.component('notifications', Notifications);

import VTooltip from 'v-tooltip';
Vue.use(VTooltip);

const app = new Vue({
    el: '#app',
    components: { App }
});