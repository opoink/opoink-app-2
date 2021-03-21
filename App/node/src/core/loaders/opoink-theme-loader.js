/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const path = require('path');
const fs = require('fs');
const loaderUtils = require('loader-utils');
const StringParser = require('./../string.parser');
const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));
const DS = path.sep;

const stringParser = new StringParser();

module.exports = async function(source) {
    const options = loaderUtils.getOptions(this);
    let config = options.watcher.config;

    if(typeof config['theme'] != 'undefined'){
        let tName = config['theme'];
        let themRoot = ROOT + DS + 'theme' + DS + tName;
        
        let {resourcePath} = this;

        let targetFile = themRoot + resourcePath.replace(ROOT, '');
        if (fs.existsSync(targetFile)) {
            console.log(`Replace [${this.resourcePath}] -> [${targetFile}]`);
            this.addDependency(targetFile);


            /////////////////////////////////////////////////////////
            // options.watcher.addFileToWatch(targetFile);
            source = fs.readFileSync(targetFile,'utf8');

            /**
             * we need to change the img src or a url()
             * to b an absolute path because the resourcePath path
             * is not changed, we only change the content of the
             * source, but webpack was still watching for the orignal file
             */
            source = stringParser.extractImgSrc(source, targetFile, options, this.emitFile);
            source = stringParser.extractCssUrl(source, targetFile, options, this.emitFile);
        }
    }
    return source;
}
