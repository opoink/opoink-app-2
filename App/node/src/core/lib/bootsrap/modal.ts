import * as $ from './../../../../node_modules/jquery';
import * as bootstrap from './../../../../node_modules/bootstrap';
// bootstrap.Util.jQueryDetection();
import Vue from './../../../../node_modules/vue/dist/vue.min';

class Modal {

    onClose: any;
    modalCount:number = 0;

    setComponent(){

    }
    /**
     * 
     * @param options 
     */
    openModal(options:object){
        let name = options['component'].extendOptions.name;
        let modalId = 'modal_'+this.modalCount;
        let modalElId = '#'+modalId;

        let attr:any = '';
        if(typeof options['attributes'] != 'undefined') {
            attr = options['attributes'];
        }

		let mdAddClass = '';
		if(typeof options['modal_dialog_class'] != 'undefined'){
			mdAddClass += options['modal_dialog_class'];
		}
		let mdAddId = '';
		if(typeof options['modal_dialog_id'] != 'undefined'){
			mdAddId += options['modal_dialog_id'];
		}
		if(mdAddId){
			mdAddId = 'id="'+mdAddId+'"';
		}

        $('body').append(`
			<div id="`+modalId+`" class="modal" tabindex="-1">
				<div `+mdAddId+` class="modal-dialog `+mdAddClass+`" `+attr+`>
					<`+name+`></`+name+`>
				</div>
			</div>

        `);

		let interval = setInterval(f => {
			let myModalEl = document.getElementById(modalId);
			if(myModalEl){
                let modalVue = new Vue({ 
                    el: modalElId
                });

				let modalConfig = {
					backdrop: 'static',
					keyboard: false
				}
				if(typeof options['modal_options'] != 'undefined'){
					modalConfig = options['modal_options'];
				}

				setTimeout(() => {
					/**
					 * we need to get the element here again
					 * because the element fetch earlier does not have the component
					 * needed
					 */
					let myModalEl = document.getElementById(modalId);
					var modal = new bootstrap.Modal(myModalEl, modalConfig);
					modal.show();
	
					let componentService = options['component']['extendOptions'].data().vue;
					if(typeof componentService.init == 'function'){
						componentService.init();
					}

					componentService.hideModal = (data:any = null) => {
						if(typeof this.onClose == 'function'){
							this.onClose(data);
						}
						modal.hide();
					}
	
					myModalEl.addEventListener('hidden.bs.modal', function (event) {
						$(modalElId).remove();
					});
				}, 100);

				clearInterval(interval);
			}
		}, 100);


        // let el = $(modalElId);

        // let interval = setInterval(f => {
        //     if(el.length){
        //         Vue.component(options['component']);
        //         new Vue({ 
        //             el: modalElId
        //         });

		// 		if(typeof options['modal_options'] != 'undefined'){
		// 			$(modalElId).modal(options['modal_options']);
		// 		} else {
		// 			$(modalElId).modal({
		// 				backdrop: 'static',
		// 				keyboard: false
		// 			});
		// 		}
				
		// 		try {

		// 			let componentService = options['component']['extendOptions'].data().vue;
					
		// 			componentService['modalElId'] = modalElId;
		// 			if(typeof componentService.init == 'function'){
		// 				componentService.init();
		// 			}
		// 			if(typeof this.onClose == 'function'){
		// 				componentService.onModalClose = this.onClose;
		// 			}

		// 			$(modalElId).on('show.bs.modal', () => {
	
		// 			});
		// 			$(modalElId).on('hidden.bs.modal', () => {
		// 				$(modalElId).remove();
		// 			});
		// 		}
		// 		catch(err) {
		// 			console.log(err);
		// 		}

        //         clearInterval(interval);
        //     }
        // }, 10);
        this.modalCount++;
    }
}

export default Modal;
