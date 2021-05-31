import * as $ from './../../../../node_modules/jquery';
import * as bootstrap from './../../../../node_modules/bootstrap';
bootstrap.Util.jQueryDetection();
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

        $('body').append(`
            <div id="`+modalId+`" `+attr+`>
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <`+name+`></`+name+`>
                    </div>
                </div>
            </div>
        `);

        let el = $(modalElId);

        let interval = setInterval(f => {
            if(el.length){
                Vue.component(options['component']);
                new Vue({ 
                    el: modalElId
                });

                $(modalElId).modal('show');
                $(modalElId).on('show.bs.modal', () => {
        
                });
                $(modalElId).on('hidden.bs.modal', () => {
                    $(modalElId).remove();
                    if(typeof this.onClose == 'function'){
                        this.onClose();
                    }
                });

                clearInterval(interval);
            }
        }, 10);
        this.modalCount++;
    }
}

export default Modal;