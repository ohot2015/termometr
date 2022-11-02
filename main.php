<?php

try {
    $db = new PDO('mysql:host=kotel_db;dbname=kotel', 'root', '123qweasd');
    $data = $db->query("SELECT * FROM temeperature")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($data as $k => $v){
        echo 'Category name: '.$v['name'].'<br>';
    }

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage();
    die();
}
?>