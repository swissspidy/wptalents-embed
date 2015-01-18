module.exports = {
  minify: {
    options: {
      keepSpecialComments: 0,
      report             : 'gzip'
    },
    expand : true,
    cwd    : 'assets/css/build',
    src    : ['*.css', '!*.min.css'],
    dest   : 'assets/css/build',
    ext    : '.min.css'
  }
};