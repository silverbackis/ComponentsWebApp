/**
 * THIS FILE IS PURELY FOR PHPSTORM SO THAT IT MAPS THE TILDE (~) PREFIX CORRECTLY
 */
const path = require('path')

module.exports = {
  resolve: {
    extensions: ['.js', '.json', '.vue'],
    alias: {
      '~': path.resolve(__dirname, './')
    }
  }
}
