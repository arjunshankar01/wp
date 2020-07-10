module.exports = {
  env: {
    browser: true,
    es6: true,
    jquery: true
  },
  extends: "airbnb-base",
  rules: {
    "linebreak-style": "off",
    "padded-blocks": "off",
    "func-names": "off",
    "comma-dangle": "off",
    "no-param-reassign": [2, { props: false }]
  }
};
