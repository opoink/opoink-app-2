const fs = require('fs');
const path = require('path');

const DS = path.sep;
const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));

class StringParser {

    watcher = null;

    /**
     * set the watcher so that we can use it whenever needed
     * @param {*} watcher 
     */
    setWatcher(watcher){
        if(!this.watcher){
            this.watcher = watcher;
        }
    }

    /**
     * chaeck if the string is a valid url or not
     * @param {*} str 
     * @returns 
     */
    validURL(str) {
        // var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        //   '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        //   '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        //   '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        //   '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        //   '(\\#[-a-z\\d_]*)?$','i'); // fragment locator

        //var pattern = new RegExp('^((https?):\/\/)?([w|W]{3}\.)+[a-zA-Z0-9\-\.]{3,}\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$')
        //return !!pattern.test(str);
        return /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(str);
        
    }

    getImageSrc(){
        return new Promise(resolve => {
            setTimeout(f => {
                resolve(true)
            }, 10000)
        })
    }

    /**
     * this will extract img element and make the src
     * as an absolute path, in that case the file can be loadable
     * by file-loader
     * @param {*} source 
     * @param {*} resourcePath 
     * @returns 
     */
    async extractImgSrc(source, resourcePath){
        let regex = /<img.*?>/ig;
        let found = source.match(regex);
        if(found){
            for (const element of found) {
                let r = /src=("|').*?("|')/ig;
                let find = element.match(r);

                let newElement = '';
                if(find){
                    for (const src of find) {
                        let _url = src.replace('src="', '').replace('src=\'', '');
                        _url = _url.replace('"', '').replace('\'');

                        let isValidUrl = this.validURL(_url);
                        if(!isValidUrl){
                            let targetSourceDir = path.dirname(resourcePath)
                            let target = path.resolve(targetSourceDir + _url);
                            target = target.split(path.sep).join('/');
                            if (fs.existsSync(target)) {
                                newElement = element.replace(src, "src=\"" + target + "\"");
                                source = source.replace(element, newElement);
                            }  else {
                                console.log("File not found: " + target);
                                console.log("In: " + resourcePath);
                                console.log(element);
                                process.exit();
                            }
                        }
                    }
                }
            }
        }
        return source;
    }

    /**
     * extract the content and look for a string url()
     * @param {*} source 
     * @param {*} resourcePath 
     * @returns 
     */
    extractCssUrl(source, resourcePath, options=null, emitFile=null){
        let regex = /url\(.*?\)/ig;
        let found = source.match(regex);
    
        if(found){
            for (const url of found) {
                let _url = url.replace('url("', '').replace("url('", '');
                _url = _url.replace('")', '').replace("')", "");
                let isValidUrl = this.validURL(_url);
                if(!isValidUrl){
                    let targetSourceDir = path.dirname(resourcePath);
                    let target = path.resolve(targetSourceDir + _url);
                    target = target.split(path.sep).join('/');
                    
                    if(typeof emitFile === 'function'){
                        if (fs.existsSync(target)) {
                            let outputPath = '';
                            if(options){
                                outputPath = options.outputPath;
                            }
    
                            let fileName = path.basename(target);
    
                            outputPath += fileName;
                            let content = fs.readFileSync(target);
                            let assetInfo = { sourceFilename: target }
                            
                            emitFile(outputPath, content, null, assetInfo);
                            source = source.replace(url, "url(" + options.publicPath + '/' + fileName + ")");
                        }  else {
                            console.log("File not found: " + target);
                            console.log("In: " + resourcePath);
                            console.log(url);
                            process.exit();
                        }
                    }
                    else {
                        source = source.replace(url, "url("+target+")");
                    }
                }
            }
        }
        return source;
    }

    addCssComponentAttr(source, resourcePath, componentAttrId){
        let splitSourcePath = resourcePath.split(DS+'vue'+DS+'components'+DS);

        if(splitSourcePath.length > 1){
                

            let hashDirPath = componentAttrId.getHashDir(resourcePath);
            if(typeof componentAttrId.components[hashDirPath] == 'undefined'){
                source = "\n"+source;
                // let regex = /[(\r|\n)](\.|#)(.*?){[(\r|\n)]/ig;
                let regex = /[\#\.\w\-\,\+\~\>\s\n\r\t:]+(?=\s*\{)/ig;
                let found = source.match(regex);

                if(found){
                    found.forEach(selector => { 
                        
                        let newSelector = selector.replace(/(\r\n|\n|\r)/gm, "");

                        if(newSelector && newSelector.trim()){
                            let dirname = path.dirname(resourcePath);
                            let cai = componentAttrId.getComponentAttrId(dirname);
                            let attr = "[" + cai.component_value_prefix + "=\"" + cai.component_value +"\"]";

							let _selectors = selector.split(',');
							_selectors.forEach((_selector, _selectorKey) => {
								_selector = _selector.trim().replace(/(\r\n|\n|\r)/gm, "");

								let _selectorItems = _selector.split(' ');
								_selectorItems.forEach((siValue, siKey) => {
									siValue = siValue.split(':');
									let cssSymbols = ['~', '+', '>'];
									if(cssSymbols.indexOf(siValue[0]) == -1){
										siValue[0] = siValue[0] + attr;
									}
									siValue = siValue.join(':');
									_selectorItems[siKey] = siValue;
								});
								_selectors[_selectorKey] = _selectorItems.join(' ');
							});
							_selectors = _selectors.join(', ');
                            newSelector = _selectors;
                            // newSelector = selector.trim() + attr;
                            // newSelector = newSelector.replace(/(\s){/gm, attr + " {");
                            source = source.replace(selector, "\n"+newSelector+" ");
                        }
                    });
                }
            }
        }
        return source;
    }
}

module.exports = StringParser;