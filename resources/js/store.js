import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
import { DateTime } from "luxon";
Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    date: DateTime.local(),
    auth: false,
    user: {},
    registerMessage: "",
    watchlist: [],
    allWatchlist: [],
    leagues: ["nfl", "ncaaf", "nba", "ncaab", "mlb", "nhl"],
    selectedLeagues: [],
    loading: [],
  },
  mutations: {
    setDate: (state, newDate) => (state.date = newDate),
    authenticate: state => (state.auth = true),
    unauthenticate: state => {
      state.auth = false;
      state.watchlist = [];
    },
    setUser: (state, user) => (state.user = user),
    registerMessage: (state, message) => (state.registerMessage = message),
    setWatchlist: (state, watchlist) => (state.watchlist = watchlist),
    setAllWatchlist: (state, watchlist) => (state.allWatchlist = watchlist),
    toggleLeague: (state, league) => {
      const pos = state.selectedLeagues.indexOf(league);
      if (pos === -1) {
        const where = state.leagues.indexOf(league);
        state.selectedLeagues.splice(where, 0, league);
        if (state.leagues.length === state.selectedLeagues.length) {
          state.selectedLeagues = [];
        }
      } else {
        state.selectedLeagues.splice(pos, 1);
      }
    },
    setLeague: (state, league) => (state.selectedLeagues = [league]),
    addToWatchlist: (state, gameId) => {
      state.watchlist.unshift(gameId);
      window.localStorage.setItem("watchlist", JSON.stringify(state.watchlist));
    },
    removeFromWatchlist: (state, index) => {
      if (index !== -1) {
        state.watchlist.splice(index, 1);
        window.localStorage.setItem(
          "watchlist",
          JSON.stringify(state.watchlist)
        );
      }
    },
    loading: (state, league) => {
      if (league.loading) {
        state.loading.push(league.league);
      } else {
        const index = state.loading.indexOf(league.league);
        if (index !== -1) {
          const loading = state.loading;
          loading.splice(index, 1);
          state.loading = loading;
        }
      }
    },
  },
  getters: {
    isLoggedIn: state => state.auth === true,
    isAdmin: state => state.auth === true && state.user.is_admin,
  },
  actions: {
    login: (ctx, user) => {
      const { email, password } = user;
      return new Promise((resolve, reject) => {
        axios
          .get("sanctum/csrf-cookie")
          .then(() => axios.post("login", { email, password }))
          .then(() => axios.get("api/user"))
          .then(response => {
            window.localStorage.setItem("loggedIn", true);
            ctx.commit("authenticate");
            ctx.commit("setUser", response.data);
            ctx.dispatch("fetchWatchlist");
            resolve(true);
          })
          .catch(reject);
      });
    },
    fetchWatchlist: ctx => {
      if (ctx.getters.isLoggedIn) {
        axios
          .get("/api/watchlist")
          .then(response => {
            ctx.commit(
              "setWatchlist",
              response.data.map(game => parseInt(game.game_id))
            );
            ctx.commit("setAllWatchlist", response.data);
          })
          .catch(err => console.log("Error fetching watchlist for user", err));
      }
    },
    logout: ctx => {
      return new Promise((resolve, reject) => {
        axios
          .post("/logout")
          .then(() => {
            ctx.commit("unauthenticate");
            ctx.commit("setUser", {});
            window.localStorage.setItem("loggedIn", false);
            resolve(true);
          })
          .catch(reject);
      });
    },
    addToWatchlist: (ctx, gameId) => {
      return new Promise((resolve, reject) => {
        try {
          ctx.commit("addToWatchlist", gameId);
          resolve(ctx.state.watchlist);
        } catch (err) {
          reject(err);
        }
      });
    },
    removeFromWatchlist: (ctx, gameId) => {
      return new Promise((resolve, reject) => {
        try {
          const index = ctx.state.watchlist.indexOf(gameId);
          ctx.commit("removeFromWatchlist", index);
          resolve(gameId);
        } catch (err) {
          reject(err);
        }
      });
    },
  },
});

export default store;
