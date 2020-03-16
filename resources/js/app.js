require('./bootstrap');

import Vue from 'vue';
import VCalendar from 'v-calendar';
import axios from 'axios';
import router from './router';
import store from './store';

import App from './containers/App';

axios.defaults.withCredentials = true;

Vue.use(VCalendar);
Vue.prototype.$http = axios.create({});

// Make sure these values are set in the .env file
// SESSION_DRIVER=cookie
// SESSION_DOMAIN=<the-server-ip-or-domain>
// AIRLOCK_STATEFUL_DOMAINS=<the-server-ip-or-domain>

axios.get('/airlock/csrf-cookie')
  .then(() => {
    window.app = new Vue({
      render: h => h(App),
      router,
      store
    }).$mount('#app');
  })
  .catch(err => console.log(err))