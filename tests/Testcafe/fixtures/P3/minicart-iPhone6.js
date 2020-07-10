/* minicart-iPhone6.js */
import { Selector, t } from 'testcafe';
import Config from '../../config';
import GeneralUtils from '../../utils/genericUtils';
import HomePage from '../../page-models/public/home-page-model';

fixture `===== DRGC P3 Automation Test - Mini Cart for iPhone6 =====`
  .httpAuth({
    username: Config.websiteAuth['username'],
    password: Config.websiteAuth['password'],
  });

const env = Config.env;
const baseURL = Config.baseUrl[env];
const mobileViewHamburgList = Selector('.navbar.navbar-expand-xl.fixed-top').find('[data-target="#navbarNavDropdown"]').find('span');
const miniCartToggle = Selector('.dr-minicart-toggle');
const utils = new GeneralUtils();
const homePage = new HomePage();

test('Verify minicart lineItem quanaity exist in iPhone6 portrait', async t => {
  console.log('Test Case: Portrait Mode - Verify Minicart Line Item Quanaity Exist in iPhone6 ');
  console.log('>> Navigate to target testing website with Mobile mode');
  await t
    .navigateTo(baseURL)
    .resizeWindowToFitDevice('iPhone 6', {
      portraitOrientation: true
    });

  await utils.clickItem(mobileViewHamburgList);
  await utils.addProductsIntoCart(homePage.addPhyProduct);

  await checkMiniCartInfo('BWC/minicart-iPhone6-portrait.png');
});

/*
Currently, testcafe doesn't have entire page screenshot feature available. A short-term solution is to use
resizeWindow and run test script in headless mode.

More details:
https://github.com/DevExpress/testcafe/issues/1520
*/

test('Verify minicart lineItem quanaity exist in iPhone6 landscape', async t => {
  console.log('Test Case: Landscape Mode - Verify Minicart Line Item Quanaity Exist in iPhone6 ');
  console.log('>> Navigate to target testing website with Mobile mode');
  await t
    .navigateTo(baseURL)
    .resizeWindowToFitDevice('iPhone 6', {
      portraitOrientation: false
    });

  await utils.clickItem(mobileViewHamburgList);
  await utils.addProductsIntoCart(homePage.addPhyProduct);

  await checkMiniCartInfo('BWC/minicart-iPhone6-landscape.png');
});

async function checkMiniCartInfo(screenShotName) {
  console.log('>> Check minicart info display correctly');
  await utils.clickItem(miniCartToggle);
  await t.expect(Selector('.dr-minicart-display').exists).ok();
  await utils.clickItem(miniCartToggle);
  await t.expect(Selector('.dr-minicart-display').exists).ok();
  await t.expect(Selector('.dr-minicart-item-qty').innerText).eql('Qty.1');
  await t.resizeWindow(667, 375)
  await t.takeScreenshot(screenShotName);
}
