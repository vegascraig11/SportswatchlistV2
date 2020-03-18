import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './pages/Home';
import Login from './pages/Login';
import App from './containers/App';
import ForOhFor from './pages/404';
import MLB from './containers/MLB';
import NBA from './containers/NBA';
import NFL from './containers/NFL';
import NHL from './containers/NHL';
import Games from './containers/Games';
import NCAAB from './containers/NCAAB';
import NCAAF from './containers/NCAAF';
import Register from './pages/Register';
import MyWatchlist from './pages/MyWatchlist';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: Home,
      name: 'home'
    },
    {
      path: '/login',
      component: Login,
      name: 'login',
      meta: {
        requiresGuest: true
      }
    },
    {
      path: '/signup',
      component: Register,
      name: 'register'
    },
    {
      path: '/my-watchlist',
      component: MyWatchlist,
      name: 'my-watchlist',
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/games',
      component: Games,
      children: [
        {
          path: 'nba',
          component: NBA,
          name: 'NBA'
        },
        {
          path: 'mlb',
          component: MLB,
          name: 'MLB'
        },
        {
          path: 'ncaab',
          component: NCAAB,
          name: 'NCAAB'
        },
        {
          path: 'ncaaf',
          component: NCAAF,
          name: 'NCAAF'
        },
        {
          path: 'nfl',
          component: NFL,
          name: 'NFL'
        },
        {
          path: 'nhl',
          component: NHL,
          name: 'NHL'
        }
      ]
    },
    {
      path: '*',
      component: ForOhFor,
      name: 'for-oh-for'
    }
  ]
});

export default router;