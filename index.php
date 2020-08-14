<?php 
require_once('jsonConvert.php');

$flat_json          = file_get_contents(__DIR__ . '/flat.json');
$flatJsonArr        = json_decode($flat_json, true);
$hierarchicalJson   = Convert::convertJson($flatJsonArr, 'id', 'parent', 'children');
print_r(json_encode($hierarchicalJson));
