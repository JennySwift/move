require('./bootstrap');

import Vue from 'vue'

// Import F7 Bundle
import Framework7 from 'framework7/framework7.esm.bundle.js'

// Import F7-Vue Plugin Bundle (with all F7 components registered)
import Framework7Vue from 'framework7-vue/framework7-vue.esm.bundle.js'

// Init F7-Vue Plugin
Framework7.use(Framework7Vue);

// import Framework7Styles from 'framework7/dist/css/framework7.css';

import 'framework7-icons';

import store from './repositories/Store'
import helpers from './repositories/Helpers'
import filters from './repositories/FiltersRepository'
global.store = store;
global.helpers = helpers;
global.filters = filters;
require('./components');

// Import Main App component
import App from './app.vue';

// Init App
global.app = new Vue({
    el: '#app',
    render: (h) => h(App),
    // ...
});

window.Event = new Vue();

require('./config.js');

const bus = new Vue();
Vue.prototype.$bus = bus;

// import VueRouter from 'vue-router'
// Vue.use(VueRouter);
