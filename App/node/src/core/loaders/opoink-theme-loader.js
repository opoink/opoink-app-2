const path = require('path');
const execPhp = require('exec-php');
const fs = require('fs');
const webpack = require('webpack');

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
    let config = await getConfig();
    if(typeof config['theme'] != 'undefined'){
        let tName = config['theme'];
        
        let {resourcePath, server} = this;

        let targetHtml = ROOT + DS + 'theme' + DS + tName + resourcePath.replace(ROOT, '');
        if (fs.existsSync(targetHtml)) {
            source = fs.readFileSync(targetHtml,'utf8');
        }
    }
    return source;
}
