<?php
/**
 * Provide a publidr-facing view for the plugin
 *
 * This file is used to markup the publidr-facing aspects of the plugin.
 *
 * @link       https://www.digitalriver.com
 * @since      1.0.0
 *
 * @package    Digital_River_Global_Commerce
 * @subpackage Digital_River_Global_Commerce/public/partials
 */
?>

<?php
$shipping_price = $cart['cart']['pricing']['formattedShippingAndHandling'];
$discount       = $cart['cart']['pricing']['discount']['value'];
$formatted_discount = $cart['cart']['pricing']['formattedDiscount'];
$subtotal_items = $cart['cart']['totalItemsInCart'];
//$subtotal_with_discount_value = $cart['cart']['pricing']['formattedSubtotalWithDiscount'];
$formattedSubtotal = $cart['cart']['pricing']['formattedSubtotal'];
$formattedOrderTotal = $cart['cart']['pricing']['formattedOrderTotal'];
$delivery_info = 'Delivery in 2-5 working days and extended 30 days return period';
$estimated_tax_value = $cart['cart']['pricing']['formattedTax'];
// currency code from the subtotal element
$currency_code = $cart['cart']['pricing']['subtotal']['currency'];

$shippingTax = 0;
$productTax = 0;

if(isset($cart["cart"]['lineItems']) && isset($cart["cart"]['lineItems']['lineItem'])) {					
	$lineItems = $cart["cart"]['lineItems']['lineItem'];
	foreach($lineItems as $item){
		$productTax += $item['pricing']['productTax']['value'];
		$shippingTax += $item['pricing']['shippingTax']['value'];						
	}
}

// dirty hack to get the formatted product and shipping tax
$extract_amount_format = str_replace($currency_code, '', $formattedSubtotal);
$substr = substr($extract_amount_format, -3);
$dec_point = (strstr($substr, '.')) ? '.' : ',';
$extract_amount_format = str_replace($substr, '', $extract_amount_format);

// Tax format "XX,XXX.XX"
$thousands_sep = (strstr($extract_amount_format, ',')) ? ',' : '.'; // may change later
// Check if there is any tax returned in the format "XX XXX,XX"
//$thousands_sep = (strstr($extract_amount_format, ' ')) ? ' ' : $thousands_sep; 
//number_format ( float $number , int $decimals = 0 , string $dec_point = "." , string $thousands_sep = "," )
$productTax = number_format($productTax, 2, $dec_point,$thousands_sep);
$shippingTax = number_format($shippingTax, 2, $dec_point,$thousands_sep);

// get the formatting of the currency code from the requisition pricing formattedSubtotal
$productTax = preg_replace('/[\d\,\.\s]+/', $productTax, $formattedSubtotal);
$shippingTax = preg_replace('/[\d\,\.\s]+/', $shippingTax, $formattedSubtotal);

// Issue to resolve with the above preg_replace line
// if the formatted subtotal is for example "23 236,83EUR", then the regex replace fails to get the proper tax output format.
// it currently returns for example: "0,00 0,00EUR" 
/*
Handle the following formats:
851,28€
1.452,49€
851.28EUR
USD851.28
$851.28
25 690,64EUR
*/

/*
Next phase: Add logic in the admin for pretty pricing
*/
?>

<div class="dr-summary dr-summary--cart">


	 <div class="dr-summary__discounted-subtotal">

        <p class="discounted-subtotal-label"><?php echo __( 'Subtotal', 'digital-river-global-commerce' ) ?></p>

        <p class="discounted-subtotal-value"><?php echo $formattedSubtotal; ?></p>

    </div>

	<div class="dr-summary__tax">

		<p class="item-label"><?php echo drgc_should_display_vat( $currency_code ) ? __( 'Estimated VAT Included', 'digital-river-global-commerce' ) : __( 'Estimated Tax', 'digital-river-global-commerce' ) ?></p>

		<p class="item-value"><?php echo $productTax; ?></p>

	</div>
<?php if( $cart['cart']['hasPhysicalProduct'] ) : ?>
    <div class="dr-summary__shipping">

        <p class="shipping-label"><?php echo __( 'Estimated Shipping', 'digital-river-global-commerce' ) ?></p>

        <p class="shipping-value"><?php echo $shipping_price; ?></p>

    </div>

	<div class="dr-summary__shipping_tax">

		<p class="item-label"><?php echo drgc_should_display_vat( $currency_code ) ? __( 'Estimated Shipping VAT', 'digital-river-global-commerce' ) : __( 'Estimated Shipping Tax', 'digital-river-global-commerce' ) ?></p>

		<p class="item-value"><?php echo $shippingTax; ?></p>

	</div>
<?php endif; ?>
	<div class="dr-summary__discount" <?php if ( $discount === 0 ) echo 'style="display: none;"' ?>>

        <p class="discount-label"><?php echo __( 'Discount', 'digital-river-global-commerce' ) ?></p>

        <p class="discount-value"><?php echo '-' . $formatted_discount; ?></p>

    </div>

    <div class="dr-summary__promo-code">
        <a class="promo-code-toggle" href="javascript:void(0);"><?php echo __( 'Add Promo Code', 'digital-river-global-commerce' ) ?> +</a>
        <div class="promo-code-wrapper" style="display: none;">
            <input type="text" class="form-control" id="promo-code" name="promo_code" placeholder="<?php echo __( 'Promo Code', 'digital-river-global-commerce' ) ?>">
            <button type="button" class="dr-btn" id="apply-promo-code-btn"><?php echo __( 'Apply', 'digital-river-global-commerce' ) ?></button>
            <div class="invalid-feedback" id="dr-promo-code-err-field"></div>
        </div>
    </div>

	<div class="dr-summary__subtotal">

        <p class="subtotal-label"><?php echo __( 'Order Total', 'digital-river-global-commerce' ) ?></p>

        <p class="subtotal-value"><?php echo $formattedOrderTotal; ?></p>

    </div>

	<?php if ( 1 < count($cart['cart']['lineItems'] )) : ?>

        <a href="<?php echo esc_url( drgc_get_page_link( 'checkout' ) ); ?>" class="dr-summary__proceed-checkout dr-btn"><?php echo __( 'Proceed to checkout', 'digital-river-global-commerce' ) ?></a>

	<?php endif; ?>


    <?php /*
    <div class="dr-summary__delivery delivery-info">

        <span class="delivery-info__icon"></span>

        <span class="delivery-info__text"><?php echo $delivery_info; ?></span>

    </div>
 */ ?>

</div>
