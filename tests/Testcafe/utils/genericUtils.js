import { Selector, t } from 'testcafe';
import HomePage from '../page-models/public/home-page-model';
import CartPage from '../page-models/public/cart-page-model';
import CheckoutPage from '../page-models/public/checkout-page-model';
import LoginPage from '../page-models/public/login-page-model';
import MiniCartPage from '../page-models/public/minicart-page-model';
import TYPage from '../page-models/public/ty-page-model';

export default class GenericUtils {
  constructor() {
  }

  async checkEstShippingInfo(title, value) {
    const cartPage = new CartPage();
    await t
      .expect(cartPage.estimatedShippingTitle.innerText).eql(title)
      .expect(cartPage.estimatedShippingValue.innerText).eql(value);
  }

  async checkShippingSummaryInfo(title, value) {
    await t
      .expect(new CheckoutPage().shippingSummaryTitle.innerText).eql(title)
      .expect(new CheckoutPage().shippingSummaryValue.innerText).eql(value);
  }

  async clickItem(target) {
    await t
      .expect(target.exists).ok()
      .wait(500)
      .hover(target)
      .click(target);
  }

  async checkCheckBox(checkbox, checked) {
    let ischecked = await checkbox.checked;
    while(ischecked != checked) {
      await t
        .hover(checkbox)
        .click(checkbox);
      ischecked = await checkbox.checked;
    }
  }

  async addProductsIntoCart(product, isVariation = false){
    const homePage = new HomePage();
    const minicartPage = new MiniCartPage();
    await this.clickItem(homePage.productsMenu);
    await this.clickItem(product);

    // Add to cart btn changed to buy now button of variaction products, need to click add to cart
    // when entered product's detail page after clicking buy now btn in products page.
    if (isVariation) {
      const addToCartBtn = Selector('.btn.btn-green.w-50.dr-buy-btn');
      await t.click(addToCartBtn);
    }

    await t.expect(minicartPage.viewCartBtn.exists).ok();
  }

  async testShippingFee(estShippingFee, shippingMethod, finalShippingFee) {
    const checkoutPage = new CheckoutPage();
    const cartPage = new CartPage();
    const estimatedShipping = 'Estimated Shipping';
    const testEmail = "qa@test.com";
    const fixedShipping = 'Shipping';
    // Click Proceed to Checkout in View Cart page to proceed checkout
    console.log('>> Direct to checkout page, still show Estimated Shipping');
    await this.clickItem(cartPage.proceedToCheckoutBtn);
    await this.clickItem(new LoginPage().continueAsGuestBtn);
    await this.checkShippingSummaryInfo(estimatedShipping, estShippingFee);

    // Enter Email and continue
    console.log('>> Checkout page - Entering email, still show Estimated Shipping');
    await checkoutPage.completeFormEmail(testEmail);
    await this.checkShippingSummaryInfo(estimatedShipping, estShippingFee);

    // Enter shipping info
    console.log('>> Checkout page - Entering shipping info, still show Estimated Shipping');
    await t.expect(checkoutPage.shippingBtn.exists).ok();
    await checkoutPage.completeFormShippingInfo();
    await this.checkShippingSummaryInfo(estimatedShipping, estShippingFee);

    // Skip Billing info
    console.log('>> Checkout page - Skip Billing info and continue, still show Estimated Shipping');
    await this.clickItem(checkoutPage.billingInfoSubmitBtn);
    await this.checkShippingSummaryInfo(estimatedShipping, estShippingFee);

    // Set delivery option
    console.log('>> Checkout page - Set delivery, Estimated Shipping label changes to Shipping');
    await t.expect(checkoutPage.deliveryOptionSubmitBtn.exists).ok();
    await checkoutPage.setDeliveryOption(shippingMethod);
    await this.checkShippingSummaryInfo(fixedShipping, finalShippingFee);
  }

