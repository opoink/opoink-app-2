/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const loaderUtils = require('loader-utils');
const path = require('path');
const fs = require('fs');
const StringParser = require('../string.parser');
const stringParser = new StringParser();
const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));
const DS = path.sep;

module.exports = async function(source) {
    const options = loaderUtils.getOptions(this);
    let config = options.watcher.config;

    const {resourcePath} = this;
    let _resourcePath = resourcePath;
    if(typeof config['theme'] != 'undefined'){
        let tName = config['theme'];
        let themRoot = ROOT + DS + 'theme' + DS + tName;
        let targetFile = themRoot + resourcePath.replace(ROOT, '');
        if (fs.existsSync(targetFile)) {
            console.log(`Replace [${this.resourcePath}] -> [${targetFile}]`);
            this.addDependency(targetFile);
            source = fs.readFileSync(targetFile,'utf8');

            /**
             * we need to change the url()
             * to b an absolute path because the resourcePath path
             * is not changed, we only change the content of the
             * source, but webpack was still watching for the orignal file
             */
            _resourcePath = targetFile;
        }
    }

    source = stringParser.extractCssUrl(source, _resourcePath);
    source = stringParser.addCssComponentAttr(source, resourcePath, options.componentAttrId);
    return source;
};