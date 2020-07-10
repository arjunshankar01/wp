const CheckoutUtils = (($, params) => {
  const localizedText = drgc_params.translations;
  const createDisplayItems = (cartData) => {
    const displayItems = [{
      label: params.translations.subtotal_label,
      amount: cartData.pricing.subtotal.value
    }, {
      label: params.translations.tax_label,
      amount: cartData.pricing.tax.value
    }];

    if (cartData.shippingOptions.shippingOption) {
      displayItems.push({
        label: params.translations.shipping_and_handling_label,
        amount: cartData.pricing.shippingAndHandling.value
      });
    }

    if (cartData.pricing.discount) {
      if (parseFloat(cartData.pricing.discount.value) > 0) {
        displayItems.push({
          label: params.translations.discount_label,
          amount: cartData.pricing.discount.value
        });
      }
    }

    return displayItems;
  };

  const createShippingOptions = (cartData) => {
    const isFreeShipping = (cartData.pricing.shippingAndHandling.value === 0);
    let shippingOptions = [];

    cartData.shippingOptions.shippingOption.forEach((option) => {
      let shippingOption = {
        id: option.id.toString(),
        label: option.description,
        amount: isFreeShipping ? 0 : option.cost.value,
        detail: ''
      };

      shippingOptions.push(shippingOption);
    });

    return shippingOptions;
  };

  const updateShippingOptions = (shippingOptions, selectedId) => {
    shippingOptions.forEach((option, index) => {
      if (option.id === selectedId.toString()) {
        shippingOptions[index].selected = true;
      } else {
        if (shippingOptions[index].selected) {
          delete shippingOptions[index].selected;
        }
      }
    });
  };

  const getBaseRequestData = (cartData, requestShipping, buttonStyle) => {
    const displayItems = createDisplayItems(cartData);
    let shippingOptions = [];

    if (requestShipping) {
      shippingOptions = createShippingOptions(cartData);
      updateShippingOptions(shippingOptions, cartData.shippingMethod.code);
    }

    const requestData = {
      country: params.drLocale.split('_')[1],
      currency: cartData.pricing.orderTotal.currency,
      total: {
        label: params.translations.order_total_label,
        amount: cartData.pricing.orderTotal.value
      },
      displayItems: displayItems,
      shippingOptions: shippingOptions,
      requestShipping: requestShipping,
      style: buttonStyle,
      waitOnClick: false
    };

    return requestData;
  };

  const updateDeliverySection = (shippingOption) => {
    const $selectedOption = $('form#checkout-delivery-form').children().find('input:radio[data-id="' + shippingOption.id + '"]');
    const resultText = `${shippingOption.label} ${shippingOption.amount === 0 ? params.translations.free_label : $selectedOption.attr('data-cost')}`;

    $selectedOption.prop('checked', true);
    $('.dr-checkout__delivery').find('.dr-panel-result__text').text(resultText);
  };

  const updateAddressSection = (addressObj, $target) => {
    const addressArr = [
      `${addressObj.firstName} ${addressObj.lastName}`,
      addressObj.line1,
      addressObj.city,
      addressObj.country
    ];

    $target.text(addressArr.join(', '));
  };

  const updateSummaryPricing = (cart) => {
    const {formattedOrderTotal, formattedTax} = cart.pricing;

    if (Object.keys(cart.shippingMethod).length) {
      const formattedShippingAndHandling = (cart.pricing.shippingAndHandling.value === 0) ? params.translations.free_label : cart.pricing.formattedShippingAndHandling;

      $('div.dr-summary__shipping > .item-value').text(formattedShippingAndHandling);
    }

    $('div.dr-summary__tax > .item-value').text(formattedTax);
    $('div.dr-summary__total > .total-value').text(formattedOrderTotal);
    $('.dr-summary').removeClass('dr-loading');
  };

  const getEntityCode = () => {
    return drgc_params.order && drgc_params.order.order ?
      drgc_params.order.order.businessEntityCode :
      (drgc_params.cart && drgc_params.cart.cart ? drgc_params.cart.cart.businessEntityCode : '');
  };

  const getCompliance = (digitalriverjs, entityCode, locale) => {
    return entityCode && locale ? digitalriverjs.Compliance.getDetails(entityCode, locale).disclosure : {};
  };

  const applyLegalLinks = (digitalriverjs) => {
    const entityCode = getEntityCode();
    const locale = drgc_params.drLocale;
    const complianceData = getCompliance(digitalriverjs, entityCode, locale);

    if (Object.keys(complianceData).length) {
      $('.dr-resellerDisclosure').prop('href', complianceData.resellerDisclosure.url);
      $('.dr-termsOfSale').prop('href', complianceData.termsOfSale.url);
      $('.dr-privacyPolicy').prop('href', complianceData.privacyPolicy.url);
      $('.dr-cookiePolicy').prop('href', complianceData.cookiePolicy.url);
      $('.dr-cancellationRights').prop('href', complianceData.cancellationRights.url);
      $('.dr-legalNotice').prop('href', complianceData.legalNotice.url);
    }
  };

  const displayPreTAndC = () => {
    if (drgc_params.googlePayBtnStatus && drgc_params.googlePayBtnStatus === 'LOADING') return;
    if (drgc_params.applePayBtnStatus && drgc_params.applePayBtnStatus === 'LOADING') return;
    $('.dr-preTAndC-wrapper').show();
  };

  const displayAlertMessage = (message) => {
    alert('ERROR! ' + message);
  };

  const apiErrorHandler = (jqXHR) => {
    drToast.displayMessage(getAjaxErrorMessage(jqXHR), 'error');
  };

  const resetBodyOpacity = () => {
    $('body').css({'pointer-events': 'auto', 'opacity': 1});
  };

  const getPermalink = (productID) => {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'POST',
        url: drgc_params.ajaxUrl,
        data: {
          action: 'get_permalink',
          productID
        },
        success: (data) => {
          resolve(data);
        },
        error: (jqXHR) => {
          reject(jqXHR);
        }
      });
    });
  };

  const resetFormSubmitButton = ($form) => {
    $form.find('button[type="submit"]').removeClass('sending').blur();
  };

  const getAjaxErrorMessage = (jqXHR) => {
    let errMsg = localizedText.undefined_error_msg;

    if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
      const err = jqXHR.responseJSON.errors.error[0];
      switch (err.code) {
        case 'restricted-bill-to-country':
        case 'restricted-ship-to-country':
          errMsg = localizedText.address_error_msg;
          break;

        case 'cart-fraud-failure':
        case 'order-fraud-failure':
          errMsg = localizedText.unable_place_order_msg;
          break;

        default:
          errMsg = err.description;
      }
    }
    return errMsg;
  };

  const setShippingOption = (option, freeShipping) => {
    const html = `
      <div class="field-radio">
        <input type="radio"
          name="selector"
          id="shipping-option-${option.id}"
          data-cost="${option.formattedCost}"
          data-id="${option.id}"
          data-desc="${option.description}"
        >
        <label for="shipping-option-${option.id}">
          <span>${option.description}</span>
          <span class="black">${freeShipping ? localizedText.free_label : option.formattedCost}</span>
        </label>
      </div>
    `;

    $('#checkout-delivery-form .dr-panel-edit__el').append(html);
  };

  const getSupportedCountries = (addressType) => {
    const countryCodes = $('#' + addressType + '-field-country > option').map((index, element) => element.value).get();
    countryCodes.shift();

    return countryCodes;
  };

  const createCartRequest = (event, requestShipping) => {
    const cartRequest = {cart: {}};
    const billingAddressObj = {
      id: 'billingAddress',
      firstName: event.billingAddress.firstName,
      lastName: event.billingAddress.lastName,
      line1: event.billingAddress.address.line1,
      line2: event.billingAddress.address.line2,
      city: event.billingAddress.address.city,
      countrySubdivision: event.billingAddress.address.state || 'NA',
      postalCode: event.billingAddress.address.postalCode,
      country: event.billingAddress.address.country,
      phoneNumber: event.billingAddress.phone,
      emailAddress: event.billingAddress.email
    };

    cartRequest.cart.billingAddress = billingAddressObj;

    if (requestShipping) {
      const shippingAddressObj = {
        id: 'shippingAddress',
        firstName: event.shippingAddress.firstName,
        lastName: event.shippingAddress.lastName,
        line1: event.shippingAddress.address.line1,
        line2: event.shippingAddress.address.line2,
        city: event.shippingAddress.address.city,
        countrySubdivision: event.shippingAddress.address.state || 'NA',
        postalCode: event.shippingAddress.address.postalCode,
        country: event.shippingAddress.address.country,
        phoneNumber: event.shippingAddress.phone,
        emailAddress: event.shippingAddress.email
      };

      cartRequest.cart.shippingAddress = shippingAddressObj;
    }

    return cartRequest;
  };

  const isSubsAddedToCart = (lineItems) => {
    if (!lineItems.length) return false;

    for (let i = 0; i < lineItems.length; i++) {
      const lineItem = lineItems[i];
      const customAttributes = lineItem.product.customAttributes.attribute || [];

      if (customAttributes.some(attr => attr.name === 'subscriptionType')) return true;
    }

    return false;
  };

  return {
    createDisplayItems,
    createShippingOptions,
    updateShippingOptions,
    getBaseRequestData,
    updateDeliverySection,
    updateAddressSection,
    updateSummaryPricing,
    applyLegalLinks,
    displayPreTAndC,
    displayAlertMessage,
    apiErrorHandler,
    resetBodyOpacity,
    getPermalink,
    getEntityCode,
    getCompliance,
    resetFormSubmitButton,
    getAjaxErrorMessage,
    setShippingOption,
    getSupportedCountries,
    createCartRequest,
    isSubsAddedToCart
  };
})(jQuery, drgc_params);

export default CheckoutUtils;
