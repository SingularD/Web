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
        try {
            echo "<table style='border: solid 1px black;'>";
            echo "<tr><th>Id</th><th>姓名</th><th>语文</th><th>数学</th><th>英语</th></tr>";
            $stmt = $conn->prepare("SELECT id, stdname, chinese,math,english FROM score");
            $stmt->execute();
            $table = "";
            // 设置结果集为关联数组$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $res = $stmt->fetchAll();
            foreach ($res as $x => $key) {
                $table = $table . "<tr><th>{$key['id']}</th><th>{$key['stdname']}</th><th>{$key['chinese']}</th><th>{$key['math']}</th><th>{$key['english']}</th></tr>";
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
        echo "<tr><th>Id</th><th>姓名</th><th>语文</th><th>数学</th><th>英语</th></tr>";
        $stmt = $conn->prepare("SELECT id FROM usersmassage WHERE id={$_SESSION['uid']}");
        $stmt->execute();

        $res = $stmt->fetchAll();
        $id=(int)$res[0]['id'];
        $stmt = $conn->prepare("SELECT id, stdname, chinese,math,english FROM score WHERE id={$id}");
        $stmt->execute();
        // 设置结果集为关联数组$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        $table = "";
        foreach ($res as $x => $key) {
            $table = $table . "<tr><th>{$key['id']}</th><th>{$key['stdname']}</th><th>{$key['chinese']}</th><th>{$key['math']}</th><th>{$key['english']}</th></tr>";
        }
        echo $table;
        echo "</table>";
        echo "<a href='login.php?act=logout'>注销</a>";
    }
}else{
    echo "请<a href='login.php'>登陆</a>";
}
?>

