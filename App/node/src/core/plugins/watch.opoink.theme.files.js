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


class WatchOpoinkThemeFiles {
    // Define `apply` as its prototype method which is supplied with compiler as its argument

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

    getChangedFiles(compiler) {
        if (compiler.modifiedFiles) {
            const changedFiles = Array.from(compiler.modifiedFiles, (file) => `\n  ${file}`).join('');
            console.log('===============================');
            console.log('FILES CHANGED:', changedFiles);
            console.log('===============================');
        }
    }

    apply(compiler) {
        let { files, dirs } = this.options;
        const { cwd } = this.options;
        files = typeof files === 'string' ? [files] : files;
        dirs = typeof dirs === 'string' ? [dirs] : dirs;
        
        if (compiler.hooks) {
            compiler.hooks.afterCompile.tap('after-compile', (compilation) => {
                const changedFile = this.getChangedFiles(compiler);
                const {
                    fileDependencies,
                    contextDependencies,
                } = this.getFileAndContextDeps(compilation, files, dirs, cwd);

                if (files.length > 0) {
                    fileDependencies.forEach((file) => {
                        compilation.fileDependencies.add(file);
                    });
                }
                if (dirs.length > 0) {
                    contextDependencies.forEach((context) => {
                        compilation.contextDependencies.add(context);
                    });
                }
            });

            // compiler.hooks.watchRun.tap('WatchOpoinkThemeFiles', (comp) => {
            //     const changedTimes = comp.watchFileSystem.watcher.mtimes;
            //     const changedFiles = Object.keys(changedTimes)
            //       .map(file => `\n  ${file}`)
            //       .join('');
            //     if (changedFiles.length) {
            //       console.log("====================================")
            //       console.log('NEW BUILD FILES CHANGED:', changedFiles);
            //       console.log("====================================")
            //     }
            // });
        }
    }
}

module.exports = WatchOpoinkThemeFiles;