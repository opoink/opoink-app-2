/**
 * Copyright 2022 Opoink Framework (http://opoink.com/)
 * Licensed under MIT, see LICENSE.md
 */

define([
	'jquery'
], function($) {
	'use strict';

	class Request {

		UrlQueryParam = {};

		constructor(){
			this.UrlQueryParam = this.getJsonFromUrl();
		}

		getJsonFromUrl(url) {
			if(!url) { 
				url = location.href;
			}

			var question = url.indexOf("?");
			var hash = url.indexOf("#");
			if(hash==-1 && question==-1) return {};
			if(hash==-1) hash = url.length;
			var query = question==-1 || hash==question+1 ? url.substring(hash) : url.substring(question+1,hash);

			var result = {};
			query.split("&").forEach(function(part) {
				if(!part) { return };

				part = part.split("+").join(" "); // replace every + with space, regexp-free version
				var eq = part.indexOf("=");
				var key = eq>-1 ? part.substr(0,eq) : part;
				var val = eq>-1 ? decodeURIComponent(part.substr(eq+1)) : "";
				var from = key.indexOf("[");
				if(from==-1) {
					result[decodeURIComponent(key)] = val;
				}
				else {
					var keys = key.split('[');

					var tmpRes = result;
					keys.forEach((_key, i) => {
						var end = key.indexOf("]",_key);
						if(end) {
							_key = _key.split(']').join('');
						}
						
						var index = decodeURIComponent(_key) + '';
						if(typeof tmpRes[index] == 'undefined'){
							if((keys.length - 1) == i){
								tmpRes[index] = val;
							}
							else {
								tmpRes[index] = {};
							}
						}
						tmpRes = tmpRes[index];
					});
				}
			});
			return result;
		}

		getUrlParam(sParam='') {
			if(!sParam){
				return this.UrlQueryParam;
			}
			else {
				let tmpParam = this.UrlQueryParam;
				sParam = sParam.split('/');
				sParam.forEach((path, i) => {
					if(typeof tmpParam[path] != 'undefined'){
						tmpParam = tmpParam[path];
					}
					else {
						tmpParam = null;
						return tmpParam;
					}
				});

				return tmpParam;
			}
		}

		ajax(options={}){
			let xhr = new XMLHttpRequest();
			xhr.open(options['method'], options['url']); 
		
			let isJson = false;
			if(typeof options['headers'] != 'undefined'){
	
				let objKeys = Object.keys(options['headers']);
				objKeys.forEach(key => {
					let val = options['headers'][key];
					xhr.setRequestHeader(key, val);
				});
			}
		
			if(typeof options['uploadProgress'] == 'function'){
				xhr.upload.addEventListener('progress', (e) => {
					let uploadProgress = (e.loaded / e.total)*100;
					options['uploadProgress'](e, uploadProgress);
				});
			}
			if(typeof options['abort'] == 'function'){
				xhr.upload.addEventListener('progress', (e) => {
					options['abort'](xhr);
				});
			}
		
			if(typeof options['success'] == 'function'){
				xhr.addEventListener('load', (e) => {
					if(xhr.status == 200){
						let responseText = xhr.responseText
						if(this.isJsonResponse(xhr.getResponseHeader('content-type'))){
							responseText = JSON.parse(xhr.responseText);
						}
						options['success'](responseText);
					}
				});
			}
			if(typeof options['error'] == 'function'){
				xhr.addEventListener('load', (e) => {
					if(xhr.status != 200){
						let responseText = xhr.responseText
						options['error'](responseText);
					}
				});
			}
			if(typeof options['complete'] == 'function'){
				xhr.addEventListener('loadend', (e) => {
					options['complete'](xhr);
				});
			}
		
			xhr.send(options['data']);
		}
		isJsonResponse(headers){
			let isJson = false;
			let _headers = headers.split(';');
			if(this.inArray('application/json', _headers) ){
				isJson = true;
			}
			return isJson;
		}
		inArray(needle, haystack) {
			var length = haystack.length;
			for(var i = 0; i < length; i++) {
				if(haystack[i] == needle) return true;
			}
			return false;
		}

		/**
		 * 
		 * @param url full valid API url
		 * @param data if empty then no data will be requested. this can be json or a formdata
		 * @param method request method
		 * @param customHeader header that will be sent along with the request
		 * @param completeCallback function that will be called upon complete
		 * @returns 
		 */
		doRequest(url, data, method, customHeader=null, completeCallback = null){
			return new Promise((resolve, reject) => {

				let headers = {
					"Content-Type": 'application/json',
				}

				if(customHeader){
					headers = customHeader;
				}

				this.ajax({
					method : method,
					url: url,
					data: data,
					headers: headers,
					success: (success) => {
						resolve(success);
					},
					error: (error) => {
						reject(error);
					},
					complete: (complete) => {
						if(typeof completeCallback == 'function'){
							completeCallback(complete);
						}
					}
				});
			});
		}
	}
	
	return new Request();
});