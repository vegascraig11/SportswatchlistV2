import Vue from 'vue';
import VueRouter from 'vue-router';

import App from './containers/App';
import NBA from './containers/NBA';
import NHL from './containers/NHL';
import NFL from './containers/NFL';
import NCAAF from './containers/NCAAF';
import NCAAB from './containers/NCAAB';
import MLB from './containers/MLB';
import Login from './pages/Login';
import Register from './pages/Register';
import ForOhFor from './pages/404';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/login',
      component: Login,
      name: 'login'
    },
    {
      path: '/signup',
      component: Register,
      name: 'register'
    },
    {
      path: '/nba',
      component: NBA,
      name: 'NBA'
    },
    {
      path: '/mlb',
      component: MLB,
      name: 'MLB'
    },
    {
      path: '/ncaab',
      component: NCAAB,
      name: 'NCAAB'
    },
    {
      path: '/ncaaf',
      component: NCAAF,
      name: 'NCAAF'
    },
    {
      path: '/nfl',
      component: NFL,
      name: 'NFL'
    },
    {
      path: '/nhl',
      component: NHL,
      name: 'NHL'
    },
    {
      path: '*',
      component: ForOhFor,
      name: 'for-oh-for'
    }
  ]
});

export default router;