declare function require(name:string);
import Vue from './vue';

import AppOneComponent from './../../Ext/Opoink/Bmodule/View/vue/components/app-one/app-one.component';
import AppTwoComponent from './../../Ext/Opoink/Bmodule/View/vue/components/app-two/app-two.component';

Vue.component('app-one', {
    data: function(){
        return new AppOneComponent()
    },
    template: require('./../../Ext/Opoink/Bmodule/View/vue/components/app-one/app-one.html')
});
Vue.component('app-two', {
    data: function(){
        return new AppTwoComponent()
    },
    template: require('./../../Ext/Opoink/Bmodule/View/vue/components/app-two/app-two.html')
});
new Vue({ 
    el: '#approot',
    beforeMount(){
    }
});