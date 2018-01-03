module.exports = {
  plugins: function () {
    return [
      require('postcss-cssnext')({
        features: {
          customProperties: {
            // Bulma is-variable column padding uses css vars which cannot be converted for older browsers
            warnings: false
          }
        }
      })
    ]
  }
};
