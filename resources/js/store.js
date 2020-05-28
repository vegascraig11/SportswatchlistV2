import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    date: (new Date()).toString(),
    auth: false,
    user: {},
    registerMessage: '',
    watchlist: [],
    leagues: ['nfl', 'ncaaf', 'nba', 'ncaab', 'mlb', 'nhl'],
    selectedLeagues: []
  },
  mutations: {
    setDate: (state, newDate) => state.date = newDate,
    authenticate: (state) => state.auth = true,
    unauthenticate: (state) => {
      state.auth = false
      state.watchlist = []
    },
    setUser: (state, user) => state.user = user,
    registerMessage: (state, message) => state.registerMessage = message,
    setWatchlist: (state, watchlist) => state.watchlist = watchlist,
    toggleLeague: (state, league) => {
      const pos = state.selectedLeagues.indexOf(league);
      if (pos === -1) {
        const where = state.leagues.indexOf(league);
        state.selectedLeagues.splice(where, 0, league);
        if (state.leagues.length === state.selectedLeagues.length) {
          state.selectedLeagues = []
        }
      } else {
        state.selectedLeagues.splice(pos, 1);
      }
    }
  },
  getters: {
    isLoggedIn: (state) => state.auth === true,
    watchlistIds: state => state.watchlist.map(game => game.game_id),
  },
  actions: {
    login: (ctx, user) => {
      const { email, password } = user
      return new Promise((resolve, reject) => {
        axios.get('sanctum/csrf-cookie')
          .then(() => axios.post('login', { email, password }))
          .then(async response => {
            const user = await axios.get('api/user')
            window.localStorage.setItem('loggedIn', true)
            ctx.commit('authenticate')
            ctx.commit('setUser', user.data)
            ctx.dispatch('fetchWatchlist')
            resolve(true)
          })
          .catch(reject)
      })
    },
    fetchWatchlist: ctx => {
      axios.get('/api/watchlist')
        .then(response => ctx.commit('setWatchlist', response.data))
        .catch(err => console.log('Error fetching watchlist for user', err))
    },
    logout: ctx => {
      return new Promise((resolve, reject) => {
        axios.post('/logout')
          .then(() => {
            ctx.commit('unauthenticate')
            ctx.commit('setUser', {})
            window.localStorage.setItem('loggedIn', false)
            flash({
              body: 'Logged out successfully!',
              type: 'success'
            })
            resolve(true)
          })
          .catch(reject)
      })
    }
  }
})

export default store