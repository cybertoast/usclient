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
    function __destruct() {}
    
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
            $resp = $this->_execute_command($line);
            if (!$resp) {
                continue;
            }
            $out = "";
            try {
                $ovars = get_object_vars($resp);
                if ($ovars) {
                    foreach ($ovars as $okey=>$oval) {
                        if ($okey == "status") continue;
                        if (is_array($oval))
                            $out .= implode(" ", $oval);
                        else
                            $out .= " " . $oval;
                    }                
                }
                printf("%s %s\n", $resp->status, $out);                
            } catch (Exception $e){
                var_dump($resp);
            }
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
        $this->APIVERSION = 2;
        printf("Will authenticate with %s / %s", 
                $this->UserName, $this->Password);
        $url = $this->_build_url("auth", array('user'=>$username, 'pass'=>$password));
        $resp = $this->Requests->GET($url);
        $this->AUTHTOKEN = $resp->token;
        return $resp->status;
    }
    
    public function getter($key=null) {
        $url = $this->_build_url("key", array('key'=>$key));
        $resp = $this->Requests->GET($url);
        return $resp;
    }
    public function setter($key=null,$value=null) {
        $url = $this->_build_url("key", array('key'=>$key, 'value'=>$value));
        $resp = $this->Requests->POST($url, array('key'=>$key, 'value'=>$value));
        return $resp;
    }
    public function lister() {
        $url = $this->_build_url("list", array());
        $resp = $this->Requests->GET($url);
        return $resp;
    }
    public function deleter($key=null) {
        $url = $this->_build_url("key", array('key'=>$key));
        $resp = $this->Requests->DELETE($url);
        return $resp;
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
                $resp = $this->auth($args[1], $args[2]);
                break;
            case 'get':
                $resp = $this->getter($args[1]);
                break;
            case 'put':
            case 'set':
                $resp = $this->setter($args[1], $args[2]);
                break;
            case 'delete':
                $resp = $this->deleter($args[1]);
                break;
            case 'list':
                $resp = $this->lister();
                break;
            default:
                $resp = "Unknown method $args[0]";
                break;
        }
        return $resp;
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
        return $url;
    }

}

?>