/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*
* this is refferenced at https://github.com/pigcan/extra-watch-webpack-plugin
*/
const path = require('path');
const validateOptions = require('schema-utils');
const uniq = require('lodash.uniq');
const isGlob = require('is-glob');
const glob = require('glob');
const fs = require('fs');
const execPhp = require('exec-php');
const DS = path.sep;
const ROOT = path.dirname(path.dirname(path.dirname(path.dirname(path.dirname(__dirname)))));


class WatchOpoinkThemeFiles {
    config = [];
    filesToAddInWatch = {};

    constructor(options = {}) {
        this.options = options
    }

    getFileAndContextDeps(compilation, files, dirs, cwd) {
        const { fileDependencies, contextDependencies } = compilation;
        const isWebpack4 = compilation.hooks;
        let fds = isWebpack4 ? [...fileDependencies] : fileDependencies;
        let cds = isWebpack4 ? [...contextDependencies] : contextDependencies;

        if (files.length > 0) {
            files.forEach((pattern) => {
                let f = pattern;
                if (isGlob(pattern)) {
                    f = glob.sync(pattern, {
                        cwd,
                        dot: true,
                        absolute: true,
                    });
                }
                fds = fds.concat(f);
            });
            fds = uniq(fds);
        }
        if (dirs.length > 0) {
            cds = uniq(cds.concat(dirs));
        }

        return {
            fileDependencies: fds,
            contextDependencies: cds,
        };
    }

    getConfig() {
        return new Promise(resolve => {
            execPhp('./../../php/config.php', (error, php, outprint) => {
                php.config((error, result) => {
                    if(error){
                        throw new Error('System config not found.');
                    } else {
                        this.config = result;
                        resolve(result)
                    }
                });
            });
        });
    }

    getChangedFiles(compiler) {
        if (compiler.modifiedFiles) {
            const changedFiles = Array.from(compiler.modifiedFiles, (file) => `\n  ${file}`).join('');

            if(typeof this.config['theme'] != 'undefined'){
                let d = DS + 'App' + DS + 'theme' + DS + this.config['theme'];
                let splitTarget = changedFiles.split(d);
                if(splitTarget.length){
                    let extDir = ROOT + DS + 'App';
                    let targetFile = extDir + splitTarget[1];
                    if (fs.existsSync(targetFile)) {
                        let content = fs.readFileSync(targetFile,'utf8');
                        fs.writeFileSync(targetFile, content);
                    }
                }
            }
        }
    }

    addFileToWatch(target){
        let key = target.split(DS).join("_");
        this.filesToAddInWatch[key] = target;
    }

    // Define `apply` as its prototype method which is supplied with compiler as its argument
    apply(compiler) {
        let { files, dirs } = this.options;
        const { cwd } = this.options;
        files = typeof files === 'string' ? [files] : files;
        dirs = typeof dirs === 'string' ? [dirs] : dirs;

        this.getConfig();
        
        if (compiler.hooks) {
            compiler.hooks.beforeCompile.tap('before-compile', (compilation) => {
            });

            compiler.hooks.afterCompile.tap('after-compile', (compilation) => {
                this.getChangedFiles(compiler);
                const {
                    fileDependencies,
                    contextDependencies,
                } = this.getFileAndContextDeps(compilation, files, dirs, cwd);

                if (files.length > 0) {
                    fileDependencies.forEach((file) => {
                        compilation.fileDependencies.add(file);
                    });
                }
                Object.keys(this.filesToAddInWatch).forEach((key) => {
                    compilation.fileDependencies.add(this.filesToAddInWatch[key]);
                });
                if (dirs.length > 0) {
                    contextDependencies.forEach((context) => {
                        compilation.contextDependencies.add(context);
                    });
                }
            });
        }
    }
}

module.exports = WatchOpoinkThemeFiles;