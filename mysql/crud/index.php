<?php

$connection = mysqli_connect('localhost','root', '', 'demo');

if (!$connection) {
    exit('<h2>数据库连接失败</h2>');
}

$query = mysqli_query($connection, 'select * from user;');
if (!$query) {
    exit('<h2>数据库查询失败</h2>');
}

require ('config.php');

//日期格式转为年龄
function birthday($birth){
    list($y,$m,$d) = explode('-', $birth);
    $y_diff = date('Y') - $y;
    $m_diff = date('m') - $m;
    $d_diff = date('d') - $d;

    if ($m_diff < 0 || $d_diff < 0) {
        $y_diff--;
    }

    return $y_diff;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>XXX管理系统</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        table th {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">XXX管理系统</a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">用户管理</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">商品管理</a>
            </li>
        </ul>
    </nav>
    <main class="container">
        <h1 class="heading">用户管理 <a class="btn btn-link btn-sm" href="add.php">添加</a></h1>
        <table class="table table-hover">
            <thead>
            <tr>
                <th><label for="all_sel"><input id="all_sel" type="checkbox"> 全选</label></th>
                <th>#</th>
                <th>头像</th>
                <th>姓名</th>
                <th>性别</th>
                <th>年龄</th>
                <th class="text-center" width="140">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($item = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><input type="checkbox" ></td>
                <th scope="row"><?php echo $item['id']; ?></th>
                <td><img src="<?php echo BASE_URL.$item['avatar']; ?>" alt="<?php echo $item['name'] ?>"></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['gender']==0?'♀':'♂'; ?></td>
                <td><?php echo birthday($item['birthday']); ?></td>
                <td class="text-center">
                    <a class="btn btn-info btn-sm" href="edit.php?id=<?php echo $item['id']; ?>">编辑</a>
                    <a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $item['id']; ?>">删除</a>
                </td>
            </tr>

            <?php endwhile ?>
            </tbody>
        </table>
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">···</a></li>
            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
        </ul>
    </main>

</body>
</html>