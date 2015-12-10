({
    mainConfigFile: 'public/assets/js/require-config.js',
    baseUrl: '.',
    appDir: 'public',
    dir: 'public/build',
    buildCSS: false,
    modules: [
        {
            name: "assets/js/dashboardLoader"
        }
    ],
    paths: {
        'require-css': 'vendor/require-css/css',
        'css-builder': 'vendor/require-css/css-builder',
        'normalize': 'vendor/require-css/normalize'
    }
})
