import * as Validator from 'validatorjs';
import * as $ from './../../../../node_modules/jquery';

export class ReactiveForm {

	form:HTMLElement;
	formId:string = '';
	options:any = null;

	/**
	 * hold all input field value
	 */
	value:any = {};
	errors:any = {};
	inputs:any = {};

	is_valid:boolean = false;

	constructor(formId:string, options:any){
		this.formId = formId;
		this.options = options;
		this.setForm(formId, options);
	}

	setForm(formId, options){
		for (let [name, option] of Object.entries(options)) {
			let selector = '#' + formId + ' [name="'+name+'"]';
			let el = $(selector);
			el['rf'] = {
				name: name,
				touched: false,
				is_valid: false,
				rules: options[name][1] ? options[name][1] : '',
			}

			if(!this.inputs) {
				this.inputs = {};
			}

			this.inputs[name] = el;

			let inpuVal = options[name][0] ? options[name][0] : '';
			this.value[name] = inpuVal;
			
			let type = el.attr('type');
			if(type == 'text' || type == 'textarea'){
				el.val(inpuVal);
				el.on('keyup', (e) => {
					el.rf.touched = true;
					this.validate();
				});
			}
			if(type == 'radio'){
				if(el.is(':checked') === false) {
					el.filter('[value='+inpuVal+']').prop('checked', true);
				}
				el.on('change', (e) => {
					el.rf.touched = true;
					this.validate();
				});
			}
			else {
				el.val(inpuVal);
				el.on('change', (e) => {
					el.rf.touched = true;
					this.validate();
				});
			}
		}
	}

	validate(){
		let formData = {};
		let rules = {};
		for (let [name, el] of Object.entries(this.inputs)) {
			let e:any = el;
			let type = e.attr('type');

			let val:any = '';
			if(type == 'radio'){
				val = $("#"+this.formId+" input[name='"+e.rf.name+"']:checked").val();
			}
			else {
				val = e.val();
			}

			formData[name] = val;
			rules[name] = el['rf']['rules'];
			this.value[name] = val;
		}

		let validation:Validator = new Validator(formData, rules);
		if(validation.passes()){
			this.errors = null;
			this.is_valid = true;
		}
		if(validation.fails()){
			this.errors = validation.errors.errors;
			this.is_valid = false;
		}
	}

	patchValue(name:string, value:any){
		this.value[name] = value;

		let el = this.inputs[name];
		let type = el.attr('type');
		if(type == 'radio'){
			el.filter('[value='+value+']').prop('checked', true);
		}
		else {
			el.val(value);
		}
		this.validate();
	}
}
