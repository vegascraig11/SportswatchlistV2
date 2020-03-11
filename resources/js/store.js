import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    date: (new Date()).toString()
  },
  mutations: {
    setDate: (state, newDate) => state.date = newDate
  }
})

export default store