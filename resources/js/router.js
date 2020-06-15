import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './pages/Home';
import Login from './pages/Login';
import App from './containers/App';
import ForOhFor from './pages/404';
import Admin from './containers/Admin';
import Dashboard from './pages/Dashboard';
import Banners from './pages/Banners';
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
      path: '/admin',
      component: Admin,
      name: 'admin',
      // meta: {
      //   requiresAuth: true
      // },
      children: [
        {
          path: '/',
          component: Dashboard,
          name: 'dashborad'
        },
        {
          path: 'banners',
          component: Banners,
          name: 'banners'
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