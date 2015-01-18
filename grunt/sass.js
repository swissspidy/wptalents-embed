module.exports = {
  dist: {
    files: [{
      expand: true,
      cwd   : 'assets/css/src',
      src   : ['*.scss'],
      dest  : 'assets/css/build',
      ext   : '.css'
    }]
  }
};