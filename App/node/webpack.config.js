const path = require('path');
const ROOT = path.dirname(path.dirname(__dirname));
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const WebpackBeforeBuildPlugin = require('before-build-webpack');
const exec = require('child_process').exec;
const appConfig = require('./src/app.config.json');

var today = new Date();
var insec = parseInt(today.valueOf() / 1000); 

var plugins = [];
plugins.push(new webpack.ProvidePlugin({
    $: 'jquery',
    jQuery: 'jquery'
}));
plugins.push(new HtmlWebpackPlugin({
    inject: 'body',
    template: './src/index.html'
}));
plugins.push(new MiniCssExtractPlugin({
    filename: appConfig.prod ? insec + 'style.css' : 'style.css' 
}));
plugins.push(new CleanWebpackPlugin({
    cleanAfterEveryBuildPatterns: [appConfig.ouputPath]
}));
plugins.push(new WebpackBeforeBuildPlugin(function(stats, callback) {
    exec('php -v', (error, stdOut, stdErr) => {
        callback();
    }); 
}));


var config = {
    mode: appConfig.prod ? 'production' : 'development',
    entry: './src/app.ts',
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
    plugins: plugins
}


module.exports = config;