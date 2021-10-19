<?php
ini_set('display_errors', 1);
$data = file_get_contents('php://input');
$file = file_get_contents('./log.json');


if (!empty($file)) {
    $file = json_decode($file,true);
    if (count($file) > 4200) {
        $file = array_splice($file, 30);
    }
    $data = json_decode($data,true);

    $file = array_merge($file, $data);

    $file = json_encode($file);


    file_put_contents('./log.json', $file);

}else {

    file_put_contents('./log.json', $data);
}
?>
