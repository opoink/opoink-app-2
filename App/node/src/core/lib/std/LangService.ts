import * as languages from "./../../../var/languages.json";

export class LangService {

	/**
	 * 
	 * @param key string from your JSON file sample "Hello world": "Hello World!" where "Hello world" is the key
	 * @param language string the name of your json file sample en.json where "en" is the language
	 * @param values array of object
	 * sample string, Welcome back {{firstname}}! outpout Welcome back John!
	 * interpollation can be used in your service ts
	 * 
	 * 	let msg = this.langService.getLang("Welcome back, {{firstname}} {{lastname}}!", "en", [
	 *		{key: "firstname",value: user.firstname},
	 *		{key: "lastname",value: user.lastname}
	 *	]);
	 * console.log(msg);
	 * 
	 * @returns string
	 */
	getLang(key:string, language:string, values:any = null){
		if(typeof languages[language] != 'undefined'){
			if(typeof languages[language][key] != 'undefined'){
				if(values){
					let l:string = languages[language][key];
					values.forEach(value => {
						l = l.replace("{{" + value.key + "}}", value.value);
					});
					return l;
				} else {
					return languages[language][key];
				}
			} else {
				return ' undefined text: ' + key ;
			}
		} else {
			return ' undefined language: ' + language + ' - ' + key;
		}
	}
}

export default new LangService();