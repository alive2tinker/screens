import RequestManager from "./RequestManager";

const state = {
    screens: [],
    screen: null,
    links: null
};
const getters = {
    allScreens: state => {
        return state.screens
    },
    currentScreen: state => {
        return state.screen
    }
};
const actions = {
    fetchScreens: ({commit}) => {
        return new Promise((resolve, reject) => {
            RequestManager().get('api/screens')
                .then(({data}) => {
                    commit('setScreens', data);
                    resolve(true);
                }).catch((error) => {
                    reject(error);
            });
        });
    },
    fetchMoreScreens: ({commit}) => {
        return new Promise((resolve, reject) => {
            if (state.links.next != null) {
                RequestManager()
                    .get(state.links.next)
                    .then(({ data }) => {
                        commit("pushScreens", data);
                        resolve(true);
                    })
                    .catch(error => {
                        reject(error);
                    });
            } else {
                reject("no more screens");
            }
        });
    },
    fetchScreen: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().get('api/screens/'+payload)
                .then(({data}) => {
                    commit('setScreen', data);
                    resolve(true);
                }).catch((error) =>{
                reject(error);
            });
        });
    },
    createScreen: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().post('api/screens', payload)
                .then(({data}) => {
                    commit('pushNewScreen', data);
                    resolve(true);
                }).catch((error) => {
                    reject(error);
            });
        });
    },
    updateScreen: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().post('api/screens/'+state.screen.id, payload)
                .then(({data}) => {
                    commit('updateStateScreen', data);
                    resolve(true);
                }).catch((error) => {
                reject(error);
            });
        });
    },
    deleteScreen: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().delete('api/screens/'+payload)
                .then(() => {
                    commit('deleteStateScreen', payload);
                    resolve(true);
                }).catch((error) => {
                reject(error);
            });
        });
    }
};
const mutations = {
    setScreens: (state, data) => {
        state.screens = data.data;
        state.links = data.links;
    },
    setScreen: (state, data) => {
        state.screen = data;
    },
    pushScreens: (state, data) => {
        state.screens.push(...data.data);
        state.links = data.links;
    },
    pushNewScreen: (state,data) => {
        state.screens.unshift(data);
    },
    updateStateScreen: (state, data) => {
        var i = state.screens.findIndex(s => s.id === data.id);
        state.screens[i] = data;
    },
    deleteStateScreen: (state, data) => {
        var i = state.screens.findIndex(s => s.id === data.id);
        state.screens.splice(i, 1);
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}
