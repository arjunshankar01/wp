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
?>

<section class="no-results not-found">
    <h2 class="entry-title">
        <?php _e( 'Nothing Found', 'digital-river-global-commerce' ); ?>
    </h2>
    <div class="entry-content">
        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
            <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'digital-river-global-commerce' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
        <?php elseif ( is_search() ) : ?>
            <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'digital-river-global-commerce' ); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'digital-river-global-commerce' ); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>
