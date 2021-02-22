const path = require('path');
const exec = require('child_process').exec;

const DS = path.sep;
const ROOT = path.dirname(path.dirname(__dirname));

let opoinkCli = ROOT + DS + 'vendor' + DS + 'opoink' + DS + 'cli' + DS + 'src' + DS + 'opoink';
opoinkCli += ' Opoink\\Template\\Vue\\Generate:execute ';
let _argv = process.argv.splice(2);
opoinkCli += _argv.join(' ');

exec('php ' + opoinkCli, (error, stdOut, stdErr) => {
    console.log(stdOut);
    // try {
    //     let result = JSON.parse(stdOut);
    //     console.log(result);
    // }
    // catch(err) {
    //     console.log(err);
    // }
}); 