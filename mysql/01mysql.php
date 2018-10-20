<?php header('Content-type: text/html; charset=utf-8');

//mysqli_connect()是一个额外扩展，需要在php.ini文件中配置打开。WAMPSERVER中不需要。

//如果需要在调用函数时候忽略错误或者警告在前面加@
//$connection = @mysqli_connect('127.0.0.1', 'root', '', 'demo');
$connection = mysqli_connect('127.0.0.1', 'root', '', 'demo');
//var_dump($connection);

if (!$connection) {
    exit('<h2>链接数据库失败</h2>');
}

//获取查询对象，这个查询对象可以一行一行拿数据
$query = mysqli_query($connection, 'select * from user;');
//var_dump($query);

//取数据，每次取一行
//赋值语句返回所赋的值

//查询失败
if (!$query) {
    exit('<h2>查询失败</h2>');
}

while ($row = mysqli_fetch_assoc($query)){
    var_dump($row);
}

//释放查询结果
mysqli_free_result($query);

//炸桥
mysqli_close($connection);