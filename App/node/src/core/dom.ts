declare function require(name:string);

import 'jquery';
import injection from './../components.injection';

class injector {
    
    types = {
        before: 'before',
        after: 'after',
        append: 'append',
        prepend: 'prepend'
    }


    /**
     * inititalize the injection of the template 
     * in DomDocument
     */
    inject(el, name){
        let newDom = $(el);

        injection.forEach(com => {
            if(typeof com.inject_to != 'undefined'){
                com.inject_to.forEach(component => {
                    let ComName = component.component_name.toLowerCase();

                    if(ComName == name){
                        let eltag = '<'+com.component_name+'></'+com.component_name+'>';
                        let type  = 'append';
                        if(typeof component.inject_type != 'undefined'){
                            type  = component.inject_type;
                        }

                        if(typeof component.element_id != 'undefined'){
                            /** 
                             * there element id is set here 
                             * so we will look for that element then make an injection
                             * inject to the bottom if not found
                             */
                            let eid = component.element_id;
                            let target = newDom.find('#'+eid);

                            if(target.length){
                                this.injectElement(target, type, eltag);
                            } else {
                                /** 
                                 * we will inject into the component 
                                 * either append or prepend only
                                 */
                                this.injectElement(newDom, type, eltag);
                            }
                        } else {
                            /** 
                             * we will inject into the component 
                             * either append or prepend only
                             */
                            this.injectElement(newDom, type, eltag);
                        }
                    }
                });
            }
        });
        return newDom[0].outerHTML;
    }

    /**
     * inject the element into designated area
     */
    injectElement(el, type, eltag){
        if(type == this.types.before){
            $( el ).before( eltag );
        } else if (type == this.types.after){
            $( el ).after( eltag );
        } else if (type == this.types.prepend){
            el.prepend(eltag);
        } else {
            el.append(eltag);
        }
    }
}

export default new injector();