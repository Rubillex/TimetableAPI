/**
 * User: Rubillex
 * Date: 14.02.2022 9:21
 */

import VueRouter from 'vue-router';

import Vue from 'vue';
import StartGame from "./components/StartGameComponent";

Vue.use(VueRouter);

const router =  [
        {
            path: '/start-game',
            name: 'start-game',
            component: StartGame
        }
    ];


export default new VueRouter({
    mode: 'history',
    routes: router
})
