<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/24 0024
 * Time: 下午 6:20
 */

/**
 * 设置utf8编码
 * @param $connection [连接数据库]
 */
function set_utf8($connection){
    mysqli_set_charset($connection, 'utf8');
}

/**
 * 释放查询结果集并关闭数据库连接
 * @param $query [查询结果集]
 * @param $conn [数据库连接]
 */
function free_query_close_con($query, $conn){
    mysqli_free_result($query);
    mysqli_close($conn);
}