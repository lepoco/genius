const webpack = require("webpack");
const path = require("path");

const CopyPlugin = require("copy-webpack-plugin");
const Workbox = require("workbox-webpack-plugin");

process.env.APP_NAME = "Genius";
process.env.APP_VERSION = "2.0.0";
process.env.APP_ENV = "production"; // production development

module.exports = {
  mode: process.env.APP_ENV,
  watch: false,
  entry: "./src/scripts/index.ts",
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: "ts-loader",
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
    extensions: [".tsx", ".ts", ".js"],
  },
  plugins: [
    new webpack.DefinePlugin({
      "process.env.ASSET_PATH": "public/",
    }),
    new CopyPlugin({
      patterns: [
        { from: "./src/index.php" },
        { from: "./src/robots.txt" },
        { from: "./src/.htaccess" },
        { from: "./src/m.webmanifest" },
        { from: "./src/favicon.ico" },
        { from: "./src/img", to: "img" },
        { from: "./src/fonts", to: "fonts" },
      ],
    }),
    new Workbox.GenerateSW({
      mode: process.env.APP_ENV,
      cacheId: "pwa",
      swDest: "service-worker.js",
      navigateFallback: "/",
      clientsClaim: true,
      skipWaiting: true,
      cleanupOutdatedCaches: true,
      offlineGoogleAnalytics: true,
      sourcemap: "development" === process.env.APP_ENV,
      include: [],
      runtimeCaching: [
        {
          urlPattern:
            "development" === process.env.APP_ENV
              ? "/^(?![sS])/"
              : /^http(s?)([:\/\\]+)(\w+).(\w+)([\/\w-]+)$/,
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
      additionalManifestEntries: [],
    }),
  ],
  output: {
    filename: "bundle.min.js",
    path: path.resolve(__dirname, "public"),
    clean: true,
  },
};
