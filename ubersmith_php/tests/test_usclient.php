<?php 
require_once dirname(__FILE__) . '/../src/USCLient.php';
require_once dirname(__FILE__) . '/../libs/Requests.php';

/**
 * Test class for USCLient
 * Run tests using PHPUnit as:
 *  pear install phpunit
 *  phpunit 
 */
class USClientTest extends PHPUnit_Framework_TestCase 
{
    protected $USC;
    protected function setUp() {
        $this->USC = new USClient();
    }
    protected function tearDown() {
    }
    
    public function testV1APICalls_should_pass() {
        print "Testing v1 positive sequence";
        $this->assertTrue(!empty(preg_match('/^ok/', $this->USC->setter("mykey", "myvalue")));
        $this->assertTrue($empty(preg_match('/^ok/', $this->USC->getter("mykey")));
    }
} 

class RequestsTest extends PHPUnit_Framework_TestCase
{
    protected $R;
    protected $URL = "http://"; // Some RESTFUL server
    protected function setUp() {
        $this->R = new Requests();
    }
    
    public function testGET_should_pass() {
        $resp = $this->R->GET($this->URL);
        $this->assertTrue($resp->code == 200));
    }
    public function testPOST_should_pass() {
        $resp = $this->R->POST($this->URL);
        $this->assertTrue($resp->code == 200));
    }
    public function testPUT_should_pass() {
        $resp = $this->R->PUT($this->URL);
        $this->assertTrue($resp->code == 200));
    }
    public function testDELETE_should_pass() {
        $resp = $this->R->DELETE($this->URL);
        $this->assertTrue($resp->code == 200));
    }

    // Failure cases
    public function testDELETE_should_fail_for_localhost() {
        $resp = $this->R->DELETE("http://localhost/");
        $this->assertTrue($resp->code == 405));
    }
}
?>
