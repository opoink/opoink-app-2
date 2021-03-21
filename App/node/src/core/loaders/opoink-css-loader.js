/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const loaderUtils = require('loader-utils');
const path = require('path');
const StringParser = require('../string.parser');

const stringParser = new StringParser();

module.exports = async function(source) {
    const options = loaderUtils.getOptions(this);

    // let urlPath = '/public/vuedist/assets/images';
    // let publicPath = '/public/vuedist/assets/images';

    // if(typeof options != 'undefined'){
    //     if(typeof options['urlPath'] != 'undefined'){
    //         urlPath = options.urlPath;
    //     }
    //     if(typeof options['publicPath'] != 'undefined'){
    //         publicPath = options.publicPath;
    //     }
    // }

    const {resourcePath} = this;
    source = stringParser.extractCssUrl(source, resourcePath);
    stringParser.addCssComponentAttr(source, resourcePath);
    return source;
};