/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const path = require('path');
const fs = require('fs');
const loaderUtils = require('loader-utils');
const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));
const DS = path.sep;

module.exports = async function(source) {
    const options = loaderUtils.getOptions(this);

	if(options.isProd){
		let {resourcePath} = this;

		let targetFile = path.dirname(resourcePath) + DS + options.replacement_name;
		if (fs.existsSync(targetFile)) {
			source = fs.readFileSync(targetFile,'utf8');
		}
	}
    return source;
}
