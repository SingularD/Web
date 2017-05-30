<?php
session_start();
if (isset($_SESSION['level'])){
    header("location:./index.php");
}
$servername = "localhost";
$username = "root";
$password = "root";
if(isset($_POST["username"]) && isset($_POST["password"])){
    try {
        $conn = new PDO("mysql:host=$servername;dbname=mysystem", $username, $password);
        $sql="SELECT `level`,`id` FROM usersmassage WHERE username='{$_POST["username"]}' and password='{$_POST["username"]}'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res=$stmt->fetchAll();
        if(!empty($res)){
            echo "Login success!";
            session_start();
            $_SESSION['uid']=$res[0]['id'];
            $_SESSION['level']=$res[0]['level'];
            header("location:./index.php");
        }else{
            echo "Login Error!";
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
if (isset($_REQUEST['act'])){
    if ($_REQUEST['act']=="logout"){
        session_start();
        session_destroy();
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="login.php" method="post">
    用户：<input type="text" name="username"><br>
    密码：<input type="password" name="password"><br>
    <input type="submit">
</form>
</body>
</html>
