import { Selector, t } from 'testcafe';
import ProductUtils from '../../utils/productUtils';

const dataUtils = new ProductUtils();
const physicalProdID = dataUtils.getTestingPhysicalProduct().productID;
const digitalProdName = dataUtils.getTestingDigitalProduct().productName;
const onSaleProdID = dataUtils.getOnSaleProduct().productID;

export default class HomePage {
  constructor() {
    this.productsMenu = Selector('a[title="Products"]');
    this.addPhyProduct = Selector('.dr-buy-btn[data-product-id="' + physicalProdID + '"]');
    this.addDigiProduct = Selector('.c-product-card-content__text').withText(digitalProdName.toUpperCase()).parent(2).find('button');
    this.addOnSaleProduct = Selector('button[data-product-id="' + onSaleProdID + '"]');
    this.categoryRegularPrice = this.addOnSaleProduct.parent('div').find('.new-price');
    this.categorySalePrice = this.addOnSaleProduct.parent('div').find('.new-price');
    this.minicartItem = Selector('li.dr-minicart-item > div[data-product-id="' + onSaleProdID + '"]');
    this.minicartRegularPrice = this.minicartItem.find('p.dr-minicart-item-price > .dr-strike-price');
    this.minicartSalePrice = this.minicartItem.find('p.dr-minicart-item-price > .dr-sale-price');

    this.paginationPrevBtn = Selector('.prev.page-link');
    this.paginationNextBtn = Selector('.next.page-link');
    this.cartBtn = Selector('.dr-btn').withText('CART');
    this.checkoutBtn = Selector('.dr-btn').withText('CHECKOUT');
  }
}
