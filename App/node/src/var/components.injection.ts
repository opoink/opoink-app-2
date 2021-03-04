declare function require(name:string);
let componentInjection = [];

componentInjection.push(require('C:/wamp64/www/opoink/opoink-app-2/App/Ext/Opoink/Api/components.json'));
componentInjection.push(require('C:/wamp64/www/opoink/opoink-app-2/App/Ext/Opoink/Bmodule/components.json'));

let injection = [];

componentInjection.forEach(val => {
	val.forEach(val2 => {
		injection.push(val2);
	});
});
export default injection;
