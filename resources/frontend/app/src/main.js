import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueSweetalert2 from 'vue-sweetalert2';
import VueYouTubeEmbed from 'vue-youtube-embed';

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';


import './assets/css/style.scss'

// Install BootstrapVue
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);
Vue.use(VueSweetalert2);
Vue.use(VueYouTubeEmbed);
Vue.use(require('vue-moment'));
Vue.config.productionTip = false;

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount("#app");
