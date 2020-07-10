<?php
/**
 * Class Test_DRGC_Activator
 *
 * @package Digital_River_Global_Commerce
 */

require_once 'includes/class-drgc-deactivator.php';
require_once 'includes/class-drgc-tables.php';

class Test_DRGC_Dectivator extends WP_UnitTestCase {
    /**
     * Test it creates short code pages
     */
    public function test_it_drops_sessions_table() {
        new DRGC_Tables();

        global $wpdb;

        $result = $wpdb->query(
            "SHOW TABLES LIKE '{$wpdb->prefix}drgc_sessions'"
        );

        // $this->assertTrue((bool) $result); // Table Exists

        DRGC_Deactivator::deactivate();

        $result = $wpdb->get_results(
            "SHOW TABLES LIKE '{$wpdb->prefix}drgc_sessions'"
        );
        
        // $this->assertFalse((bool) $result); 
    }

    public function test_it_deactivates_all_schedule_events() {
        // TODO: Assert exists first
        DRGC_Deactivator::deactivate();

        $this->assertFalse(wp_next_scheduled('dr_products_import'));
        $this->assertFalse(wp_next_scheduled('dr_refresh_access_token'));
        $this->assertFalse(wp_next_scheduled('dr_clean_expired_sessions'));
    }

    public function test_it_removes_options() {
        // TODO: Assert exists first
        DRGC_Deactivator::deactivate();

        $this->assertFalse(get_option("drgc_db_version"));
    }

    public function test_it_removes_cached_dr_products() {
        // TODO: Assert exists first
        DRGC_Deactivator::deactivate();

        $this->assertFalse(wp_cache_get('dr_products'));
    }
}
