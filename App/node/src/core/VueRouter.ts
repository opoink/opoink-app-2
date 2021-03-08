import VueRouter from './../../node_modules/vue-router/dist/vue-router.min';

class VRouter {

    routes:any = [];
    vueRouter:any;
    VueRouter:any = VueRouter;

    constructor(){
        let routes = [];
        this.vueRouter = new VueRouter({routes});
    }

    addRoute(option){
        this.vueRouter.addRoute(option)
    }
}

export default new VRouter();
