<?php
require_once dirname(__FILE__) . '/../libs/requests.php';

class USClient
{
    // Public properties
    public $APIVERSION = 1;
    public $BASEURL = "http://hiringapi.dev.voxel.net";
    public $ScriptFile;
    public $UserName;
    public $Password;
    public $AUTHTOKEN;

    // Constructor and Destructor
    function __construct($username=null, $password=null) {
        $this->UserName = $username;
        $this->Password = $password;
        $this->Requests = new Requests();

        if ($this->APIVERSION == 2) {
            if ( empty($username) and empty($password)) {
                print("Version 2 call requires username and password");
            }
            $this->auth();
        }
    }
    function __destruct() {
        print("Terminate");
    }
    
    public function api_url() {
        return($this->BASEURL . "/v" . $this->APIVERSION . "/");
    }
    
    // All public functions
    public function run_script($scriptfile=null) {
        if (!empty($scriptfile)) {
            $this->ScriptFile = $scriptfile;
        }
        $fp = fopen($this->ScriptFile, "r");
        if (!$fp) {
            die("Error opening $fname\n");
        }
        while (!feof($fp)) {
            $line = fgets($fp);
            $this->_execute_command($line);
        }
        fclose($fp);
    }
    

    // HTTP script functions
    public function auth($username=null, $password=null) {
        if ($username) {
            $this->UserName = $username;
        }
        if ($password) {
            $this->Password = $password;
        }
        if (!$this->UserName or !$this->Password) {
            die("Cannot authenticate without username and password");
        }
        printf("Will authenticate with %s / %s", 
                $this->Username, $this->Password);
    }
    
    public function getter($key=null) {
        $url = $this->_build_url("key", array('key'=>$key));
        $this->Requests->GET($url);
    }
    public function setter($key=null,$value=null) {
        $url = $this->_build_url("key", array('key'=>$key, 'value'=>$value));
        $this->Requests->POST($url, array('key'=>$key, 'value'=>$value));
    }
    public function lister() {
        $url = $this->_build_url("list", array());
        $this->Requests->GET($url);
    }
    public function deleter($key=null) {
        $url = $this->_build_url("key", array('key'=>$key));
        $this->Requests->DELETE($url);
    }

    // Private Functions
    private function _execute_command($line) {
        $line = trim($line);
        // Process each line's arguments
        if (preg_match('/^\s*#/', $line)) return;
        // ignore blank lines
        if (preg_match('/^\s*$/', $line)) return;
        
        $args = preg_split('/\s+/', $line);
        switch($args[0]) {
            case 'auth': 
                $this->auth($args[1], $args[2]);
                break;
            case 'get':
                $this->getter($args[1]);
                break;
            case 'put':
            case 'set':
                $this->setter($args[1], $args[2]);
                break;
            case 'delete':
                $this->deleter($args[1]);
                break;
            case 'list':
                $this->lister();
                break;
            default:
                print("Unknown method $args[0]");
                break;
        }
    }

    private function _build_url($resource, $params) {
        $url = "";
        foreach ($params as $key => $val) {
            $url = $url . "$key=$val&";
        }
        if ($this->APIVERSION == 2 && !empty($this->AUTHTOKEN)) {
            $url = $url . "token=" . $this->AUTHTOKEN;
        }
        $url = $this->api_url() . $resource . "?" . $url;
        // clean extraneous &
        $url = preg_replace('/\&$|\?$/', '', $url);
        
        print("URL is now $url\n");
        return $url;
    }

}

?>