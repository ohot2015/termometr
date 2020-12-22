<?php
ini_set('display_errors', 1);
$data = file_get_contents('php://input');
$file = file_get_contents('./log.json');
$data = mb_substr($file, 0, -1);
$file = mb_substr($file, 0, 1);
$data .= $file . ']';
file_put_contents('./log.json', $data);
?>