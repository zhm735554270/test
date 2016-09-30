<?php
    include './class/config.php';
    //自动加载
    function __autoload($className) {
        include './class/'.$className.'.class.php';
    }

    $users = new Model('users');
    $page = new Page($users->count(), 5);

    $amount = $page->amount;

    $arr = $users->limit($page->limit)->select();

    // var_dump($arr);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>用户列表</title>
</head>
<body>
<?php echo date('Y-m-d H:i:s'); ?>
<div id="box">
    <table width="600" border="1">
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>性别</th>
            <th>添加时间</th>
        </tr>
    <?php foreach ($arr as $v): ?>
        <tr>
            <td><?=$v['id']?></td>
            <td><?=$v['username']?></td>
            <td><?=$v['sex']?></td>
            <td><?=$v['addtime']?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</div>

    <button onclick="getData(false, this)">上一页</button>
    <button onclick="getData(true, this)">下一页</button>
</body>
<script src="../ajax.js"></script>
<script>
    var box = document.getElementById('box');

    //先声明一个空数组，用于做缓存
    var arr = [];

    //获取当前页
    var p = <?php echo isset($_GET['p']) ? $_GET['p'] : 1; ?>;

    function getData(flag, obj) {
        if (flag) {
            p++;
        } else {
            p--;
        }

        if (p < 1) {
            p = 1;
        } else if (p > <?php echo $amount?>) {
            p = <?php echo $amount?>;
        } else {
            if (arr[p]) {
                box.innerHTML = arr[p];
            } else {
                obj.disabled = true;
                get('ajax.php?p='+p, function(res){
                    //方式一：在前台遍历对象
                    //将字符串转换为对象
                    // var obj = JSON.parse(res);
                    // console.dir(obj);
                    // for (var k in obj) {

                    // }

                    //方式二：在后台遍历数组，拼接字符串
                    box.innerHTML = res;

                    arr[p] = res;

                    //开启按钮
                    obj.disabled = false;
                })
            }
        }
    }
</script>
</html>
