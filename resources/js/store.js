import axios from 'axios';
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'


export default new Vuex.Store({
    state: {
        user: {}
    },
    mutations: {
        setUserState: (state, value) => state.user = value
    },
    actions: {
        userStateAction: async (context) => {
            await axios.get('api/user/me').then(response => {
                const userResponse = response.data.user
                context.commit('setUserState', userResponse)
            })
        }
    },
    plugins: [
        createPersistedState()
    ]
})
