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

<div class="pd-item-thumbnail">
    <img src="<?php echo get_post_meta( get_the_ID(), 'gc_thumbnail_url', true ); ?>" alt="">
</div>
