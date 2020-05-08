import Vue from "vue";
import VueRouter from "vue-router";
import Login from "../views/Login";
import AppLayout from "../views/layouts/AppLayout";
import Home from "../views/Home";
import Store from '../store/index';
import Show from "../views/screens/Show";
import Messages from "../views/Messages";
import Slider from "../views/Slider";
Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'login',
        component: Login
    },
    {
        path: '/home',
        component: AppLayout,
        children: [
            {
                path: '/',
                name: 'home',
                component: Home
            },
            {
                path: '/screens/:id',
                name: 'screens.show',
                component: Show
            },
            {
                path: '/messages',
                name: 'messages',
                component: Messages
            }
        ]
    },
    {
        path: '/view/:id',
        name: 'screens.view',
        component: Slider
    }
];

const router = new VueRouter({
    mode: 'history',
    routes
});

const openRoutes = ["login", 'screens.view'];
router.beforeEach((to, from, next) => {
    if (Store.getters.authenticated) {
        if (to.path === "/") next("/home");
        else next();
    } else {
        if (openRoutes.includes(to.name)) next();
        else next({name: 'login'});
    }
});

export default router;
