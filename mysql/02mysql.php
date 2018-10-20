<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/22 0022
 * Time: 下午 12:26
 */

//数据库增删改
$connection = mysqli_connect('127.0.0.1', 'root', '', 'demo');

if (!$connection) {
    exit('<h2>数据库链接失败</h2>');
}

//数据查询前传入链接对象和编码，数据中有中文会出现乱码状况：
mysqli_set_charset($connection, 'utf8');

$query = mysqli_query($connection, 'delete from user where id = "5"');
if (!$query) {
    exit('<h2>数据库查询失败</h2>');
}

//如何拿到受影响的行，传入的是链接对象
$row = mysqli_affected_rows($connection);
var_dump($row);

//炸桥
mysqli_close($connection);