module.exports =  {
  options: {
    banner: '/*! <%= package.version %> */\n',
    separator: ';'
  },
  maps: {
    src: ['assets/js/src/meta-box.js'],
    dest: 'assets/js/build/meta-box.js'
  }
};