import Vue from 'vue';
import VueRouter from 'vue-router';

import App from './containers/App';
import NBA from './containers/NBA';
import NHL from './containers/NHL';
import NFL from './containers/NFL';
import NCAAF from './containers/NCAAF';
import NCAAB from './containers/NCAAB';
import MLB from './containers/MLB';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    // {
    //   path: '/',
    //   component: App,
    //   name: 'home'
    // },
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
    }
  ]
});

export default router;