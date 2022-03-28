define([
	'vue',
	'vue-init'
], function(Vue, vInit) {
	'use strict';

	let _component = Vue.component('admin-settings-index-index', {
		template: `{{template}}`
	});

	vInit.addRoute({path: '/admin/settings', component: _component});
});