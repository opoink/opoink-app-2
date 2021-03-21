const path = require('path');

// const DS = path.sep;
// const ROOT = path.dirname(path.dirname(__dirname));

const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const WatchOpoinkThemeFiles = require('./src/core/plugins/watch.opoink.theme.files')

var config = (env) => {

    let isProd = env.prod ? true : false;
    let mode = env.prod ? 'production' : 'development';
    var plugins = [];

    let opoinkWatcher = new WatchOpoinkThemeFiles({
        files: [],
        dirs: []
    })

    plugins.push(opoinkWatcher);

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

    let _outPut = {
        path: path.resolve(__dirname, "./../../public/vuedist"),
        filename: isProd ? 'js/[chunkhash].[name].bundle.js' : 'js/[name].bundle.js',
        chunkFilename: isProd ? 'js/chunks/[chunkhash].[name].js' : 'js/chunks/[name].js'
    }

    return {
        mode: mode,
        entry: './src/app.ts',
        devtool: isProd ? false : 'inline-source-map',
        performance: {
            maxEntrypointSize: 4096000,
            maxAssetSize: 4096000
        },
        output: _outPut,
        optimization: {
            splitChunks: {
                cacheGroups: {
                    vendor: {
                        chunks: 'initial',
                        name: 'vendor',
                        test: 'vendor',
                        enforce: true
                    },
                }
            },
            runtimeChunk: true,
            minimizer: [
                new CssMinimizerPlugin(),
            ]
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
                        isProd ? MiniCssExtractPlugin.loader : 'style-loader',
                        {
                            loader: 'css-loader',
                            options: {
                                modules: false,
                                sourceMap: isProd,
                                importLoaders: 1
                            }
                        },
                        {
                            loader: 'postcss-loader'
                        },
                        {
                            loader: 'opoink-css-loader'
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                sourceMap: isProd
                            }
                        }
                    ]
                },
                
                {
                    test: /bootstrap\.min\.css$/,
                    use: [
                        isProd ? MiniCssExtractPlugin.loader : 'style-loader',
                        {
                            loader: 'css-loader',
                            options: {
                                modules: false,
                                sourceMap: isProd,
                                importLoaders: 1
                            }
                        }
                    ]
                },
                { 
                    test: /\.html$/, 
                    use: [
                        'html-loader',
                        {
                            loader: 'opoink-html',
                            options: {
                                addFileLocation: isProd,
                            }
                        },
                        {
                            loader: 'opoink-theme-loader',
                            options: {
                                watcher: opoinkWatcher,
                                outputPath: 'assets/images/',
                                publicPath: '/public/vuedist/assets/images'
                            }
                        }
                    ] 
                },
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
                },
                {
                    test: /\.(eot|woff|ttf)$/i,
                    use: [
                        {
                            loader: 'file-loader',
                            options: {
                                name: '[name].[ext]',
                                outputPath: 'assets/fonts/icon',
                                publicPath: '/public/vuedist/assets/fonts/icon'
                            }
                        }
                    ]
                }
            ]
        },
        resolve: {
            alias: { 
                vue: path.resolve(__dirname, './node_modules/vue/dist/vue.min')
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
