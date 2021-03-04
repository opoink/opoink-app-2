/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const path = require('path');
const DS = path.sep;
const ROOT = path.dirname(path.dirname(__dirname));
const fs = require('fs');

let vueComponentPath = './src/var/vue.components.ts';
let vueComponentInjPath = './src/var/components.injection.ts';

if(fs.existsSync(vueComponentPath)){
    fs.unlinkSync(vueComponentPath);
}
if(fs.existsSync(vueComponentInjPath)){
    fs.unlinkSync(vueComponentInjPath);
}

let moduleDir = function(targerDir){
    let modVueComs = [];
    let modVuecomponents = [];
    let modVueRoutes = [];
    fs.readdirSync(targerDir).forEach(vfile => {
        let vendorDir = targerDir + DS + vfile;
        if (fs.statSync(vendorDir).isDirectory()){

            fs.readdirSync(vendorDir).forEach(mfile => {
                let modDir = vendorDir + DS + mfile;
                if (fs.statSync(modDir).isDirectory()){
                    fs.readdirSync(modDir).forEach(file => {
                        if(file == 'vue.components.ts'){
                            modVueComs.push(modDir+DS+file);
                        }
                        else if(file == 'components.json'){
                            modVuecomponents.push(modDir+DS+file);
                        }
                        else if(file == 'vue.routes.ts'){
                            modVueRoutes.push(modDir+DS+file);
                        }
                    });
                }
            });
        }
    });

    return {
        modVueComs: modVueComs,
        modVuecomponents: modVuecomponents,
        modVueRoutes: modVueRoutes
    }
}

let targerDir = ROOT + DS + 'App'+DS+'Ext';
let VueComs = moduleDir(targerDir);

let content = "import Vue from './../../node_modules/vue/dist/vue.esm';\n";
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

