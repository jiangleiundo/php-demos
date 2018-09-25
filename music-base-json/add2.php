<?php header('Content-type: text/html; charset=utf-8');
require ('./config.php');

function add_music(){
    //准备一个存放数据的数组
    $data = array();
    $data['id']=uniqid();

    if(empty($_POST['title'])){
        $GLOBALS['msg'] = '请输入音乐标题';
        return;
    }
    if(empty($_POST['artist'])){
        $GLOBALS['msg'] = '请输入音乐人';
        return;
    }

    $data['title'] = $_POST['title'];
    $data['artist'] = $_POST['artist'];

    if(!isset($_FILES['images'])){
        $GLOBALS['msg'] = '请正常使用表单';
        return;
    }

    $images = $_FILES['images'];
    $images['images'] = array();

    //验证图片上传
    for ($i = 0; $i < count($images['name']); $i++) {
        if ($images['error'][$i] !== UPLOAD_ERR_OK) {
            $GLOBALS['msg'] = '上传图片失败';
            return; //如果其中一个出错也没关系此处用continue;
        }

        if (strpos($images['type'][$i], 'image/') !== 0) {
            $GLOBALS['msg'] = '图片格式不正确';
            return;
        }

        if ($images['size'][$i] > 1 * 1024 * 1024) {
            $GLOBALS['msg'] = '图片文件过大';
            return;
        }
    }

    //验证音频上传
    if(!isset($_FILES['source'])){
        $GLOBALS['msg'] = '请正常使用表单';
        return;
    }

    $source = $_FILES['source'];

    if ($source['error'] !== UPLOAD_ERR_OK) {
        $GLOBALS['msg'] = '上传音乐文件失败001';
        return;
    }

    $allowed_types = array('audio/mp3', 'audio/wma');
    if (!in_array($source['type'], $allowed_types)) {
        $GLOBALS['msg'] = '这是不支持的音乐格式';
        return;
    }

    if ($source['size'] > 10 * 1024 * 1024) {
        $GLOBALS['msg'] = '音乐文件过大';
        return;
    }
    if ($source['size'] < 1 * 1024 * 1024) {
        $GLOBALS['msg'] = '音乐文件过小';
        return;
    }

    //文件上传图片到数据库
    for ($i = 0; $i < count($images['name']); $i++) {
        $id = uniqid();
        $name = $images['name'][$i];
        $cur_name = '/uploads/'.$id.'-'.$name; //此处绝对路径

        $data['images'][] = $cur_name;

        //如果是文件名含有中文转码
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $name) > 0) {
            $name = iconv("UTF-8", "gbk", $name);
        }

        $target = './uploads/'.$id.'-'.$name; //此处相对路径
        if(!move_uploaded_file($images['tmp_name'][$i], $target)){
            $GLOBALS['msg'] = '上传图片失败002';
            return;
        }
    }

    //上传音乐文件
    $name = $source['name'];
    $cur_id = uniqid();

    //在未转码之前先拿到文件名，防止中文转码后拿到乱码
    $data['source'] = '/uploads/'.$cur_id.'-'.$name;

    //如果是文件名含有中文转码
    if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $name) > 0) {
        $name = iconv("UTF-8", "gbk", $name);
    }

    $target = './uploads/'.$cur_id.'-'.$name;
    if(!move_uploaded_file($source['tmp_name'], $target)){
        $GLOBALS['msg'] = '上传音乐文件失败002';
        return;
    }

    $origin = json_decode(file_get_contents('storage.json'), true);
    $origin[] = $data;
    $json_str = json_encode($origin);
    file_put_contents('storage.json', $json_str);

    //数据提交完成后跳到list页面
    header('Location: list.php');
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    add_music();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加新音乐</title>
    <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
<div class="container py-5">
    <h1 class="display-4">添加新音乐</h1>
    <hr>
    <?php if (isset($msg)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $msg ?>
        </div>
    <?php endif ?>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="form-group">
            <label for="title">标题</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        <div class="form-group">
            <label for="artist">歌手</label>
            <input type="text" class="form-control" id="artist" name="artist">
        </div>
        <div class="form-group">
            <label for="images">海报</label>
<!--            上传多个文件name="images[]"，PHP中要加[]-->
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
        </div>
        <div class="form-group">
            <label for="source">音乐</label>
            <!--accept可以设置两种值，MIME Type或者文件扩展名，一个或多个eg: accept='.txt,.lrc,audio/*' 即可以上传文本，歌词和audio文件-->
            <input type="file" class="form-control" id="source" name="source" accept="audio/*">
        </div>
        <button class="btn btn-primary btn-block">保存</button>
    </form>
</div>
</body>
</html>