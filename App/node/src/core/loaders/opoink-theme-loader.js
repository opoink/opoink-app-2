/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const path = require('path');
const fs = require('fs');
const loaderUtils = require('loader-utils');
const jsdom = require('jsdom');
const $ = require('jquery')(new jsdom.JSDOM().window);

const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));
const DS = path.sep;

function validURL(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
      '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
      '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
      '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
      '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
      '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(str);
}

function extractSrc(source, resourcePath){
    let regex = /<img.*?>/ig;
    let found = source.match(regex);
    if(found){
        found.forEach(element => {
            let r = /src=("|').*?("|')/ig;
            let find = element.match(r);

            let newElement = '';
            find.forEach(src => {
                let _url = src.replace('src="', '').replace('src=\'', '');
                _url = _url.replace('"', '').replace('\'');

                let isValidUrl = validURL(_url);
                if(!isValidUrl){
                    let targetSourceDir = path.dirname(resourcePath)
                    let target = path.resolve(targetSourceDir + _url);
                    target = target.split(path.sep).join('/');
                    if (fs.existsSync(target)) {
                        newElement = element.replace(src, "src=\""+target+"\"");
                        source = source.replace(element, newElement);
                    }  else {
                        console.log("File not found: " + target);
                        console.log("In: " + resourcePath);
                        console.log(element);
                        process.exit();
                    }
                }
            });
        });
        return source;
    }
}

module.exports = async function(source) {
    const opoinkWatcher = loaderUtils.getOptions(this);
    let config = opoinkWatcher.watcher.config;

    if(typeof config['theme'] != 'undefined'){
        let tName = config['theme'];
        let themRoot = ROOT + DS + 'theme' + DS + tName;
        
        let {resourcePath} = this;

        let targetFile = themRoot + resourcePath.replace(ROOT, '');
        if (fs.existsSync(targetFile)) {
            opoinkWatcher.watcher.addFileToWatch(targetFile);
            source = fs.readFileSync(targetFile,'utf8');
            source = extractSrc(source, targetFile);
        }
    }
    return source;
}
