<div class="form-check dr-preTAndC-wrapper" style="display: none;">
    <input class="form-check-input" type="checkbox" id="dr-preTAndC">
    <label class="form-check-label" for="dr-preTAndC">
        <?php
            $tos_link = '<a href="#" target="_blank" class="dr-termsOfSale">' . __( 'Terms of Sale', 'digital-river-global-commerce' ) . '</a>';
            $pp_link = '<a href="#" target="_blank" class="dr-privacyPolicy">' . __( 'Privacy Policy', 'digital-river-global-commerce' ) . '</a>';
            $entity_name = drgc_get_business_entity_name( $cart['cart']['businessEntityCode'] );
            printf( __( 'I agree to %s and %s of %s', 'digital-river-global-commerce' ), $tos_link, $pp_link, $entity_name );
        ?>
    </label>
</div>
