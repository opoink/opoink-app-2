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
    if(typeof config['theme'] != 'undefined'){
        let tName = config['theme'];
        let themRoot = ROOT + DS + 'theme' + DS + tName;
        let targetFile = themRoot + resourcePath.replace(ROOT, '');
        if (fs.existsSync(targetFile)) {
            console.log(`Replace [${this.resourcePath}] -> [${targetFile}]`);
            this.addDependency(targetFile);
            source = fs.readFileSync(targetFile,'utf8');
        }
    }
    return source;
};