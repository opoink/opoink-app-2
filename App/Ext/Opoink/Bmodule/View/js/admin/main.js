define([
	'jquery',
	'request',
	'validatorjs'
], function($, req, validatorjs) {

	let main = {
		init: function(){
			this.sideNavControls.init();
			this.actionTopButtons.init();
			this.actionBtns.init();
		},
		sideNavControls: {
			init: function(){
				$('.toggle-side-nav').on('click', function(){
					main.sideNavControls.toggleSideNav()
				});
			},
			toggleSideNav: function(){
				if($('body').hasClass('side-nav-fixed')){
					$('body').removeClass('side-nav-fixed');
				}
				else {
					$('body').addClass('side-nav-fixed');
				}
			}
		},
		_form: {
			formData: null,
			setFormElement: function(elemId){
				$('#'+elemId).on('submit', function(e){
					e.preventDefault();

					let fields = $('#'+elemId + ' .builder-form-field');

					let isAllPassed = true;


					fields.each(function( key, field ) {
						if(typeof field.dataset.validation_rules != 'undefined'){
							let data = {};
							data[field.name] = field.value;

							let rules = {};
							rules[field.name] = field.dataset.validation_rules;

							let messages = {};
							if(field.dataset.validation_error_message){
								messages = atob(field.dataset.validation_error_message);
								messages = JSON.parse(messages);
							}

							let validation = new validatorjs(data, rules, messages);
							$('.validatorjs-errors-'+field.id).empty();

							if(validation.fails()){
								isAllPassed = false;

								console.log('field field field',  validation.errors.errors[field.name]);

								validation.errors.errors[field.name].forEach(error => {
									$('.validatorjs-errors-'+field.id).append('<small class="text-danger">'+error+'</small>');
								});
							}
							if(validation.passes()){
								/** do nothing */
							}
						}
					});

					if(isAllPassed){
						this.submit();
					}
				});
			},
			_serialize: function(formEl){
				this.formData = formEl.serialize();
				return this;
			},
			toJson: function(){
				let fData = this.formData.split('&');

				let jsonData = {};
				for (let index = 0; index < fData.length; index++) {
					let data = fData[index];
					data = data.split('=');
					if(data.length == 2){
						jsonData[data[0]] = data[1];
					}
				}
				return jsonData;
			}
		},
		actionTopButtons: {
			init: function(){
				let el = $('.page-top-bottons button');
				el.on('click', function(e){
					let dataset = e.target.dataset;
					if(dataset.target == 'link'){
						window.location.href = adminUrl + dataset.action;
					}
					else if(dataset.target == 'form_submit'){
						let formEl = $('#' + dataset.target_form_id);
						if(typeof dataset.action != 'undefined'){
							formEl.attr('action', adminUrl + dataset.action)
						}
						formEl.submit();
					}
				});
			}
		}
	};

	main.init();

	return main;
});