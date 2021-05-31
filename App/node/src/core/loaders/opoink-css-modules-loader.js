/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const StringParser = require('../string.parser');
const stringParser = new StringParser();
module.exports = async function(source) {
    const options = this.getOptions();
    let config = options.watcher.config;
    let _resourcePath = this.resourcePath;

    if(typeof config['theme'] != 'undefined'){
        let tName = config['theme'];
        let themRoot = ROOT + DS + 'theme' + DS + tName;
        let targetFile = themRoot + resourcePath.replace(ROOT, '');
        if (fs.existsSync(targetFile)) {
            /**
             * we need to change the url()
             * to b an absolute path because the resourcePath path
             * opoink-css-theme-loader, only change the content of the
             * source, but webpack was still watching for the orignal file
             */
             _resourcePath = targetFile;
        }
    }
    source = stringParser.extractCssUrl(source, _resourcePath);
    source = stringParser.addCssComponentAttr(source, this.resourcePath, options.componentAttrId);
    return source;
}