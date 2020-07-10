import DRApplePay from '../../../assets/js/public/payment-applepay';
import DRCommerceApi from '../../../assets/js/public/commerce-api';

const setupDocumentBody = () => {
  document.body.innerHTML =
    '<div id="dr-applepay-button"></div>';
};

const cartData = {
  pricing: {
    orderTotal: {
      currency: 'USD',
      value: 21.98
    },
    subtotal: {
      currency: 'USD',
      value: 19.99
    },
    tax: {
      currency: 'USD',
      value: 1.99
    },
    shippingAndHandling: {
      currency: 'USD',
      value: 3.99
    },
    formattedTax: '$1.99',
    formattedOrderTotal: '$21.98'
  },
  shippingOptions: {
    shippingOption: [{
      id: 12345,
      description: 'shipping option #1',
      cost: {
        value: 999
      }
    }]
  },
  shippingAddress: {
    firstName: 'abc',
    lastName: 'xyz',
    line1: '10380 Bren Rd W',
    city: 'Minnetonka',
    country: 'US'
  },
  shippingMethod: {
    code: 'SHIPPING_CODE'
  }
};

const DigitalRiver = {};

Object.defineProperty(DigitalRiver, 'paymentRequest', {
  value: jest.fn(() => {
    return {};
  })
});

Object.defineProperty(DigitalRiver, 'createElement', {
  value: jest.fn((type, object) => {
    return {
      id: `${type}-12345`,
      type: type,
      mount: mount,
      canMakePayment: canMakePayment,
      on: on,
      options: object
    };
  })
});

const mount = jest.fn();
const canMakePayment = jest.fn();
const on = jest.fn();
const updateWith = jest.fn();
const complete = jest.fn();

describe('Apple Pay tests', () => {
  test('init error', () => {
    // Missing the params
    expect(() => {
      DRApplePay.init();
    }).toThrow();

    // Missing the DigitalRiver JS object
    expect(() => {
      DRApplePay.init({});
    }).toThrow();

    // Missing the cart data object
    expect(() => {
      DRApplePay.init({digitalriverJs: DigitalRiver});
    }).toThrow();
  });

  test('canMakePayment returns false', () => {
    canMakePayment.mockReturnValueOnce(false);

    const paymentDataRequest = DigitalRiver.paymentRequest();
    const applepay = DRApplePay.init({
      digitalriverJs: DigitalRiver,
      paymentDataRequest: paymentDataRequest
    });

    expect(applepay).toBe(false);
  });

  test('init successfully', () => {
    const event = {
      billingAddress: {
        firstName: 'xyz',
        address: {
          country: 'US'
        }
      },
      shippingAddress: {
        firstName: 'abc',
        address: {
          country: 'US'
        }
      },
      shippingOption: {
        id: 'SHIPPING_OPTION_ID'
      },
      source: {
        id: 'SOURCE_ID'
      },
      updateWith: updateWith,
      complete: complete
    };

    setupDocumentBody();
    canMakePayment.mockReturnValueOnce(true);
    on.mockImplementation((action, cb) => cb(event));
    
    DRCommerceApi.updateCart = jest.fn(() =>
      Promise.resolve({
        cart: cartData
    }));

    DRCommerceApi.applyShippingOption = jest.fn(() =>
      Promise.resolve({
        cart: cartData
    }));

    const paymentDataRequest = DigitalRiver.paymentRequest();
    const applepay = DRApplePay.init({
      digitalriverJs: DigitalRiver,
      paymentDataRequest: paymentDataRequest,
      requestShipping: false
    });

    expect(typeof applepay).toBe('object');
    expect(applepay.type).toBe('applepay');
    expect(typeof applepay.canMakePayment).toBe('function');
    expect(typeof applepay.mount).toBe('function');
    expect(typeof applepay.on).toBe('function');
  });
});