<?php header('Content-type: application/json');

$json = array('id'=>1, 'name'=>'tom', 'age'=>20, 'sex'=>'boy');
echo json_encode($json);