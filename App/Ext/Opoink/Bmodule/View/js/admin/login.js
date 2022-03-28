/**
 * Copyright 2022 Opoink Framework (http://opoink.com/)
 * Licensed under MIT, see LICENSE.md
 * 
 * https://www.npmjs.com/package/validatorjs
 */

define([
	'jquery',
	'vue',
	'validatorjs',
	'opoink/bmodule/js/admin/services/user.min'
], 
function($, Vue, Validatorjs, user){
	new Vue({
		data() {
			return {
				loginErrorMessage: '',
				formData: {
					email: '',
					password: ''
				},
				formRules: {
					email: 'required|email',
					password: 'required|min:8|max:50'
				},
				formErrorMessage: {
					"required.email": "The email address is required",
					"email.email": "Invalid email format",
					"required.password": "The password is required",
					"min.password": "The password must be a min of 8 characters long",
					"max.password": "The password must be a max of 50 characters long"
				},
				formError: {},
				login: function(e){
					e.preventDefault();

					this.formError = {};
					this.loginErrorMessage = '';

					let validation = new Validatorjs(this.formData, this.formRules, this.formErrorMessage);
					if(validation.passes()){
						$('#main-page-loader').removeClass('d-none');
						user.login(this.formData.email, this.formData.password)
						.then(resolve => {
							window.location.href = adminUrl;
						})
						.catch(error => {
							this.loginErrorMessage = error;
							$('#main-page-loader').addClass('d-none');
						});
					}
					else if(validation.fails()){
						this.formError = validation.errors.errors;
					}
				}
			}
		},
		mounted: function(){
			$('#login-box').removeClass('d-none');
		}
	}).$mount('#login-box');
});