/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
const path = require('path');
const DS = path.sep;
const ROOT = path.dirname(path.dirname(__dirname));
const fs = require('fs');

if(fs.existsSync('./src/vue.components.ts')){
    fs.unlinkSync('./src/vue.components.ts');
}

let moduleDir = function(targerDir){
    let modVueComs = [];
    let modVuecomponents = [];
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
                            let components_json = require(modDir+DS+file);
                            components_json.forEach(val => {
                                modVuecomponents.push(val);
                            });
                        }
                    });
                }
            });
        }
    });

    return {
        modVueComs: modVueComs,
        modVuecomponents: modVuecomponents,
    }
}

let targerDir = ROOT + DS + 'App'+DS+'Ext';
let VueComs = moduleDir(targerDir);

let contect = "import Vue from 'vue';\n\n";

VueComs.modVueComs.forEach(target => {
    let _target = target.split(DS).join('/');
    contect += "import '"+_target+"';\n";
});

contect += "\nnew Vue({\n";
contect += "\tel: '#approot',\n";
contect += "\tbeforeMount(){}\n";
contect += "});";

fs.writeFileSync('./src/vue.components.ts', contect);

contect = JSON.stringify(VueComs.modVuecomponents, null, "\t");
fs.writeFileSync('./src/vue.components.json', contect);

