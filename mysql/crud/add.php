<?php header('Content-type: text/html; charset=utf-8');

function add_user(){
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

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];

    if (empty($_FILES['avatar'])) {
        $GLOBALS['msg'] = '上传图片';
        return;
    }

    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $target = '../uploads/avatar-' . uniqid() . '.' . $ext;

    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
        $GLOBALS['msg'] = '上传图片失败';
        return;
    }

    $avatar = substr($target, 2);

    //链接数据库
    $conn = mysqli_connect('localhost', 'root', '', 'demo');
    if (!$conn) {
        $GLOBALS['msg'] = '链接数据库失败';
        return;
    }

    $query = mysqli_query($conn, "insert into user values (null, '{$name}', '{$gender}', '{$birthday}', '{$avatar}');");

    if (!$query) {
        $GLOBALS['msg'] = '查询过程失败';
        return;
    }

    $affected_row = mysqli_affected_rows($conn);
    if ($affected_row !== 1) {
        $GLOBALS['msg'] = '添加数据失败';
        return;
    }

    mysqli_free_result($query);
    mysqli_close($conn);

    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    add_user();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XXX管理系统</title>
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
    <h1 class="heading">添加用户</h1>
    <?php if (isset($msg)): ?>
    <div class="alert alert-warning">
      <?php echo $msg; ?>
    </div>
    <?php endif ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form-group">
        <label for="avatar">头像</label>
        <input type="file" class="form-control" id="avatar" name="avatar">
      </div>
      <div class="form-group">
        <label for="name">姓名</label>
        <input type="text" class="form-control" id="name" name="name">
      </div>
      <div class="form-group">
        <label for="gender">性别</label>
        <select class="form-control" id="gender" name="gender">
          <option value="-1">请选择性别</option>
          <option value="1">男</option>
          <option value="0">女</option>
        </select>
      </div>
      <div class="form-group">
        <label for="birthday">生日</label>
        <input type="date" class="form-control" id="birthday" name="birthday">
      </div>
      <button class="btn btn-primary">保存</button>
    </form>
  </main>
</body>
</html>
