<?php
ini_set('display_errors', 1);
$data = file_get_contents('php://input');
$file = file_get_contents('./log.json');
if (!empty($file)) {
    $file = substr($file, 0, -1);
    $data = substr($data, 1);
    $file .=  ',' . $data;
    file_put_contents('./log.json', $file);
}
else {
    file_put_contents('./log.json', $data);
}

?>