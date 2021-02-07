import 'jquery';
import Vue from './vue';
import './style.scss';

let testing:any = "testing lang muna";
console.log(testing);


var app5 = new Vue({
    el: '#app-5',
    data: {
      message: 'Hello Vue.js!',
      testing() {
        console.log('testing');
      }
    },
    methods: {
      reverseMessage: function () {
        this.message = this.message.split('').reverse().join('')
      }
    }
  })

