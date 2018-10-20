<?php header('Content-type: application/json');
/*返回的响应就是一个json内容
 * 对于返回数据的地址一般称为接口（形式上是web信息）
 */
$data = array(
    array(
        'id' => 1,
        'name' => 'tom',
        'age' => 18
    ),
    array(
        'id' => 2,
        'name' => 'jerry',
        'age' => 19
    ),
    array(
        'id' => 3,
        'name' => 'gaofei',
        'age' => 20
    ),
);

if (empty($_GET['id'])) {
    //没有id,返回全部

    $json = json_encode($data);//[{'id':1,'name':'tom'},{...}]
    echo $json;
} else {
    //有id，只取一条
    foreach ($data as $item) {
        if ($item['id'] != $_GET['id']) continue;

        $json = json_encode($item);
        echo $json;
    }
}