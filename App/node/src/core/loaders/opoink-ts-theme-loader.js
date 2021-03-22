// const loaderUtils = require('loader-utils');
// const path = require('path');
// const fs = require('fs');
// const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));
// const DS = path.sep;

module.exports = async function(source) {
    // const options = loaderUtils.getOptions(this);
    // const {resourcePath} = this;
    // let config = options.watcher.config;

    // if(typeof config['theme'] != 'undefined'){
    //     let tName = config['theme'];
    //     let themRoot = ROOT + DS + 'theme' + DS + tName;
    //     let targetFile = themRoot + resourcePath.replace(ROOT, '');
    //     if (fs.existsSync(targetFile)) {
    //         console.log(`Replace [${this.resourcePath}] -> [${targetFile}]`);
    //         this.addDependency(targetFile);
    //         source = fs.readFileSync(targetFile,'utf8');
    //     }
    // }
    console.log(source);

    return source;
}