let config = {
  env: 'dev', // local: dev; staging/systest: sys; production/demo: prod
  apiEnv: 'PRD',
  testEmail: 'qa@dr.com',
  adminUserName: {
    dev: 'username', //Replace this to your localhost admin username
    sys: 'username',
    prod: 'username',
    demo: 'username',
  },
  adminPassword: {
    dev: 'password', //Replace this to your localhost admin password
    sys: 'password',
    prod: 'password',
    demo: 'password',
  },
  baseUrl: {
    dev: 'Dev Site Web Address',  //Replace this to your localhost test site address
    sys: 'Systest Site Web Address',
    prod: 'Prod Site Web Address',
    demo: 'Demo Site Web Address',
  },
  apiUrl: {
    PRD: 'Prod API URL',
    NONPRD: 'NonProd API URL',
  },
  websiteAuth: {
	username: 'username',
	password: 'password'
  }
};

module.exports = config;
