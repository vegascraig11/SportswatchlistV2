import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    date: (new Date()).toString(),
    auth: false,
    user: {},
    registerMessage: ''
  },
  mutations: {
    setDate: (state, newDate) => state.date = newDate,
    authenticate: (state) => state.auth = true,
    unauthenticate: (state) => state.auth = false,
    setUser: (state, user) => state.user = user,
    registerMessage: (state, message) => state.registerMessage = message
  },
  getters: {
    isLoggedIn: (state) => state.auth === true,
  }
})

export default store