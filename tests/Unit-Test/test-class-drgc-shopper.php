<?php
/**
 * Class Test_DRGC_Shopper
 *
 * @package Digital_River_Global_Commerce
 */

use GuzzleHttp\Psr7;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

require_once 'includes/class-drgc-authenticator.php';
require_once 'includes/class-drgc-shopper.php';

class Test_DRGC_Shopper extends WP_UnitTestCase {
	
	protected $authenticator;
	
	public function setUp() {
		parent::setUp();

		$this->authenticator = new DRGC_Authenticator;
	}

	/**
	 * Test it creates shopper
	 */
	public function test_it_creates_shopper() {
        $handler = HandlerStack::create(new MockHandler([
			new Response(200, array(), null),
			new Response(201, array(), null)
        ]));

        $res = (new DRGC_Shopper($this->authenticator, $handler))->create_shopper('UniqueName', 'secret', 'First', 'Last', 'AnyEmail@any.com', md5(rand(1,30)));
		
        $this->assertTrue($res);
	}

	/**
	 * Test it retrieves shopper
	 */
	public function test_it_retrieves_shopper() {
		$payload = json_decode(file_get_contents(__DIR__.'/Mock/retrieve_shopper-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
			new Response($payload->status, array(), $stream)
        ]));

		$res = (new DRGC_Shopper($this->authenticator, $handler))->retrieve_shopper();
		
        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('username', $res);
        $this->assertArrayHasKey('externalReferenceId', $res);
        $this->assertArrayHasKey('emailAddress', $res);  
	}

	/**
	 * Test it gets access token information
	 */
	public function test_it_gets_access_token_information() {
		$payload = json_decode(file_get_contents(__DIR__.'/Mock/access_token_info-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
			new Response($payload->status, array(), $stream)
		]));
		
		$res = (new DRGC_Shopper($this->authenticator, $handler))->get_access_token_information();

        $this->assertArrayHasKey('sessionId', $res);
        $this->assertArrayHasKey('userId', $res);
        $this->assertArrayHasKey('externalReferenceId', $res);
		$this->assertArrayHasKey('cartId', $res);  
	}

	/**
	 * Test it updates shopper fields
	 */
	public function test_it_updates_shopper_fields() {
        $handler = HandlerStack::create(new MockHandler([
			new Response(200, array(), null),
			new Response(204, array(), null)
		]));
		
		$this->assertTrue(
			(new DRGC_Shopper($this->authenticator, $handler))->update_shopper_fields()
		);
	}
}
