<?php header('Content-type: text/html; charset=utf-8');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 下午 5:13
 */

if (empty($_COOKIE['randNum']) || empty($_GET['num'])) {
    $num_rand = rand(1, 100);
    setcookie('randNum', $num_rand);

    setcookie('count');
    setcookie('num_his');
} else {
    $count = empty($_COOKIE['count'])? 0: (int)$_COOKIE['count'];

    if($count < 5){
        $res = (int)$_GET['num'] - $_COOKIE['randNum'];
        $num_str = $_COOKIE['num_his'];


        if (empty($num_str)) {
            $num_str = (int)$_GET['num'];

        }else{
            $num_str = $num_str . ',' . (int)$_GET['num'];
        }
        setcookie('num_his', $num_str);

        if ($res > 0) {
            $GLOBALS['msg'] = '偏大了';
        } else if ($res < 0) {
            $GLOBALS['msg'] = '偏小了';
        } else {
            $GLOBALS['msg'] = '你猜对了';
            setcookie('randNum');
            setcookie('num_his');
        }

        setcookie('count', ++$count);
    }else{
        $res = '';
        $GLOBALS['msg'] = '挑战失败';
        setcookie('count');
        setcookie('randNum');
        setcookie('num_his');
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>猜数字</title>
    <style>
        body {
            background-color: #2b3b49;
            color: #fff;
            text-align: center;
            font-size: 1.5em;
        }

        input {
            padding: 5px 20px;
            height: 50px;
            background-color: #3b4b59;
            border: 1px solid #c0c0c0;
            box-sizing: border-box;
            color: #fff;
            font-size: 20px;
        }

        button {
            padding: 5px 20px;
            height: 50px;
            font-size: 16px;
            cursor: pointer;
        }

        .container {
            width: 500px;
            min-height: 300px;
            margin: 0 auto;
            padding: 30px 40px;
            border: 1px solid #00AA9A;
        }
        .reset {
            display: inline-block;
            height: 38px;
            line-height: 38px;
            text-align: center;
            padding: 5px 20px;
            color: #fff;
            background-color: #F86931;
            font-size: 16px;
            text-decoration: none;
        }
        p {
            height: 38px;
            line-height: 38px;
            padding-left: 16px;
            color: #ddd;
            font-size: 14px;
            text-align: left;
        }

        p.info {
            background-color: #bd4147;
        }

        p.right {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>猜数字游戏</h2>
    <p>Hi，我已经准备了一个0~100的数字，你需要在仅有的10机会之内猜对它。</p>

    <form action="index.php" method="get">
        <input type="number" min="0" max="100" name="num" value="<?php if (!empty($_GET['num'])) echo $_GET['num']; ?>">
        <button type="submit">试一试</button>
        <a class="reset" href="index.php">重置</a>
    </form>
    <p><?php if (!empty($num_str)) echo $num_str; ?></p>
    <?php if (isset($msg)): ?>
        <p class="info<?php echo ($res === 0) ? ' right' : ''; ?>"><?php echo $msg; ?></p>
    <?php endif ?>
</div>
</body>
</html>