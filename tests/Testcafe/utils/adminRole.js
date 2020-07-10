import { Role } from 'testcafe';
import Config from '../config';
const env = Config.env;
const url = Config.baseUrl[env] + '/wp-login.php';
const username = Config.adminUserName[env];
const password = Config.adminPassword[env];
export default Role(url, async t => {
  await t
    .setTestSpeed(0.7)
    .setNativeDialogHandler(() => true)
    .typeText('#user_login', username)
    .typeText('#user_pass', password)
    .click('#wp-submit');
}, { preserveUrl: true });
