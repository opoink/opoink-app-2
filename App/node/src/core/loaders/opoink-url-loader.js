const loaderUtils = require('loader-utils');
const path = require('path');
const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));

let changeUrlString = function(source, dirname){
    let regex = /url\(.*?\)/ig;
    let found = source.match(regex);

    let extDir = ROOT + '/Ext/';

    if(found){
        found.forEach(url => {
            let u = url.replace('url("', '').replace('url(\'', '');
            u = u.replace('")', '').replace('\')');
            let target = extDir + u;
            target = target.split(path.sep).join('/');
            source = source.replace(url, "url(\""+target+"\")");
        });
    }
    return source;
}

module.exports = function(source) {
    const options = loaderUtils.getOptions(this);

    let urlPath = '/public/vuedist/assets/images';
    let publicPath = '/public/vuedist/assets/images';

    if(typeof options != 'undefined'){
        if(typeof options['urlPath'] != 'undefined'){
            urlPath = options.urlPath;
        }
        if(typeof options['publicPath'] != 'undefined'){
            publicPath = options.publicPath;
        }
    }

    // const {resourcePath} = this;
    // let dirname = path.dirname(resourcePath);

    source = changeUrlString(source);

    return source;
};