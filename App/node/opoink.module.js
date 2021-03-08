/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const path = require('path');
const DS = path.sep;
const ROOT = path.dirname(path.dirname(__dirname));
const fs = require('fs');
const execPhp = require('exec-php');

function getConfig() {
    return new Promise(resolve => {
        execPhp('./src/php/config.php', (error, php, outprint) => {
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

function moduleDir(targerDir, config){
    let modVueComs = [];
    let modVuecomponents = [];
    let modVueRoutes = [];

    let mod = config.modules
    for (var key of Object.keys(mod)) {
        let vendorDir = targerDir + DS + key;
        let vMods = mod[key];
        vMods.forEach(vMod => {
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
        });
    }

    // fs.readdirSync(targerDir).forEach(vfile => {
    //     let vendorDir = targerDir + DS + vfile;
    //     if (fs.statSync(vendorDir).isDirectory()){

    //         fs.readdirSync(vendorDir).forEach(mfile => {
    //             let modDir = vendorDir + DS + mfile;
    //             if (fs.statSync(modDir).isDirectory()){
    //                 fs.readdirSync(modDir).forEach(file => {
    //                     if(file == 'vue.components.ts'){
    //                         modVueComs.push(modDir+DS+file);
    //                     }
    //                     else if(file == 'components.json'){
    //                         modVuecomponents.push(modDir+DS+file);
    //                     }
    //                     else if(file == 'vue.routes.ts'){
    //                         modVueRoutes.push(modDir+DS+file);
    //                     }
    //                 });
    //             }
    //         });
    //     }
    // });

    return {
        modVueComs: modVueComs,
        modVuecomponents: modVuecomponents,
        modVueRoutes: modVueRoutes
    }
}

async function init() {
    let config = await getConfig();

    let vueComponentPath = './src/var/vue.components.ts';
    let vueComponentInjPath = './src/var/components.injection.ts';

    if(fs.existsSync(vueComponentPath)){
        fs.unlinkSync(vueComponentPath);
    }
    if(fs.existsSync(vueComponentInjPath)){
        fs.unlinkSync(vueComponentInjPath);
    }

    let targerDir = ROOT + DS + 'App'+DS+'Ext';
    let VueComs = moduleDir(targerDir, config);

    let content = "import Vue from './../../node_modules/vue/dist/vue.min';\n";
    content += "import VRouter from './../core/VueRouter';\n";
    content += "Vue.use(VRouter.VueRouter);\n\n";

    content += "const router = VRouter.vueRouter;\n\n";

    VueComs.modVueComs.forEach(target => {
        let _target = target.split(DS).join('/');
        content += "import '"+_target+"';\n";
    });

    VueComs.modVueRoutes.forEach(target => {
        let _target = target.split(DS).join('/');
        content += "import '"+_target+"';\n";
    });

    content += "\n\nconst app = new Vue({router}).$mount('#approot');\n";

    fs.mkdirSync(path.resolve(path.dirname(vueComponentPath)), { recursive: true });
    fs.writeFileSync(vueComponentPath, content);


    /** ================================================================== */
    /** ================================================================== */
    /** ==================== components.injection.ts ===================== */
    /** ================================================================== */
    /** ================================================================== */

    content = "declare function require(name:string);\n";
    content += "let componentInjection = [];\n\n";

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
    content += "export default injection;\n";

    fs.writeFileSync(vueComponentInjPath, content);


}

init();