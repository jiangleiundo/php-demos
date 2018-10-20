<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 下午 4:02
 */
//if (isset($_GET['action']) && $_GET['action'] === 'close_ad') {
//    echo 'test';
//    setcookie('hide_ad', '1');
//    echo 'test2';
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>test cookie</title>
    <style>
        .ad {
            height: 200px;
            width: 100px;
            background-color: #ff0;
            position: fixed;
            top: 50%;
            left: 0;
            margin-top: -100px;
        }
        .ad a {
            display: block;
            position: absolute;
            top: 0;
            right: 0;
        }
    </style>
</head>
<body>

<?php if (empty($_COOKIE['hide_ad']) || $_COOKIE['hide_ad'] !== '1'): ?>
    <div class="ad">
<!--         <a href="close.php">不再显示</a> -->
        <a href="close.php">不再显示</a>
    </div>
<?php endif ?>

</body>
</html>
