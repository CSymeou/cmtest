/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import Vuetify from 'vuetify';
import '@mdi/font/css/materialdesignicons.css'

Vue.use(Vuetify, {
  iconfont: 'mdi'
}); 
import 'vuetify/dist/vuetify.min.css'

import App from './components/App'

const opts = {}
const vuetify = new Vuetify(opts)

const app = new Vue({
    vuetify,
    render: h => h(App),
    el: '#app',
});
