<?php
/**
 * Render product details meta box.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.digitalriver.com
 * @since      1.0.0
 *
 * @package    Digital_River_Global_Commerce
 * @subpackage Digital_River_Global_Commerce/admin/partials
 */

$post = get_post( get_the_ID() );
$post_parent = $post->post_parent;
$varying_attribute_array = [];

if ( $post_parent > 0 ) {
    $base_variation_attributes = get_post_meta( $post_parent, 'variation_attributes', true );

    foreach ($base_variation_attributes as $key => $value) {
        if ( $value === 'productType' ) {
            $base_variation_attributes[$key] = 'product_type';
        }

        $attr = get_post_meta( get_the_ID(), $base_variation_attributes[$key], true );

        if ( ! empty( $attr ) ) {
            $varying_attribute_array[$value] = $attr;
        }
    }
}
?>

<div class="meta-form-group">
    <table class="form-table">
        <tbody>
            <?php if ( ! empty( $varying_attribute_array ) ) : ?>
                <fieldset>
                    <legend><span><?php echo __( 'Variation Attributes', 'digital-river-global-commerce' ); ?></span></legend>
                    <?php foreach ($varying_attribute_array as $key => $value) : ?>
                        <dl>
                            <dt>
                                <label><?php echo esc_attr( $key ); ?>: </label>
                            </dt>
                            <dd>
                                <div class="regular-text" id="variation-attributes"><?php echo esc_attr( $value ); ?></div>
                            </dd>
                        </dl>
                    <?php endforeach; ?>
                </fieldset>
            <?php endif; ?>
            <tr>
                <th scope="row"> <label for="gc-product-id"><?php echo __( 'GC Product ID', 'digital-river-global-commerce' ); ?></label></th>
                <td><input type="text" class="regular-text" id="gc-product-id" name="gc_product_id" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'gc_product_id', true ) ); ?>" readonly /></td>
            </tr>
            <tr>
                <th scope="row"> <label for="ext-reference-id"><?php echo __( 'External Reference ID', 'digital-river-global-commerce' ); ?></label></th>
                <td><input type="text" class="regular-text" id="ext-reference-id" name="external_reference_id" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'external_reference_id', true ) ); ?>" readonly /></td>
            </tr>
            <tr>
                <th scope="row"> <label for="sku"><?php echo __( 'SKU', 'digital-river-global-commerce' ); ?></label></th>
                <td><input type="text" class="regular-text" id="sku" name="sku" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'sku', true ) ); ?>" readonly /></td>
            </tr>
            <tr>
                <th scope="row"> <label for="price"><?php echo __( 'Price', 'digital-river-global-commerce' ); ?></label></th>
                <td><input type="text" class="regular-text" id="price" name="price" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'price', true ) ); ?>" readonly /></td>
            </tr>
            <tr>
                <th scope="row"> <label for="short-description"><?php echo __( 'Short Description', 'digital-river-global-commerce' ); ?></label></th>
                <td><textarea id="short-description" class="large-text" rows="3" readonly><?php echo esc_attr( get_post_meta( get_the_ID(), 'short_description', true ) ); ?></textarea></td>
            </tr>
            <tr>
                <th scope="row"> <label for="long-description"><?php echo __( 'Long Description', 'digital-river-global-commerce' ); ?></label></th>
                <td><textarea id="long-description" class="large-text" rows="10" readonly><?php echo esc_attr( get_post_meta( get_the_ID(), 'long_description', true ) ); ?></textarea></td>
            </tr>
            <tr>
                <th scope="row"> <label for="thumbnail"><?php echo __( 'Thumbnail', 'digital-river-global-commerce' ); ?></label></th>
                <td><img src="<?php echo esc_url(get_post_meta( get_the_ID(), 'gc_thumbnail_url', true ) ); ?>" alt=""/></td>
            </tr>
            <tr>
                <th scope="row"> <label for="product-image"><?php echo __( 'Product Image', 'digital-river-global-commerce' ); ?></label></th>
                <td><img src="<?php echo esc_url( get_post_meta( get_the_ID(), 'gc_product_images_url', true ) ); ?>" alt=""/></td>
            </tr>
        </tbody>
    </table>
</div>
