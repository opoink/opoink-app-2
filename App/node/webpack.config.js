const path = require('path');

const DS = path.sep;
const ROOT = path.dirname(path.dirname(__dirname));

const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const WebpackBeforeBuildPlugin = require('before-build-webpack');
const exec = require('child_process').exec;
const appConfig = require('./src/app.config.json');
const fs = require('fs');

var today = new Date();
var insec = parseInt(today.valueOf() / 1000); 

var plugins = [];

plugins.push(new webpack.ProvidePlugin({
    $: 'jquery',
    jQuery: 'jquery'
}));

plugins.push(new webpack.DefinePlugin({
    DS: JSON.stringify(DS),
    ROOT: JSON.stringify(ROOT)
}));

plugins.push(new HtmlWebpackPlugin({
    inject: 'body',
    template: './src/index.html',
    publicPath: '/public/vuedist'
}));
plugins.push(new MiniCssExtractPlugin({
    filename: appConfig.prod ? insec + 'style.css' : 'style.css' 
}));
plugins.push(new CleanWebpackPlugin({
    dry: false,
    dangerouslyAllowCleanPatternsOutsideProject: true,
    cleanBeforeEveryBuildPatterns: [path.resolve(__dirname, appConfig.ouputPath)]
}));
plugins.push(new WebpackBeforeBuildPlugin(function(stats, callback) {
    callback();
}));

var config = {
    entry: './src/app.ts',
    target: 'node',
    output: {
        path: path.resolve(__dirname, appConfig.ouputPath),
        filename: appConfig.prod ? insec + appConfig.outputFileName : appConfig.outputFileName
    },
    module: {
        rules: [
            { test: /\.ts$/, use: 'awesome-typescript-loader' },
            { test: /\.js$/, use: 'babel-loader' },
            { 
                test: /\.scss$/, 
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: '/' + appConfig.publicPath,
                        }
                    },
                    'css-loader',
                ] 
            },
            { test: /\.html$/, use: 'html-loader' },
            {
                test: /\.(gif|png|jpe?g|svg)$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'assets/images/',
                            publicPath: 'assets/images/'
                        }
                    }
                ],
            }
        ]
    },
    resolve: {
        alias: { 
            vue: path.resolve(__dirname, './node_modules/vue/dist/vue.esm')
        },
        extensions: ['.ts', '.js'],
        fallback: {
            path: require.resolve("path-browserify")
        }
    },
    plugins: plugins
}


module.exports = config;