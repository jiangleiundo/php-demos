<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21 0021
 * Time: 下午 5:22
 */

$id = $_GET['id'];

if (empty($id)) {
    exit('<h1>参数错误</h1>');
}

$origin = json_decode(file_get_contents('storage.json'), true);
foreach ($origin as $item) {
    if ($item['id'] !== $id) continue;

    $index = array_search($item, $origin); //获取下标
    array_splice($origin, $index, 1); //删除相应数据

    $json_str = json_encode($origin);
    file_put_contents('storage.json', $json_str);

    header('Location: list.php');
}

