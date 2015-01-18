module.exports = {
  options: {
    livereload: true
  },

  config: {
    files: 'grunt/watch.js'
  },

  css: {
    files: 'assets/css/src/**/*.scss',
    tasks: ['sass', 'cssmin', 'autoprefixer']
  },

  jsminify: {
    files: 'assets/js/src/**/*.js',
    tasks: ['concat', 'uglify']
  }
};