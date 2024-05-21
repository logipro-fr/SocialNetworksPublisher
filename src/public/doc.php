<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Output the contents of the YAML file
echo file_get_contents(__DIR__ . "/doc.yaml");
?>
