import RequestManager from "./RequestManager";

const state = {
    user: { name: "", email: "", accessToken: "", tokenExpiresAt: null }
};
const getters = {
    authenticated: state => {
        if (state.user.accessToken === "") {
            state.user = JSON.parse(localStorage.getItem("user"));
            if (state.user === null) return false;
            else if (state.user.tokenExpiresAt < new Date().getTime())
                return false;
        }

        return true;
    },
    user: state => {
        return state.user;
    }
};
const actions = {
    login: ({ commit }, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager()
                .post("oauth/token", payload)
                .then(({ data }) => {
                    commit("updateToken", data);
                    RequestManager()
                        .get("api/user")
                        .then(({ data }) => {
                            commit("updateUserInfo", data);
                        })
                        .catch(error => {
                            reject(error);
                        });
                    resolve(true);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    logout: ({commit}) => {
        return new Promise((resolve, reject) => {
            RequestManager().get('api/logout')
                .then(() => {
                    commit('deleteCredentials');
                    resolve(true);
                }).catch((error) => {
                    reject(error);
            })
        })
    }
};
const mutations = {
    updateToken: (state, data) => {
        var nTime = new Date().getTime() + data.expires_in;
        state.user = {
            accessToken: data.access_token,
            tokenExpiresAt: nTime
        }
    },
    updateUserInfo: (state, data) => {
        state.user.name = data.name;
        state.user.email = data.email;
        localStorage.setItem('user', JSON.stringify(state.user))
    },
    deleteCredentials: state => {
        state.user = { name: "", email: "", accessToken: "", tokenExpiresAt: null };
        localStorage.removeItem('user');
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
