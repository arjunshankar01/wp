<?php
/**
 * Provide a public-legal footer for the plugin
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

<div class="container dr-legal-footer text-center">
  <p class="dr-legalResellerStatement">
  	<a href="#" target="_blank" class="dr-resellerDisclosure"><?php echo drgc_get_business_entity_name( $entity_code ); ?></a><?php echo __( ' is the authorized reseller and merchant of the products and services offered within this store.', 'digital-river-global-commerce' ); ?>
  </p>
  <p class="dr-legalPolicyLinks">
  	<a href="#" target="_blank" class="dr-privacyPolicy"><?php echo __( 'Privacy Policy', 'digital-river-global-commerce' ); ?></a>
  	<a href="#" target="_blank" class="dr-termsOfSale"><?php echo __( 'Terms of Sale', 'digital-river-global-commerce' ); ?></a>
  	<a href="#" target="_blank" class="dr-cookiePolicy"><?php echo __( 'Cookies', 'digital-river-global-commerce' ); ?></a>
  	<a href="#" target="_blank" class="dr-cancellationRights"><?php echo __( 'Cancellation Right', 'digital-river-global-commerce' ); ?></a>
  	<a href="#" target="_blank" class="dr-legalNotice"><?php echo __( 'Legal Notice', 'digital-river-global-commerce' ); ?></a>
  </p>
</div>
