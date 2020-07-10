<?php
/**
 * Class Test_DRGC_Tables
 *
 * @package Digital_River_Global_Commerce
 */

require_once 'includes/class-drgc-tables.php';

class Test_DRGC_Tables extends WP_UnitTestCase {
    
    /**
     * Test it creates session table
     */
    public function test_it_creates_session_table() {
        new DRGC_Tables();

        global $wpdb;

		$result = $wpdb->query(
            $wpdb->prepare(
                "SELECT count(*)
                FROM information_schema.TABLES
                WHERE (TABLE_SCHEMA = %s) AND (TABLE_NAME = %s)",
                $wpdb->prefix . 'drgc_sessions',
                $wpdb->dbname
            )
        );
        
        $this->assertTrue((bool) $result);
    }
}
