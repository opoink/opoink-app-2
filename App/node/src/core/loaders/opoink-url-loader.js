/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const loaderUtils = require('loader-utils');
const path = require('path');

function validURL(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
      '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
      '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
      '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
      '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
      '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(str);
  }

let changeUrlString = function(source, resourcePath){
    let regex = /url\(.*?\)/ig;
    let found = source.match(regex);

    if(found){
        found.forEach(url => {
            let _url = url.replace('url("', '').replace('url(\'', '');
            _url = _url.replace('")', '').replace('\')');

            let isValidUrl = validURL(_url);
            if(!isValidUrl){
                let targetSourceDir = path.dirname(resourcePath)
                let target = path.resolve(targetSourceDir + _url);
                target = target.split(path.sep).join('/');
                source = source.replace(url, "url(\""+target+"\")");
            }
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

    const {resourcePath} = this;
    // let dirname = path.dirname(resourcePath);

    source = changeUrlString(source, resourcePath);

    return source;
};