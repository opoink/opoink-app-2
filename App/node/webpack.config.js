const path = require('path');

// const DS = path.sep;
// const ROOT = path.dirname(path.dirname(__dirname));

const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

var config = (env) => {

    let isProd = env.prod ? true : false;
    let mode = env.prod ? 'production' : 'development';
    var plugins = [];

    plugins.push(new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery'
    }));
    
    plugins.push(new HtmlWebpackPlugin({
        inject: 'body',
        template: './src/index.html',
        publicPath: '/public/vuedist'
    }));
    plugins.push(new MiniCssExtractPlugin({
        filename: isProd ? 'css/[chunkhash].[name].css' : 'css/[name].css'
    }));
    plugins.push(new CleanWebpackPlugin({
        dry: false,
        dangerouslyAllowCleanPatternsOutsideProject: true,
        cleanBeforeEveryBuildPatterns: [path.resolve(__dirname, "./../../public/vuedist")]
    }));

    return {
        mode: mode,
        entry: './src/app.ts',
        // target: 'node',
        output: {
            path: path.resolve(__dirname, "./../../public/vuedist"),
            filename: isProd ? 'js/[chunkhash].[name].bundle.js' : 'js/[name].bundle.js',
            chunkFilename: isProd ? 'js/chunks/[chunkhash].[name].js' : 'js/chunks/[name].js'
        },
        resolveLoader: {
            modules: [
                'node_modules',
                path.resolve(__dirname, './src/core/loaders')
            ]
        },
        module: {
            rules: [
                { test: /\.ts$/, use: 'ts-loader' },
                { test: /\.js$/, use: 'babel-loader' },
                {
                    test: /\.s(a|c)ss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: {
                                modules: false,
                                sourceMap: isProd
                            }
                        },
                        {
                            loader: 'opoink-url-loader'
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                sourceMap: isProd
                            }
                        }
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
                                publicPath: '/public/vuedist/assets/images'
                            }
                        }
                    ]
                }
            ]
        },
        resolve: {
            alias: { 
                vue: path.resolve(__dirname, './node_modules/vue/dist/vue.esm')
            },
            extensions: ['.ts', '.js', '.scss'],
            fallback: {
                path: require.resolve("path-browserify")
            }
        },
        plugins: plugins
    }
}

module.exports = config;