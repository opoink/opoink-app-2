var path = require('path');
var webpack = require('webpack');
var MiniCssExtractPlugin = require('mini-css-extract-plugin');
var HtmlWebpackPlugin = require('html-webpack-plugin');
var { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = {
    mode: 'development',
    entry: './src/app.ts',
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'bundle.js',
        // publicPath: '/dist'
    },
    module: {
        rules: [
            { 
                test: /\.ts$/, 
                use: 'awesome-typescript-loader' 
            },
            { 
                test: /\.js$/, 
                use: 'babel-loader' 
            },
            { 
                test: /\.scss$/, 
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: '/dist',
                        }
                    },
                    'css-loader',
                ] 
            },
            { 
                test: /\.html$/, 
                use: 'html-loader' 
            },
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
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        }),
        new HtmlWebpackPlugin({
            inject: 'body',
            template: './src/index.html'
        }),
        new MiniCssExtractPlugin({
            filename: 'style.css'
        }),
        new CleanWebpackPlugin({
            cleanAfterEveryBuildPatterns: ['dist']
        })
    ]
}