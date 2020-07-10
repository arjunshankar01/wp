import { Selector } from 'testcafe';

export default class TYPage {
  constructor() {
    this.orderNumber = Selector('.order-number');
    this.tyMsg = Selector('.subheading');
  }
}
