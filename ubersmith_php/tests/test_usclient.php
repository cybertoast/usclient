<?php 
require_once dirname(__FILE__) . '/../src/USCLient.php';

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
        print ("Testing v1 positive sequence");

        $resp = $this->USC->setter("mykey", "myvalue");
        $this->assertTrue(preg_match('/^ok/', $resp->status) == 1);

        $resp = $this->USC->getter("mykey");
        $this->assertTrue(preg_match('/^ok/', $resp->status) == 1);
    }
} 
?>
