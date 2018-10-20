<?php header('Content-type: text/html; charset=utf-8');

if (empty($_GET['id'])) {
    exit('<h2>必须指定传入参数</h2>');
}

$id = $_GET['id'];

$conn = mysqli_connect('localhost', 'root', '', 'demo');
if (!$conn) {
    exit('<h2>数据库链接失败</h2>');
}

//如果$id = '7,8,9';类似这样的“=”就要换成in实现批量删除
//$query = mysqli_query($conn, 'delete from user where id = (' . $id . ');');
$query = mysqli_query($conn, 'delete from user where id in (' . $id . ');');

if (!$query) {
    exit('<h2>查询数据失败</h2>');
}

$affected_row = mysqli_affected_rows($conn);

if ($affected_row <= 0) {
    exit('<h2>删除失败</h2>');
}

mysqli_free_result($query);
mysqli_close($conn);

header('Location: index.php');