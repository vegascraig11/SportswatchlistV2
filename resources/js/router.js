import Vue from "vue";
import VueRouter from "vue-router";

import Home from "./pages/Home";
import Login from "./pages/Login";
import Keys from "./pages/Keys";
import Users from "./pages/Users";
import App from "./containers/App";
import ForOhFor from "./pages/404";
import Admin from "./containers/Admin";
import Banners from "./pages/Banners";
import Register from "./pages/Register";
import MyWatchlist from "./pages/MyWatchlist";

Vue.use(VueRouter);

const router = new VueRouter({
  mode: "history",
  routes: [
    {
      path: "/",
      component: Home,
      name: "home",
    },
    {
      path: "/login",
      component: Login,
      name: "login",
      meta: {
        requiresGuest: true,
      },
    },
    {
      path: "/signup",
      component: Register,
      name: "register",
    },
    {
      path: "/my-watchlist",
      component: MyWatchlist,
      name: "my-watchlist",
    },
    {
      path: "/admin",
      component: Admin,
      children: [
        {
          path: "/",
          redirect: "/admin/banners",
        },
        {
          path: "banners",
          component: Banners,
          name: "banners",
          meta: {
            requiresAdmin: true,
          },
        },
        {
          path: "users",
          component: Users,
          name: "users",
          meta: {
            requiresAdmin: true,
          },
        },
        {
          path: "keys",
          component: Keys,
          name: "keys",
          meta: {
            requiresAdmin: true,
          },
        },
      ],
    },
    {
      path: "*",
      component: ForOhFor,
      name: "for-oh-for",
    },
  ],
});

export default router;
