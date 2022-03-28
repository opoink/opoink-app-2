define([
	'vue',
	'vue-init'
], function(Vue, vInit) {
	'use strict';

	let _component = Vue.component('admin-adminuser-index-index', {
		template: `{{template}}`
	});

	vInit.addRoute({path: '/admin/adminuser', component: _component});
});