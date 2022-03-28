define([
	'jquery',
	'request'
], function($, vInit, req) {

	let main = {
		init: function(){
			this.sideNavControls.init();
			this.actionTopButtons.init();
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
				let el = $('.page-top-bottons #action-save');
				el.on('click', function(){
					let dataset = el.data();
					if(typeof dataset.target_form_id != 'undefined'){
						let formEl = $('#' + dataset.target_form_id);
						if(typeof dataset.action != 'undefined'){
							formEl.action = dataset.action;
						}

						if(typeof dataset.isajax != 'undefined' && parseInt(dataset.isajax) == 1){
							let jsonData = main._form._serialize(formEl).toJson();
							console.log('main main main', jsonData);
						}
						else {
							// formEl.submit();
						}
					}
				});
			}
		}
	};

	main.init();

	return main;
});