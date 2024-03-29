<?php
class Requests {
    function __construct() {
        $this->CURL = curl_init();
    }
    function __destruct() {
        curl_close ( $this->CURL ); 
    }
        
    public function GET($url) 
    {
        $CURL = curl_init();
        $this->_set_common_curl_opts($CURL, $url);
        $resp = curl_exec ( $CURL );
        if (!$resp) $resp = $this->_parse_curl_info($CURL);
        else $resp = json_decode($resp);
        curl_close($CURL);
        return ($resp);
    }
    public function PUT($url, $data=array()) {
        $CURL = curl_init();
        $postfields = $this->_data_to_postfields($data);
        $this->_set_common_curl_opts($CURL, $url);
        curl_setopt($CURL, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt( $CURL, CURLOPT_POSTFIELDS, $postfields);

        $resp = curl_exec( $CURL );        
        if (!$resp) $resp = $this->_parse_curl_info($CURL);
        else $resp = json_decode($resp);

        curl_close($CURL);
        return($resp);
    }
    /**
     * Parameter: $data should be array("key=value", "key=value")
     */
    public function POST($url, $data) {
        $CURL = curl_init();
        $postfields = $this->_data_to_postfields($data);
        $this->_set_common_curl_opts($CURL, $url);
        curl_setopt( $CURL, CURLOPT_POST, true);    // POST request
        curl_setopt( $CURL, CURLOPT_POSTFIELDS, $postfields);
        $resp = curl_exec ( $CURL );
        if (!$resp) $resp = $this->_parse_curl_info($CURL);
        else $resp = json_decode($resp);
        
        curl_close($CURL);
        return $resp;
    }

    public function DELETE($url) {
        $CURL = curl_init();
        $this->_set_common_curl_opts($CURL, $url);
        curl_setopt($CURL, CURLOPT_CUSTOMREQUEST, "DELETE");
        $resp = curl_exec ( $CURL );
        if (!$resp) $resp = $this->_parse_curl_info($CURL);
        else $resp = json_decode($resp);
        
        curl_close($CURL);
        return $resp;
    }
    
    private function _set_common_curl_opts(&$CURL, $url) {
        curl_setopt ( $CURL, CURLOPT_URL, $url ); 
        curl_setopt ( $CURL, CURLOPT_RETURNTRANSFER, true ); //return the output as a variable 
        curl_setopt ( $CURL, CURLOPT_FAILONERROR, true ); //fail if error encountered 
        curl_setopt ( $CURL, CURLOPT_FOLLOWLOCATION, true ); //allow redirects 
        curl_setopt ( $CURL, CURLOPT_TIMEOUT, 10 ); //time out length
    }
    
    private function _data_to_postfields($data) {
        $postfields = "";
        if (!empty($data)) {
            foreach($data as $key=>$value)
                $postfields .= "$key=".urlencode($value)."&";
            $postfields = preg_replace('/\&$|\?$/', '', $postfields);            
        }
        return $postfields;
    }
    
    private function _parse_curl_info($CURL) {
        $resp = array();
        $curlinfo = curl_getinfo($CURL);
        $resp['header_size'] = $curlinfo['header_size'];
        $resp['status_code'] = $curlinfo['http_code'];
        $resp['errno'] = curl_errno($CURL);
        $resp['text'] = curl_error ( $CURL );
        $resp['content_type'] = $curlinfo['content_type'];
        $resp['request_size'] = $curlinfo['request_size'];
        $resp['url'] = $curlinfo['url'];

        return $resp;
    }
}
?>