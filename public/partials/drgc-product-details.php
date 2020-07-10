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
 * @subpackage Digital_River_Global_Commerce/public/partials
 */
?>

<form class="form-inline">
    <div id="pd-price" class="price"></div>
    <div class="pd-qty">
        <span class="qty-minus"><img src="<?php echo get_site_url(); ?>/wp-content/plugins/digital-river-global-commerce/assets/images/product-minus.svg" alt=""/></span>
        <input type="number" class="form-control pd-qty-input no-spinners" id="qty" step="1" min="1" max="999" value="1" maxlength="5" size="2" pattern="[0-9]*" inputmode="numeric">
        <span class="qty-plus"><img src="<?php echo get_site_url(); ?>/wp-content/plugins/digital-river-global-commerce/assets/images/product-plus.svg" alt=""/></span>
    </div>
    <button type="button" class="btn btn-primary dr-buy-btn" data-product-id="<?php echo $this->gc_product_id; ?>"> <?php echo __( 'Buy Now', 'digital-river-global-commerce'); ?> </button>
</form>