  async fillOrderInfoAndSubmitOrder(isPhysical) {
    const tyPage = new TYPage();
    const checkoutPage = new CheckoutPage();
    if (isPhysical) {
      // Enter shipping info
      console.log('>> Checkout page - Entering Shipping Info.');
      await checkoutPage.completeFormShippingInfo();
      await t.expect(checkoutPage.useSameAddrCheckbox.exists).ok();

      // Set billing info as diff from shipping info
      // If checkbox is checked, the billing info will be set to same as shipping info
      await this.checkCheckBox(checkoutPage.useSameAddrCheckbox, false);
    }

    // Enter Billing Info
    console.log('>> Checkout page - Entering Billing Info.');
    await checkoutPage.completeFormBillingInfo();

    if (isPhysical) {
      await t.expect(checkoutPage.deliveryOptionSubmitBtn.exists).ok();
      // Set delivery option
      console.log('>> Checkout page - Set Delivery Options as Standard');
      await checkoutPage.setDeliveryOption('standard');
      await t.expect(checkoutPage.submitPaymentBtn.exists).ok();
    }

    // Enter Payment Info
    console.log('>> Checkout page - Entering Payment Info.');
    await checkoutPage.completeFormCreditCardInfo();

    // Agree to Terms of Sales and Privacy Policy then submit order
    console.log('>> Checkout page - agree to Terms of Sale');
    await this.checkCheckBox(checkoutPage.checkboxTermsofSaleAndPolicy, true);

    // Submit Order
    console.log('>> Checkout page - Place order');
    await t
      .takeScreenshot('BWC/payment_s.jpg')
      .click(checkoutPage.submitOrderBtn)
      .expect(tyPage.tyMsg.innerText).eql('Your order was completed successfully.')
      .takeScreenshot('BWC/TY_s.jpg');

    console.log('>> Directs to the TY page');
    const orderNum = await tyPage.orderNumber.textContent;
    console.log(orderNum.trim());
  }


  async addProductAndProceedToCheckout(product, isVariation = false) {
    const minicartPage = new MiniCartPage();
    const cartPage = new CartPage();
    const checkoutPage = new CheckoutPage();
    // Add a physical product into cart
    console.log('>> Add Product into Cart');
    await this.addProductsIntoCart(product, isVariation);

    // Click View Cart btn in miniCart to go to Cart page
    console.log('>> Direct to cart page');
    await minicartPage.clickViewCartBtn();

    // Click Proceed to Checkout in View Cart page to proceed checkout
    console.log('>> Direct to checkout page');
    await t
      .click(cartPage.proceedToCheckoutBtn)
      .expect(checkoutPage.primary.exists).ok();
  }

  getNewUser() {
    const timestamp = Date.now();
    const newEmail = 'qa' + timestamp + '@dr.com';
    const firstName = 'JOHN';
    const lastName = 'DON';
    const password = 'DigitalRiverTest_2019!';
    const email = newEmail;

    const user = {
      firstName: firstName,
      lastName: lastName,
      password: password,
      confirmPassword: password,
      email: email
    };

    return user;
  }

  getShippingUserData() {
    const shipInfo = {
      firstName: 'Helen',
      lastName: 'Mcclinton',
      addrLine1: '10451 Gunpowder Falls St',
      city: 'Las Vegas',
      country: 'USA',
      countryValue: 'US',
      state: 'Nevada',
      stateValue: 'NV',
      postCode: '89123',
      phoneNo: '7028962624'
    };

    return shipInfo;
  }

  getBillingUserData() {
    const shipInfo = {
      firstName: 'John',
      lastName: 'Doe',
      addrLine1: '10380 Bren Rd W',
      city: 'Minnetonka',
      country: 'USA',
      countryValue: 'US',
      state: 'Minnesota',
      stateValue: 'MN',
      postCode: '55343',
      phoneNo: '9522531234'
    };

    return shipInfo;
  }

  getCreditCardInfo() {
    const currentTime = new Date();
    const year = (currentTime.getFullYear() + 3).toString();
    const expiryData = '01'+ year.slice(-2);
    const cardInfo = {
      cardNo: '4444222233331111',
      expiry: expiryData,
      cvv: '123'
    };

    return cardInfo;
  }
}
