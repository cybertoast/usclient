<?php
require_once('libs/Requests.php');

$r = new Requests();
$resp = $r->POST("http://localhost");
print_r($resp);
?>