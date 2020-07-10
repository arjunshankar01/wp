import { Selector } from 'testcafe';

export default class CartPage {
  constructor() {
    this.proceedToCheckoutBtn = Selector('a').withText('PROCEED TO CHECKOUT');

    // Product editor
    this.increaseQuantity = Selector('.dr-pd-cart-qty-plus.value-button-increase');
    this.estimatedShippingTitle = Selector('.shipping-label');
    this.estimatedShippingValue = Selector('.shipping-value');
  }
}
