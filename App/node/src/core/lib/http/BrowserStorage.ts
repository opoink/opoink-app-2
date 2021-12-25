class BrowserStorage {


	/**
	 * convert the array into string first before save into
	 * the storage
	 * @param value must be a string or an array
	 */
	stringify(value:any){
		let v = '';
		if(typeof value == 'string'){
			let v = [value];
		}
		v = JSON.stringify(value);
		return v;
	}
	/**
	 * save the data into session storage
	 * usefull for the data that needs to be deleted 
	 * upon closing the browser
	 * @param name session storage name
	 * @param value session storage value
	 */
	setSession(name, value){
		window.sessionStorage.setItem(name, this.stringify(value));
	}

	/**
	 * set local storage usefull for caching the data
	 * that is came from the api
	 * @param name local storage name
	 * @param value local storage value
	 * @param exp expiration milisec 0 means no expiration
	 */
	setLocal(name, value, exp=86400000){
		let _exp = null;
		if(exp){
			let d = new Date();
			let t = d.getTime();
			_exp = t + exp;
		}

		let v = {
			data: value,
			exp: _exp
		};

		window.localStorage.setItem(name, this.stringify(v));
	}
	
	/**
	 * get data from session or local starage using the given name
	 * @param name session of local storage name
	 */
	get(name){
		return new Promise(resolve => {
			let data: any = window.localStorage.getItem(name);
			if(!data){
				data = window.sessionStorage.getItem(name);
			}

			if (data){
				let r = JSON.parse(data);

				if(typeof r.data != 'undefined' && typeof r.exp != 'undefined'){
					if(r.exp){
						let d = new Date();
						let t = d.getTime();
						if(t < r.exp){
							r = r.data;
						} else {
							this.removeItem(name);
							r = null;
						}
					} else {
						r = r.data;
					}
				}
				resolve(r);
			} else {
				resolve(null);
			}
		});
	}
	
	/**
	 * remove the item from storage
	 * @param name  
	 */
	removeItem(name){
		return new Promise(function(resolve, reject) {
			let data: any = window.localStorage.getItem(name);
			if(data){
				window.localStorage.removeItem(name);
			}

			data = window.sessionStorage.getItem(name);
			if(data){
				window.sessionStorage.removeItem(name);
			}
			resolve('removed');
		});
	}

	clearStorage(){
		window.localStorage.clear();
		window.sessionStorage.clear();
	}
}
export default new BrowserStorage();