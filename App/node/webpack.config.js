const path = require('path');

// const DS = path.sep;
// const ROOT = path.dirname(path.dirname(__dirname));

const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const WatchOpoinkThemeFiles = require('./src/core/plugins/watch.opoink.theme.files');
const ComponentAttrId = require('./src/core/lib/component.attr.id');
var componentAttrId = new ComponentAttrId();

var config = (env) => {

    let isProd = env.prod ? true : false;
    let mode = env.prod ? 'production' : 'development';
    var plugins = [];

    let opoinkWatcher = new WatchOpoinkThemeFiles({
        files: [],
        dirs: [],
        publicPath: path.resolve(__dirname, "./../../public/vuedist")
    });

    plugins.push(new webpack.ProvidePlugin({
        $: path.resolve(__dirname, "./node_modules/jquery"),
        jQuery: path.resolve(__dirname, "./node_modules/jquery")
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
        cleanBeforeEveryBuildPatterns: [path.resolve(__dirname, "./../../public/vuedist")],
        cleanStaleWebpackAssets: false,
        protectWebpackAssets: true
    }));
    plugins.push(opoinkWatcher);

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
                { 
                    test: /\.ts$/, 
                    use: ['ts-loader']
                },
                { test: /\.js$/, use: 'babel-loader' },
                {
                    test: /\.s(a|c)ss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
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
                            loader: 'opoink-css-modules-loader',
                            options: {
                                watcher: opoinkWatcher,
                                componentAttrId: componentAttrId
                            }
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                sourceMap: isProd
                            }
                        },
                        {
                            loader: 'opoink-css-theme-loader',
                            options: {
                                watcher: opoinkWatcher
                            }
                        }
                    ]
                },
                {
                    test: /bootstrap\.min\.css$/,
                    use: [
                        MiniCssExtractPlugin.loader,
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
                                componentAttrId: componentAttrId
                            }
                        },
                        {
                            loader: 'opoink-html-theme-loader',
                            options: {
                                watcher: opoinkWatcher,
                                outputPath: 'assets/images/',
                                publicPath: '/public/vuedist/assets/images'
                            }
                        }
                    ] 
                },
                {
                    test: /\.(gif|png|jpe?g|svg|cur)$/i,
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
                    test: /\.(eot|woff|woff2|ttf)$/i,
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
                },
                {
                    test: /environment\.ts$/,
                    use: [
                        {
                            loader: 'opoink-file-replace-loader',
                            options: {
                                isProd: isProd,
                                replacement_name: 'environment.prod.ts'
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
