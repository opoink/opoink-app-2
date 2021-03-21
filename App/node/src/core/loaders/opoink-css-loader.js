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

    const {resourcePath} = this;
    source = stringParser.extractCssUrl(source, resourcePath);
    source = stringParser.addCssComponentAttr(source, resourcePath, options.componentAttrId);
    return source;
};