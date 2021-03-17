/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const path = require('path');
const execPhp = require('exec-php');
const fs = require('fs');
const loaderUtils = require('loader-utils');

const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));
const DS = path.sep;

function getConfig() {
    return new Promise(resolve => {
        execPhp('./../../php/config.php', (error, php, outprint) => {
            php.config((error, result) => {
                if(error){
                    throw new Error('System config not found.');
                } else {
                    resolve(result)
                }
            });
        });
    });
}

module.exports = async function(source) {
    const options = loaderUtils.getOptions(this);
    let config = await getConfig();
    if(typeof config['theme'] != 'undefined'){
        let tName = config['theme'];
        
        let {resourcePath} = this;

        let targetFile = ROOT + DS + 'theme' + DS + tName + resourcePath.replace(ROOT, '');
        if (fs.existsSync(targetFile)) {
            options.watcher.addFileToWatch(targetFile);
            source = fs.readFileSync(targetFile,'utf8');
        }
    }
    return source;
}
