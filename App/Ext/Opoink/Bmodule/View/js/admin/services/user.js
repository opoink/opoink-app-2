/**
 * Copyright 2022 Opoink Framework (http://opoink.com/)
 * Licensed under MIT, see LICENSE.md
 */

define([
	'jquery',
	'request',
	'storage'
], function($, request, storage) {
	'use strict';
	
	class user {

		user = null;

		/**
		 * this will make an API request to login a user
		 * @param {*} email string
		 * @param {*} password string
		 */
		login(email, password){
			return new Promise((resolve, reject) => {
				let url = adminUrl + 'login';
				let jsonData = {
					email: email,
					password: password,
				}

				request.doRequest(url, JSON.stringify(jsonData), 'POST').then(result => {
					this.user = result.user;
					// storage.setLocal('token', result.token, null);
					resolve(this.user);
				})
				.catch(error => {
					reject(error);
				});
			});
		}
	}

	return new user();
});