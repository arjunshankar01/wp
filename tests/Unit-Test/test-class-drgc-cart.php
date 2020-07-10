<?php
/**
 * Class Test_DRGC_Cart
 *
 * @package Digital_River_Global_Commerce
 */

use GuzzleHttp\Psr7;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

require_once 'includes/class-drgc-authenticator.php';
require_once 'includes/class-drgc-cart.php';

class Test_DRGC_Cart extends WP_UnitTestCase {
	
	protected $authenticator;
	
	public function setUp() {
		parent::setUp();

		$this->authenticator = new DRGC_Authenticator;
    }

    /**
	 * Test it retrieves cart object
	 */
    public function test_it_retrieves_cart_object() {
		$payload = json_decode(file_get_contents(__DIR__.'/Mock/retrieve_cart-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
			new Response($payload->status, array(), $stream)
        ]));

		$res = (new DRGC_Cart($this->authenticator, $handler))->retrieve_cart();
        
        $this->assertArrayHasKey('cart', $res);
        $this->assertFalse(empty($res['cart']));
    }

    /**
	 * Test it retrieves placed order
	 */
    public function test_it_retrieves_order() {
        global $_GET;

        $_GET['order'] = 0707070707;

        $payload = json_decode(file_get_contents(__DIR__.'/Mock/retrieve_order-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
			new Response($payload->status, array(), $stream)
        ]));

		$res = (new DRGC_Cart($this->authenticator, $handler))->retrieve_order();
            
        $this->assertArrayHasKey('order', $res);
        $this->assertFalse(empty($res['order']));
    }

    /**
	 * Test it retrieves all site supported currencies and locales
	 */
    public function test_it_retrieves_currencies_and_locales() {
        $payload = json_decode(file_get_contents(__DIR__.'/Mock/retrieve_site_data-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
			new Response($payload->status, array(), $stream)
        ]));

		$res = (new DRGC_Cart($this->authenticator, $handler))->retrieve_currencies();
        
        $this->assertArrayHasKey('site', $res);
        $this->assertArrayHasKey('localeOptions', $res['site']);
    }


    /**
	 * Test it fetches all line items (cart items)
	 */
    public function test_it_fetches_line_items() {
        $payload = json_decode(file_get_contents(__DIR__.'/Mock/retrieve_line_items-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
			new Response($payload->status, array(), $stream)
        ]));

		$res = (new DRGC_Cart($this->authenticator, $handler))->fetch_items();
        
        $this->assertArrayHasKey('lineItems', $res);
        $this->assertArrayHasKey('lineItem', $res['lineItems']);
    }

    /**
	 * Test it updates line items
	 */
    public function test_it_updates_line_items() {
        $handler = HandlerStack::create(new MockHandler([
			new Response(201, array(), null)
        ]));
        
        $this->assertTrue(
            (new DRGC_Cart($this->authenticator, $handler))->update_line_items(['productId' => 99999999 ])
        );
    }

    /**
	 * Test it retrieves billing address
	 */
    public function test_it_retrieves_billing_address() {
        $payload = json_decode(file_get_contents(__DIR__.'/Mock/retrieve_billing_address-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
			new Response($payload->status, array(), $stream)
        ]));

		$res = (new DRGC_Cart($this->authenticator, $handler))->get_billing_address();
        
        $this->assertArrayHasKey('address', $res);
    }
}
