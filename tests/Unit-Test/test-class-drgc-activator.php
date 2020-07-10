<?php
/**
 * Class Test_DRGC_Activator
 *
 * @package Digital_River_Global_Commerce
 */

require_once 'includes/class-drgc-activator.php';
require_once 'includes/drgc-core-functions.php';

class Test_DRGC_Activator extends WP_UnitTestCase {
    /**
     * Test it creates short code pages
     */
    public function test_it_adds_creates_shortcode_pages() {

        DRGC_Activator::create_pages();

        $this->assertTrue(is_object(get_page_by_title('Cart')));
        $this->assertTrue(is_object(get_page_by_title('Checkout')));
        $this->assertTrue(is_object(get_page_by_title('Login')));
        $this->assertTrue(is_object(get_page_by_title('Thank You')));
    }
}
