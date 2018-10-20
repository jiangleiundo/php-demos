<?php

$u = $_POST['username'];
$p = $_POST['password'];

if (empty($u) || empty($p)) {
    exit('请输入用户名和密码');
}

if ($u == 'admin' && $p == '123456') {
    exit('登陆成功');
}

exit('登录失败!');

