/**
 * User: Rubillex
 * Date: 14.02.2022 9:21
 */

import VueRouter from 'vue-router';

import Vue from 'vue';
import IndexComponent from "./components/IndexComponent";

Vue.use(VueRouter);

const router =  [
        {
            path: '/start-game',
            name: 'start-game',
            component: IndexComponent
        }
    ];


export default new VueRouter({
    mode: 'history',
    routes: router
})
