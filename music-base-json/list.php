<?php header('Content-type:text/html; charset=utf-8;');

require ('./config.php');

$data = file_get_contents('storage.json');
$arr = json_decode($data, true);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="bootstrap.css">
    <style type="text/css">

    </style>
    <title>音乐列表</title>
</head>
<body>
<div class="container">
    <h2 class="display-5 mt-4">音乐列表</h2>
    <hr>
    <div class="mb-3">
        <a href="add2.php" class="btn btn-secondary btn-sm">添加</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <td class="text-center">标题</td>
            <td class="text-center">歌手</td>
            <td class="text-center">海报</td>
            <td class="text-center">音乐</td>
            <td class="text-center">操作</td>
        </tr>
        </thead>
        <tbody class="text-center">
        <?php foreach ($arr as $item): ?>
            <tr>
                <td class="align-middle"><?php echo $item['title'] ?></td>
                <td class="align-middle"><?php echo $item['artist'] ?></td>
                <td class="align-middle"><img src="<?php echo BASE_PATH.$item['images'][0] ?>" alt="" height="100" width="100"></td>
                <td class="align-middle"><audio src="<?php echo BASE_PATH.$item['source'] ?>" controls></audio></td>
                <td class="align-middle"><a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $item['id'] ?>">删除</a></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

</body>
</html>
