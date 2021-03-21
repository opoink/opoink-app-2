const crypto = require('crypto');

class ComponentAttrId {
    
    /**
     * a object list of all compenents
     */
    components = {};

    /**
     * incremental count of the total element found
     */
     componentCount = 0;

    componentCountPrefix = 'opoink_vue_component_';

    /**
     * @param {*} content 
     * @returns a sha1 hash of the dir name
     */
    getHashDir(content){
        let shasum = crypto.createHash('sha1');
        return shasum.update(content).digest('hex');
    }

    /**
     * @returns the eleent attr the will be added during the build
     */
    getComAttr(){
        this.componentCount++;
        return this.componentCountPrefix + this.componentCount;
    }

    /**
     * add the new element in the list this will be used for adding
     * the attr to the css for modular use
     * @param {*} dirname 
     */
    addComponent(dirname, hash=null){
        if(!hash){
            hash = this.getHashDir(dirname);
        }
        this.components[hash] = {
            dirname: dirname,
            component_attr: this.getComAttr(),
            component_value: this.componentCount,
            component_value_prefix: this.componentCountPrefix
        }

        return this.components[hash];
    }

    /**
     * return object of component html attribute value
     * if the component does ot exists add it
     * @param {*} dirname 
     * @returns 
     */
    getComponentAttrId(dirname){
        let hash = this.getHashDir(dirname);
        if(typeof this.components[hash] == 'undefined'){
            return this.addComponent(dirname, hash);
        } else {
            return this.components[hash];
        }
    }
}
module.exports = ComponentAttrId;