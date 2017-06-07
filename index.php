<?php
header("Content-type: text/html; charset=utf-8");
session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "mysystem";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

if(isset($_SESSION['level'])) {
    $level=(int)$_SESSION['level'];
    if ($level==10) {
        if(isset($_REQUEST['act'])){
            if($_REQUEST['act']=="edit"){
                if(isset($_REQUEST['submit'])){
                    foreach ($_REQUEST['submit'] as $x){
                        try{
                            var_dump($x);
                            $chinese=$x['chinese'];
                            $english=$x['english'];
                            $math=$x['math'];
                            $id=$x['id'];
                            $sql="UPDATE `score` SET `chinese`='{$chinese}', `math`='{$math}', `english`='{$english}' WHERE (`id`='{$id}')";
                            //echo $sql;
                            $res=$conn->exec($sql);
                            echo "\n----------------------->\n".$res;
                        }catch (Exception $e){
                            echo json_encode("Error");
                            exit();
                        }
                    }
                    $stmt = $conn->prepare("SELECT id, stdname, chinese,math,english FROM score");
                    $stmt->execute();
                    //数据预处理，防止未知异常数据注入数据库
                    $table = "";
                    $res = $stmt->fetchAll();
                    foreach ($res as $x => $key) {
                        $average = "{$key['chinese']} + {$key['math']} +{$key['english']} ";
                        $table = $table . "<tr><th class='row'>{$key['id']}</th class='row'><th>{$key['stdname']}</th><th class='row'>{$key['chinese']}</th><th class='row'>{$key['math']}</th><th class='row'>{$key['english']}</th>
                        <th class='row'>{$all}</th><th class='row'>{$average}</th></tr>";
                    }
                    echo $table;
                    exit();
                }
                exit();
            }
        }
        try {
            echo "<table style='border: solid 1px black;'>";
            echo "<tr><th>Id</th><th>姓名</th><th>语文</th><th>数学</th><th>英语</th><th>总分</th><th>平均分</th><th>评价</th></tr>";
            //数据预处理，防止未知异常数据注入数据库
            $stmt = $conn->prepare("SELECT id, stdname, chinese,math,english FROM score");
            $stmt->execute();
            $table = "";
            $res = $stmt->fetchAll();
            foreach ($res as $x => $key) {
                $all = $key['chinese'] + $key['math'] +$key['english'];
                $average = $all/3;

                if($average>= "85"){
                    $mark = "A";
                }elseif ($average>= "70"){
                    $mark = "B";
                }elseif ($average>= "60"){
                    $mark = "C";
                }else{
                    $mark = "D";
                }
                $table = $table . "<tr><th class='row'>{$key['id']}</th class='row'><th>{$key['stdname']}</th><th class='row'>{$key['chinese']}</th><th class='row'>{$key['math']}</th><th class='row'>{$key['english']}</th>
                <th class='row'>{$all}</th><th class='row'>{$average}</th><th class='row'>{$mark}</th></tr>";
            }
            echo $table;
            

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        echo "</table>";
        echo "<a href='login.php?act=logout'>注销</a>";
    }else{
        echo "<table style='border: solid 1px black;'>";
        echo "<tr><th>Id</th><th>姓名</th><th>语文</th><th>数学</th><th>英语</th><th>总分</th><th>平均分</th><th>评价</th></tr>";
        $stmt = $conn->prepare("SELECT id FROM usersmassage WHERE id={$_SESSION['uid']}");
        $stmt->execute();

        $res = $stmt->fetchAll();
        $id=(int)$res[0]['id'];
        $stmt = $conn->prepare("SELECT id, stdname, chinese,math,english FROM score WHERE id={$id}");
        $stmt->execute();

        $res = $stmt->fetchAll();
        $table = "";
        foreach ($res as $x => $key) {
            $all = $key['chinese'] + $key['math'] +$key['english'];
            $average = $all/3;

            if($average>= "85"){
                $mark = "A";
            }elseif ($average>= "70"){
                $mark = "B";
            }elseif ($average>= "60"){
                $mark = "C";
            }else{
                $mark = "D";
            }
            $table = $table . "<tr><th class='row'>{$key['id']}</th class='row'><th>{$key['stdname']}</th><th class='row'>{$key['chinese']}</th><th class='row'>{$key['math']}</th><th class='row'>{$key['english']}</th>
                <th class='row'>{$all}</th><th class='row'>{$average}</th><th class='row'>{$mark}</th></tr>";
        }
        echo $table;
        echo "</table>";
        echo "<a href='login.php?act=logout'>注销</a>";
    }
}else{
    echo "请<a href='login.php'>登陆</a>";
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script>
        //初始化堆栈
        var stack=new Array();
        //加载主docment
        $(document).ready(function () {
            $(".row").click(function () {
                var rowValue=$(this).html();
                var child=$(this).children("input");

                if(child.length<1) {
                    $(this).html("<input onblur='blu(this)'  class='inputer' value='" + rowValue + "' type='text'>");
                    $(this).children("input")[0].focus();
                    console.log() ;
                }
            });

        });
        function blu(obj) {
            var father=obj.parentNode;
            father.innerHTML=obj.value;
            //console.log();
            var id=father.parentNode.childNodes[0].innerHTML;
            var chinese=father.parentNode.childNodes[2].innerHTML;
            var math=father.parentNode.childNodes[3].innerHTML;
            var english=father.parentNode.childNodes[4].innerHTML;
            var data=new Object();
            data.id=id;
            data.chinese=chinese;
            data.math=math;
            data.english=english;
            if (stack.length==0){
                //压栈
                stack.push(data);
                console.log("is zero");
            }else {
                /**
                 * 数据逻辑：
                 *      设置循环标记flag=0，循环堆栈，栈内id存在则重新给数据赋值
                 *      若id不存在则将数据推入堆栈中
                 * @type {stack}
                 */
                var flag=0;
                for (x in stack) {
                    if (String(stack[x].id) == String(data.id)) {
                        stack[x] = data;
                        flag=1;
                    }
                }
                //压栈
                if (flag==0)stack.push(data);
            }
            console.log(stack);
        }
        /**
         * ajax将堆栈数据推送给后台
         */
        function submitData() {
            $.ajax({
                url:"index.php?act=edit",
                data:{"submit":stack},
                dataType:"html",
                type:"post",
                success:function (data) {
                    console.log(data);
                    stack=new Array();
                }
            });
        }
    </script>
    <title>Document</title>
</head>
<body>
<button onclick="submitData()" >提交修改</button>
</body>
</html>



