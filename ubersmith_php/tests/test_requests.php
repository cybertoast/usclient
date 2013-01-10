<?php 
require_once dirname(__FILE__) . '/../libs/Requests.php';

class RequestsTest extends PHPUnit_Framework_TestCase
{
    protected $R;
    protected $URL = "http://localhost"; // Some RESTFUL server
    protected function setUp() {
        $this->R = new Requests();
    }
    
    public function testGET_should_pass() {
        $resp = $this->R->GET($this->URL);
        $this->assertTrue($resp->status_code == 200);
    }
    public function testPOST_should_pass() {
        $resp = $this->R->POST($this->URL);
        $this->assertTrue($resp->status_code == 200);
    }
    public function testPUT_should_pass() {
        $resp = $this->R->PUT($this->URL);
        $this->assertTrue($resp->status_code == 200);
    }
    public function testDELETE_should_pass() {
        $resp = $this->R->DELETE($this->URL);
        $this->assertTrue($resp->status_code == 200);
    }

    // Failure cases
    public function testDELETE_should_fail_for_localhost() {
        $resp = $this->R->DELETE($this->URL);
        $this->assertTrue($resp->status_code == 405);
    }
}
?>
