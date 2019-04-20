import Vue from 'vue'
import Vuex from 'vuex'
import permission from './modules/permission'
import getters from './getters'
Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    permission,
  },
  getters
})

export default store
