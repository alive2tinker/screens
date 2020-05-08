import RequestManager from "./RequestManager";

const state = {
    attachments: [],
    links: null
};
const getters = {
    allAttachments: state => {
        return state.attachments;
    }
};
const actions = {
    fetchAttachments: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().get(`api/attachments/${payload}`)
                .then(({data}) => {
                    commit('setAttachments', data);
                    resolve(true);
                }).catch((error) => {
                reject(error);
            });
        });
    },
    fetchMoreAttachments: ({commit}) => {
        return new Promise((resolve, reject) => {
            if (state.links.next != null) {
                RequestManager()
                    .get(state.links.next)
                    .then(({ data }) => {
                        commit("pushAttachments", data);
                        resolve(true);
                    })
                    .catch(error => {
                        reject(error);
                    });
            } else {
                reject("no more attachments");
            }
        });
    },
    createAttachment: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().post(`api/attachments/${payload.screen}`, payload.form)
                .then(({data}) => {
                    commit('pushNewAttachment', data);
                    resolve(true);
                }).catch((error) => {
                reject(error);
            });
        });
    },
    deleteAttachment: ({commit}, payload) => {
        return new Promise((resolve, reject) => {
            RequestManager().delete('api/attachments/'+payload)
                .then(() => {
                    commit('deleteStateAttachment', payload);
                    resolve(true);
                }).catch((error) => {
                reject(error);
            });
        });
    }
};
const mutations = {
    setAttachments: (state, data) => {
        state.attachments = data.data;
        state.links = data.links;
    },
    pushScreens: (state, data) => {
        state.attachments.push(...data.data);
        state.links = data.links;
    },
    pushNewAttachment: (state,data) => {
        state.attachments.unshift(data);
    },
    deleteStateAttachment: (state, data) => {
        var i = state.attachments.findIndex(s => s.id === data);
        state.attachments.splice(i, 1)
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}
