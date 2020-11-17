import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: {}
    },
    mutations: {
        setUserState: (state, value) => state.user = value
    },
    actions: {
        userStateAction: ({commit}) => {
            axios.get('api/user/me').then(response => {
                commit('setUserState', response.data)
            })
        }
    }
})
