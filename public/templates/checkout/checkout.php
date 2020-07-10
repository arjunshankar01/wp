<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.digitalriver.com
 * @since      1.0.0
 *
 * @package    Digital_River_Global_Commerce
 * @subpackage Digital_River_Global_Commerce/public/templates/parts
 */

if ( $cart['cart']['totalItemsInCart'] === 0 ) {
?>
    <p class="dr-checkout__empty-cart"><?php echo __( 'Your cart is empty!', 'digital-river-global-commerce' ); ?></p>
    <div class="dr-checkout__actions-top">
        <a href="<?php echo get_post_type_archive_link( 'dr_product' ); ?>" class="continue-shopping"><?php echo __( 'Continue Shopping', 'digital-river-global-commerce' ); ?></a>
    </div>
<?php
    return;
}

$is_logged_in = $customer && ( $customer['id'] !== 'Anonymous' );
$customerEmail = $is_logged_in ? $customer['emailAddress'] : '';
$default_address = $cart['cart']['billingAddress'];
$addresses = [];

if ( $is_logged_in ) {
    $addresses = $customer['addresses']['address'] ?? [];

    if ( count( $addresses ) > 0 ) {
        foreach( $addresses as $addr ) {
            if ( $addr['isDefault'] === 'true' ) {
                $default_address = $addr;
                break;
            }
        }
    }
}

$check_subs = drgc_is_subs_added_to_cart( $cart );
?>

<div class="dr-checkout-wrapper" id="dr-checkout-page-wrapper">
    <div class="dr-checkout-wrapper__actions">
        <div class="back-link">

            <a href="" onclick="history.back(); return false;">&#60; Back</a>

        </div>

    </div>

    <div class="dr-checkout-wrapper__content">

        <div class="dr-checkout">

            <div class="dr-checkout__el dr-checkout__cloudpay">

                <?php include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-pretandc.php'; ?>

                <?php if ( ! $check_subs['has_subs'] ) : ?>

                    <?php include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-googlepay.php'; ?>

                    <?php include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-applepay.php'; ?>

                <?php endif; ?>

                <div class="invalid-feedback" id="dr-preTAndC-err-msg"></div>

            </div>

            <?php include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-email.php'; ?>

            <?php if( $cart['cart']['hasPhysicalProduct'] ) :
                include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-shipping.php';
            endif; ?>

            <?php include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-billing.php'; ?>

            <?php if( $cart['cart']['hasPhysicalProduct'] ) :
                include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-delivery.php';
            endif; ?>

            <?php include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-payment.php'; ?>

            <?php include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-confirmation.php'; ?>

        </div>

        <div class="dr-summary dr-summary--checkout">

            <?php drgc_currency_toggler( 'd-none' ); ?>

            <div class="dr-summary__products">

                <?php if ( 1 < count($cart['cart']['lineItems']) ) : ?>
                    <?php foreach ($cart['cart']['lineItems']['lineItem'] as $line_item): ?>
                        <?php include DRGC_PLUGIN_DIR . 'public/templates/cart/cart-product.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <?php include_once DRGC_PLUGIN_DIR . 'public/templates/checkout/checkout-summary.php'; ?>

        </div>

    </div>

    <div class="dr-checkout__actions-bottom">

        <a href="<?php echo get_post_type_archive_link( 'dr_product' ); ?>" class="continue-shopping"><?php echo __( 'Continue Shopping', 'digital-river-global-commerce' ); ?></a>

    </div>

</div>
