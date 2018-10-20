<?php
require('common.php');
require('config.php');

if (empty($_GET['id'])) {
    exit('<h2>必须传入参数</h2>');
}

$id = $_GET['id'];

$conn = mysqli_connect('localhost', 'root', '', 'demo');

if(!$conn) {
    exit('<h2>数据库连接失败</h2>');
}

set_utf8($conn);

//因为 ID 是唯一的 那么找到第一个满足条件的就不用再继续了 limit 1
$query = mysqli_query($conn, "select * from user where id = {$id} limit 1;");
if(!$query) {
    exit('<h2>数据库查询失败</h2>');
}

$data = mysqli_fetch_assoc($query);

if(!$data) {
    exit('<h2>找不到需要编辑的数据</h2>');
}

function mod_user($data, $conn, $id){
    if (empty($_POST['name'])) {
        $GLOBALS['msg'] = '请输入姓名';
        return;
    }

    if (!isset($_POST['gender']) && !$_POST['gender'] !== '-1') {
        $GLOBALS['msg'] = '请选择性别';
        return;
    }

    if (empty($_POST['birthday'])) {
        $GLOBALS['msg'] = '请选择出生日期';
        return;
    }

    $name = isChange($data, 'name');
    $gender = isChange($data, 'gender');
    $birthday = isChange($data, 'birthday');
    $avatar = $data['avatar'];

    if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $target = '../uploads/avatar-' . uniqid() . '.' . $ext;

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
            $GLOBALS['msg'] = '上传图片失败';
            return;
        }

        $avatar = substr($target, 2);
    }

    $update_query = "update user set name = '{$name}', gender = '{$gender}', birthday = '{$birthday}', avatar = '{$avatar}' where id = {$id};";
    $query2 = mysqli_query($conn, $update_query);

    if (!$query2) {
        $GLOBALS['msg'] = '查询过程失败';
        return;
    }

    $affected_row = mysqli_affected_rows($conn);
    if ($affected_row !== 1) {
        $GLOBALS['msg'] = '修改数据失败';
        return;
    }

    mysqli_free_result($query2);
    mysqli_close($conn);

    header('Location: index.php');
}

//判断是否修改
function isChange($data, $key){
    return ($data[$key] == $_POST[$key])? $data[$key]: $_POST[$key];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    mod_user($data, $conn, $id);
}

//释放查询结果集
mysqli_free_result($query);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>edit</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
        <h1 class="heading">编辑“<?php echo $data['name']; ?>”</h1>
        <?php if (isset($msg)): ?>
        <div class="alert alert-warning">
            <?php echo $msg; ?>
        </div>
        <?php endif ?>
        <form action="<?php echo $_SERVER['PHP_SELF']."?id=$id"; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
            <img src="<?php echo BASE_URL.$data['avatar']; ?>" height="100" width="100" alt="头像图片">
            <div class="form-group">
                <label for="avatar">头像</label>
                <input type="file" class="form-control" id="avatar" name="avatar">
            </div>
            <div class="form-group">
                <label for="name">姓名</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>">
            </div>
            <div class="form-group">
                <label for="gender">性别</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="-1">请选择性别</option>
                    <option value="1"<?php echo $data['gender'] === '1' ? ' selected': ''; ?>>男</option>
                    <option value="0"<?php echo $data['gender'] === '0' ? ' selected': ''; ?>>女</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthday">生日</label>
                <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $data['birthday']; ?>">
            </div>
            <button class="btn btn-primary">保存</button>
        </form>
    </main>
</body>
</html>