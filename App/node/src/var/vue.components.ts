import Vue from './../../node_modules/vue/dist/vue.esm';
import VRouter from './../core/VueRouter';
Vue.use(VRouter.VueRouter);

const router = VRouter.vueRouter;

import 'C:/wamp64/www/opoink/opoink-app-2/App/Ext/Opoink/Api/vue.components.ts';
import 'C:/wamp64/www/opoink/opoink-app-2/App/Ext/Opoink/Bmodule/vue.components.ts';


const app = new Vue({router}).$mount('#approot');
