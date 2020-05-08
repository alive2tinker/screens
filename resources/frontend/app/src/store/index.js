import Vue from "vue";
import Vuex from "vuex";
import auth from "./modules/auth";
import screens from "./modules/screens";
import attachments from "./modules/attachments";
import messages from "./modules/messages";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth,
        screens,
        attachments,
        messages
    }
});
