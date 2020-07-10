<?php
/**
 * Class Test_DRGC_Session
 *
 * @package Digital_River_Global_Commerce
 */


require_once 'includes/class-drgc-session.php';
require_once 'includes/class-drgc-tables.php';


class Test_DRGC_Session extends WP_UnitTestCase {
	
	protected $session;
	
	public function setUp() {
		parent::setUp();
    
        // Create tables
        new DRGC_Tables();
		$this->session = new DRGC_Session;
    }

    /**
     * Tests it generates m5 hash
     */
    public function test_it_generates_md5_session_id() {
        $this->session->generate_session_id();

        $md5 = $this->session->session_id;

        $this->assertTrue(strlen($md5) === 32 && ctype_xdigit($md5));
    }

    /**
     * Tests it checks for valid md5
     */
    public function test_it_checks_for_valid_md5() {
        $md5 = $this->session->session_id;

        $this->assertTrue((bool)$this->session->is_valid_md5('5d41402abc4b2a76b9719d911017c592'));
        $this->assertFalse((bool)$this->session->is_valid_md5('d41402abc4b2a76b9719d911017c592'));
    }

    /**
     * Tests it stores session data
     */
    public function test_it_stores_session_data() {
        $this->session->generate_session_id();

        $session_data = ['access_token' => 'access_token', 'session_token' => 'session_token', 'refresh_token' => 'refresh_token'];

        $this->session->generate_session_cookie_data(
            $session_data
        );

        $this->assertEquals(
            $session_data,
            $this->session->get_session_data()
        );
    }

    /**
     * Tests it retrieves session data
     */
    public function test_it_retrieves_session_data() {
        $this->session->generate_session_id();

        $session_data = ['access_token' => 'access_token', 'session_token' => 'session_token', 'refresh_token' => 'refresh_token'];

        $this->session->generate_session_cookie_data(
            $session_data
        );

        $this->assertEquals(
            $session_data,
            $this->session->get_session_data()
        );
    }

    /**
     * Tests it sets expiration time
     */
    public function test_it_sets_expiration_time() {
        $this->session->set_expiration_time();

        $this->assertGreaterThan(
            time(),
            $this->session->expires
        );
    }

    /**
     * Tests it clears the session
     */
    public function test_it_clears_the_session() {
        $this->session->generate_session_id();

        $session_data = ['access_token' => 'access_token', 'session_token' => 'session_token', 'refresh_token' => 'refresh_token'];

        $this->session->generate_session_cookie_data(
            $session_data
        );

        $this->assertEquals(
            $session_data,
            $this->session->get_session_data()
        );

        $this->session->clear_session();

        $this->assertNull($this->session->get_session_data());
        $this->assertTrue(empty($this->session->get_session_cookie_data()));
    }
}