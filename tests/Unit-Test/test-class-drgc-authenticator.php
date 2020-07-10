<?php
/**
 * Class Test_WP_Simple_Authenticator
 *
 * @package Digital_River_Global_Commerce
 */
use GuzzleHttp\Psr7;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

require_once 'includes/class-drgc-authenticator.php';

class Test_DRGC_Authenticator extends WP_UnitTestCase {
	/**
	 * Test it ganerate session token
	 */
    public function test_it_generates_session_token() {
        $payload = json_decode(file_get_contents(__DIR__.'/Mock/session_token-response-body.txt'));
        
        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
            new Response($payload->status, array(), $stream),
        ]));
            
        $this->assertEquals(
            $payload->result->session_token,
            (new DRGC_Authenticator( $handler ))->generate_dr_session_token()
        );
    }

	/**
	 * Test it ganerate access token
	 */
	public function test_it_generates_access_token() {
        $payload = json_decode(file_get_contents(__DIR__.'/Mock/access_token-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
            new Response(200, array(), $stream),
        ]));

        $res = (new DRGC_Authenticator( $handler ))->generate_access_token();

        $this->assertArrayHasKey('access_token', $res);
        $this->assertArrayHasKey('token_type', $res);
        $this->assertArrayHasKey('expires_in', $res);
        $this->assertArrayHasKey('refresh_token', $res);        
    }
    
    /**
	 * Test it schedules refresh of acces token
	 */
	public function test_it_schedules_refresh_of_acces_token() {
        (new DRGC_Authenticator)->set_schedule_refresher();

        $this->assertTrue( has_action( 'dr_refresh_access_token' ) );
        $this->assertTrue( is_int( wp_next_scheduled( 'refresh_access_token_event' ) ) );
    }
    
    /**
	 * Test it schedules refresh of acces token
	 */
	public function test_refreshes_access_token() {
        $payload = json_decode(file_get_contents(__DIR__.'/Mock/access_token-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
            new Response(200, array(), $stream)
        ]));

        $res = (new DRGC_Authenticator( $handler ))->do_refresh_access_token();

        $this->assertArrayHasKey('access_token', $res);
        $this->assertArrayHasKey('token_type', $res);
        $this->assertArrayHasKey('expires_in', $res);
        $this->assertArrayHasKey('refresh_token', $res);        
    }
    
    /**
	 * Test it schedules refresh of acces token
	 */
	public function test_it_generates_access_token_by_dr_reference_id() {
        $payload = json_decode(file_get_contents(__DIR__.'/Mock/access_token-response-body.txt'));

        $stream = Psr7\stream_for(json_encode($payload->result));

        $handler = HandlerStack::create(new MockHandler([
            new Response(200, array(), $stream)
        ]));

        $res = (new DRGC_Authenticator( $handler ))->generate_access_token_by_ref_id(md5(rand(1, 30)));

        $this->assertArrayHasKey('access_token', $res);
        $this->assertArrayHasKey('token_type', $res);
        $this->assertArrayHasKey('expires_in', $res);
        $this->assertArrayHasKey('refresh_token', $res);        
	}
}
