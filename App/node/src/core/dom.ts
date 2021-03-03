// declare function require(name:string);

import 'jquery';
// let path = require('path');
// let fs = require('fs');

// console.log('DS DS', DS);
// console.log('ROOT ROOT', ROOT);

class injector {
    
    /**
     * inititalize the injection of the template 
     * in DomDocument
     */
    inject(el){
        let newDom = $(el);

        let target = newDom.find('#testinject');
        target.append('<apptwo></apptwo>');

        this.findComponents();
        // newDom.append('<apptwo></apptwo>');
        return newDom[0].outerHTML;
    }

    /**
     * look for the components 
     */
    findComponents(){
        // let targerDir = ROOT+DS+'App'+DS+'Ext';
        // console.log(targerDir);
    }
}

export default new injector();