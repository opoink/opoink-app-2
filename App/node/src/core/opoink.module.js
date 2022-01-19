/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*
* this is used in watch.opoink.theme.files.js
*/
const path = require('path');
const DS = path.sep;
const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(__dirname))));
const fs = require('fs');
// const execPhp = require('exec-php');
const _execPHP = require('./../php/php.exec')();


function getConfig() {
    return new Promise(resolve => {
		_execPHP.parseFile(path.resolve('./src/php/config.php'), 
        function(error, stdout, stderr){
            if(error){
                throw new Error('System config not found.');
            } else {
                let cfg = JSON.parse(stdout);
                resolve(cfg);
            }
        });
        // execPhp(path.resolve('./src/php/config.php'), (error, php, outprint) => {
        //     php.config((error, result) => {
        //         if(error){
        //             throw new Error('System config not found.');
        //         } else {
        //             resolve(result)
        //         }
        //     });
        // });
    });
}

function getCss(){
    return new Promise(resolve => {
        _execPHP.parseFile(path.resolve('./src/php/css.php'), 
        function(error, stdout, stderr){
            if(error){
                throw new Error(error);
            } else {
                let css = JSON.parse(stdout);
                resolve(css);
            }
        });
    })
}

function geFile(phpFile){
    return new Promise(resolve => {
        _execPHP.parseFile(path.resolve('./src/php/' + phpFile), 
        function(error, stdout, stderr){
            if(error){
                throw new Error(error);
            } else {
                let content = JSON.parse(stdout);
                resolve(content);
            }
        });
    })
}

function moduleDir(targerDir, config){
    let modVueComs = [];
    let modVuecomponents = [];
    let modVueRoutes = [];
    let modCss = [];

    return new Promise(resolve => {
        if(typeof config.modules != 'undefined'){
            let mod = config.modules;

            for (var key of Object.keys(mod)) {
                let vendorDir = targerDir + DS + key;
                let vMods = mod[key];

                for (const vMod of vMods) {
                    let modDir = vendorDir + DS + vMod;
                    let vComTs = modDir + DS + 'vue.components.ts';
                    let vComJson = modDir + DS + 'components.json';
                    let vRoutes = modDir + DS + 'vue.routes.ts';
                    
                    if (fs.existsSync(vComTs)) {
                        modVueComs.push(vComTs);
                    }
                    if (fs.existsSync(vComJson)) {
                        modVuecomponents.push(vComJson);
                    }
                    if (fs.existsSync(vRoutes)) {
                        modVueRoutes.push(vRoutes);
                    }
                }
            }
        
            resolve ({
                modVueComs: modVueComs,
                modVuecomponents: modVuecomponents,
                modVueRoutes: modVueRoutes,
                modCss: modCss
            });
        } else {
            // resolve('There was no installed module found. Please login to the Opoink system UI and install your module.');
            resolve(null);
        }
    })

}

async function init() {
    let config = await getConfig();

    let vueComponentPath = './src/var/vue.components.ts';
    let vueComponentInjPath = './src/var/components.injection.js';
    let moduleLangPath = './src/var/languages.json';

    if(fs.existsSync(vueComponentPath)){
        // fs.unlinkSync(vueComponentPath);
    }
    if(fs.existsSync(vueComponentInjPath)){
        // fs.unlinkSync(vueComponentInjPath);
    }

    let targerDir = ROOT + DS + 'App'+DS+'Ext';
    
    let VueComs = await moduleDir(targerDir, config);
    if(VueComs){
        let content = "import Vue from './../../node_modules/vue/dist/vue.min';\n";
        content += "import VRouter from './../core/VueRouter';\n";
		content += "import LangService from './../core/lib/std/LangService';\n";
        content += "Vue.use(VRouter.VueRouter);\n\n";

		content += "Vue.filter('lang', function (key:string, language:string, values:any = null) {\n";
		content += "\treturn LangService.getLang(key, language, values);\n";
		content += "});\n\n";

        content += "const router = VRouter.vueRouter;\n\n";

        VueComs.modVueComs.forEach(target => {
            let _target = target.split(DS).join('/');
            content += "import '"+_target+"';\n";
        });

        VueComs.modVueRoutes.forEach(target => {
            let _target = target.split(DS).join('/');
            content += "import '"+_target+"';\n";
        });

        let css = await getCss(config);
        css.forEach(css => {
            content += "import '"+css+"';\n";
        });

        content += "\n\nconst app = new Vue({router}).$mount('#approot');\n";

        fs.mkdirSync(path.resolve(path.dirname(vueComponentPath)), { recursive: true });
        fs.writeFileSync(vueComponentPath, content);


        /** ================================================================== */
        /** ================================================================== */
        /** ==================== components.injection.ts ===================== */
        /** ================================================================== */
        /** ================================================================== */

        content = "let componentInjection = [];\n\n";

        VueComs.modVuecomponents.forEach(val => {
            content += "componentInjection.push(";
            content += "require('"+ val.split(DS).join('/') +"')";
            content += ");\n";
        });

        content += "\nlet injection = [];\n";
        content += "\ncomponentInjection.forEach(val => {\n";
        content += "\tval.forEach(val2 => {\n";
        content += "\t\tinjection.push(val2);\n";
        content += "\t});\n";
        content += "});\n";
        content += "module.exports = injection;\n";

        fs.writeFileSync(vueComponentInjPath, content);

		/** ================================================================== */
		/** ================================================================== */
		/** ==================== languages ===================== */
		/** ================================================================== */
		/** ================================================================== */
		let langContent = await geFile('lang.php');
		langContent = JSON.stringify(langContent);
		fs.writeFileSync(moduleLangPath, langContent);
    } else {
        console.log('There was no installed module found. Please login to the Opoink system UI and install your module.');
        process.exit();
    }
}

function run(){
    return new Promise(res => {
        init();
        res();
    });
}

module.exports = run;
