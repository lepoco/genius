const webpack = require("webpack");
const path = require("path");

const CopyPlugin = require("copy-webpack-plugin");
const Workbox = require("workbox-webpack-plugin");

process.env.APP_NAME = "Genius";
process.env.APP_VERSION = "1.1.0";
process.env.APP_URL = "https://genius.lan/";
process.env.APP_ENV = "production"; // production development

// Public/webroot path
const publicPath = path.resolve(__dirname, "public");

console.log(publicPath);

module.exports = {
  mode: process.env.APP_ENV,
  watch: false,
  // entry: {
  //   index: "./src/scripts/index.js",
  // },
  entry: './src/scripts/index.ts',
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: 'ts-loader',
        exclude: /node_modules/,
      },
      {
        test: /\.scss$/,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "bundle.min.css",
            },
          },
          {
            loader: "sass-loader",
            options: {
              implementation: require("sass"),
              sourceMap: true,
              sassOptions: {
                outputStyle: "compressed",
              },
            },
          },
        ],
      },
    ],
  },
  resolve: {
    extensions: ['.tsx', '.ts', '.js'],
  },
  plugins: [
    new webpack.DefinePlugin({
      "process.env.ASSET_PATH": "public/",
    }),
    new CopyPlugin({
      patterns: [
        { from: "./src/index.php" },
        { from: "./src/no-connection.html" },
        { from: "./src/robots.txt" },
        { from: "./src/.htaccess" },
        { from: "./src/m.webmanifest" },
        { from: "./src/favicon.ico" },
        { from: "./src/img", to: "img" },
      ],
    }),
    // https://developers.google.com/web/tools/workbox/reference-docs/latest/module-workbox-webpack-plugin.GenerateSW
    // https://stackoverflow.com/questions/58103531/how-to-you-modify-the-precache-manifest-file-generated-by-workbox-need-urls-to
    new Workbox.GenerateSW({
      mode: process.env.APP_ENV,
      cacheId: "pwa",
      swDest: "service-worker.js",
      navigateFallback: "/",
      clientsClaim: true,
      skipWaiting: true,
      cleanupOutdatedCaches: true,
      offlineGoogleAnalytics: true,
      directoryIndex: process.env.APP_URL,
      navigateFallback: process.env.APP_URL + "no-connection.html",
      sourcemap: "development" === process.env.APP_ENV,
      include: [],
      runtimeCaching: [
        {
          urlPattern: "development" === process.env.APP_ENV ? '/^(?![\s\S])/' : /^http(s?)([:\/\\]+)(\w+).(\w+)([\/\w-]+)$/,
          handler: "NetworkFirst",
          options: {
            cacheName: process.env.APP_NAME.toLowerCase() + "-pages",
            expiration: {
              maxEntries: 10,
            },
          },
        },
        {
          urlPattern: /([\/:.\w]+)([.])(?:js|css|webmanifest|html|txt)(.)?/,
          handler: "CacheFirst",
          options: {
            cacheName: (
              process.env.APP_NAME +
              "-" +
              process.env.APP_ENV
            ).toLowerCase(),
          },
        },
        {
          urlPattern: /([\/:.\w]+)([.])(?:txt|png|jpg|jpeg|svg)(.)?/,
          handler: "CacheFirst",
          options: {
            cacheName: process.env.APP_NAME.toLowerCase() + "-images",
          },
        },
        {
          urlPattern: new RegExp("https://fonts.(googleapis|gstatic).com"),
          handler: "CacheFirst",
          options: {
            cacheName: process.env.APP_NAME.toLowerCase() + "-google-fonts",
          },
        },
      ],
      //chunks: ["no-connection.html"],
      additionalManifestEntries: [],
      //dontCacheBustUrlsMatching: /\.\w{8}\./,
      //navigateFallback: 'no-connection.html',
      //importScripts: ["./src/sw.js"],
    }),
  ],
  output: {
    filename: "bundle.min.js",
    path: publicPath,
    clean: true,
  },
};
