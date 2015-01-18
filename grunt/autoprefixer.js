module.exports = {
    dist: {
        options: {
            browsers: [
                'last 2 versions',
                '> 5%',
                'ie 9'
            ],
            map: true
        },
        expand: true,
        flatten: true,
        src: 'assets/css/build/**/*.css',
        dest: 'assets/css/build/'
    }
};