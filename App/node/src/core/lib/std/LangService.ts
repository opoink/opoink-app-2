import * as languages from "./../../../var/languages.json";

export class LangService {

	getLang(key:string, language, values:any = null){
		if(typeof languages[language] != 'undefined'){
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
			return key + ' undefined language text';
		}
	}
}

export default new LangService();