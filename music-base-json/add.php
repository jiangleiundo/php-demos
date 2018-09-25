<?php header('Content-type: text/html; charset=utf-8');
require ('./config.php');

function add_music(){
    if(empty($_POST['title'])){
        $GLOBALS['msg'] = '请输入音乐标题';
        return;
    }
    if(empty($_POST['artist'])){
        $GLOBALS['msg'] = '请输入音乐人';
        return;
    }

    if(!isset($_FILES['images'])){
        $GLOBALS['msg'] = '请选择正确提交图片';
        return;
    }
    if(!isset($_FILES['source'])){
        $GLOBALS['msg'] = '请选择正确提交文件';
        return;
    }

    $img = $_FILES['images'];
    $source = $_FILES['source'];

    //开始验证要上传文件
    validate_file($img, TXT_IMG, TYPE_IMG);

    //如果图片上传不成功，下面的不再调用
    if($GLOBALS['flag_validate']){
        validate_file($source, TXT_MUSIC, TYPE_MUSIC);
    }

    //验证成功上传
    upload_files($img, TXT_IMG, TYPE_IMG);
    if ($GLOBALS['flag_upload']) {
        upload_files($source, TXT_MUSIC, TYPE_MUSIC);
    }

    //添加数据到数据库
    $origin = json_decode(file_get_contents('storage.json'), true);
    $origin[] = array(
        'id' => uniqid()
        ,'title' => $_POST['title']
        ,'artist' => $_POST['artist']
        ,'images' => array($GLOBALS['uploaded_img'])
        ,'source' => $GLOBALS['uploaded_mp3']
    );

    $json_str = json_encode($origin);
    file_put_contents('storage.json', $json_str);

    //数据提交完成后跳到list页面
//    header('Location: list.php');
}


/**
 * 验证文件是否正确
 * @param $source [文件域的内容]
 * @param $txt    [‘音乐’或者‘图片’]
 * @param $type   [0代表上传音频，1代表上传图片]
 */
function validate_file($source, $txt, $type){

    if($source['error'] !== UPLOAD_ERR_OK){
        $GLOBALS['msg'] = '请选择'.$txt.'文件';
        return;
    }

    $allowed_types = $type? array('image/jpeg', 'image/png', 'image/gif'): array('audio/mp3', 'audio/wma');

    if(!in_array($source['type'], $allowed_types)){
        $GLOBALS['msg'] = '这是不支持的'.$txt.'格式';
        return;
    }

    if ($type) {
        if ($source['size'] > 1 * 1024 * 1024) {
            $GLOBALS['msg'] = $txt . '文件过大';
            return;
        }
    } else {
        if ($source['size'] > 10 * 1024 * 1024) {
            $GLOBALS['msg'] = $txt . '文件过大';
            return;
        }
        if ($source['size'] < 1 * 1024 * 1024) {
            $GLOBALS['msg'] = $txt . '文件过小';
            return;
        }
    }

    $GLOBALS['flag_validate'] = true;
}

/**
 * 上传文件
 * @param $source [文件域的内容]
 * @param $txt    [‘音乐’或者‘图片’]
 * @param $type   [0代表上传音频，1代表上传图片]
 */
function upload_files($source, $txt, $type){
    $name = $source['name'];
    $cur_id = uniqid();

    //在未转码之前先拿到文件名，防止中文转码后拿到乱码
    $cur_name = './uploads/'.$cur_id.'-'.$name;
    if ($type) {
        $GLOBALS['uploaded_img'] = $cur_name;
    } else {
        $GLOBALS['uploaded_mp3'] = $cur_name;
    }

    //如果是文件名含有中文转码
    if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $name) > 0) {
        $name = iconv("UTF-8", "gbk", $name);
    }

    $target = './uploads/'.$cur_id.'-'.$name;
    if(!move_uploaded_file($source['tmp_name'], $target)){
        $GLOBALS['msg'] = '上传'.$txt.'失败';
        return;
    }

    $GLOBALS['flag_upload'] = true;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $GLOBALS['flag_validate'] = false; //是否验证成功
    $GLOBALS['flag_upload'] = false;//是否上传成功
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
