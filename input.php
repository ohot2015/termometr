<?php
ini_set('display_errors', 1);
$data = file_get_contents('php://input');
$file = file_get_contents('./log.json');
$data = substr($file, 0, -1);
$file = substr($file, 0, 1);
$data .= $file . ']';
file_put_contents('./log.json', $data);
?>