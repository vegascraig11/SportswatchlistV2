require('./bootstrap');

import Vue from 'vue';
import VCalendar from 'v-calendar';
import axios from 'axios';
import router from './router';
import store from './store';

import App from './containers/App';

Vue.use(VCalendar);
Vue.prototype.$http = axios.create({
  baseURL: '/api'
});

window.app = new Vue({
  render: h => h(App),
  router,
  store
}).$mount('#app');