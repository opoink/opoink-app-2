class Request {

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
}

export default new Request();