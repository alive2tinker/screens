import RequestManager from "./RequestManager";

const state = {
    messages: []
};
const getters = {
    allMessages: state => {
        return state.messages;
    }
};
const actions = {
    fetchMessages: ({commit}) => {
        return new Promise((resolve, reject) => {
            RequestManager().get('api/messages')
                .then(({data}) => {
                    commit('setMessages', data);
                    resolve(true);
                }).catch((error) =>{
                    reject(error);
            })
        });
    },
    createMessage: ({commit}, payload) => {
        return new Promise((resolve,reject) => {
            RequestManager().post('api/messages', payload)
                .then(({data}) => {
                    commit('pushNewMessage', data);
                    resolve(true);
                }).catch((error) => {
                    reject(error);
            });
        });
    },
    updateMessage: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().post(`api/messages/${payload.id}`, payload.form)
                .then(({data}) => {
                    commit("updateStateMessage", data);
                    resolve(true);
                }).catch((error) => {
                    reject(error);
            });
        });
    },
    deleteMessage: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().delete(`api/messages/${payload}`)
                .then(({data}) => {
                    commit('deleteStateMessage', data);
                    resolve(true);
                }).catch((error) => {
                    reject(error);
            })
        })
    }
};
const mutations = {
    setMessages: (state, data) => {
        state.messages = data;
    },
    pushNewMessage: (state, data) =>{
        state.messages.push(data);
    },
    updateStateMessage: (state, data) => {
        var i = state.messages.findIndex(s => s.id === data.id);
        state.messages[i] = data;
    },
    deleteStateMessage: (state, data) => {
        var i = state.messages.findIndex(s => s.id === data);
        state.messages.splice(i, 1);
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}
