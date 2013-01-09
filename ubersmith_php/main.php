#!/usr/bin/env php
<?php
require_once('src/USClient.php');

$usc = new USClient();

// $usc->ScriptFile = "/Users/sundar/Projects/sundar/ubersmith/ubersmith_php/data/input.v1.example";
// $usc->run_script();

$usc->ScriptFile = "/Users/sundar/Projects/sundar/ubersmith/ubersmith_php/data/input.v2.example";
$resp = $usc->run_script();
// $resp = $usc->auth('test', 'testpw');
// var_dump($resp);

?>

