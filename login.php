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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <link rel="stylesheet" href="bootstrap-3.3.7/css/bootstrap.min.css" type="text/css">
    <link href="./css/style.css" rel="stylesheet" type="text/css">
    <script src="jquery-3.2.1.min.js"></script>
    <script src="bootstrap-3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="home"><a href=""> login</a></div>
        <div class="form">
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="exampleInputText">Username</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Username"
                           name="username">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"
                           name="password">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>

            </form>
        </div>
        <div class="welcome">WELCOME TO MY WEB</div>
        <div class="welcome-word">It's LiSongWei's Web page</div>
    </div>
    <div class="bodyer"></div>
    <div class="footer"></div>
</div>
</body>
</html>
