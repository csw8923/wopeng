<?php
    header("Content-type:text/html;charset=UTF-8;");
    session_start();
    if(isset($_POST)&&!empty($_POST)){
        include("common/mysql.php");
        include("common/config.php");
        if(!isset($_POST["username"])||empty($_POST["username"])){
            die("<script>alert('用户名称错误。');location.href='add.php';</script>");
        }
        if(!isset($_POST["userpass"])||empty($_POST["userpass"])){
            die("<script>alert('用户密码错误。');location.href='add.php';</script>");
        }
        $db = new mysql($aConfig["db"]["host"],$aConfig["db"]["user"],$aConfig["db"]["pass"],$aConfig["db"]["name"],$aConfig["db"]["lang"]);
        $sql = "select * from `user` where `username`='".$_POST["username"]."'";
        $result = $db->query($sql);
        if($db->num_rows($result)>0){
            die("<script>alert('用户重复。');location.href='add.php';</script>");
        }
        $sql = "insert into `user` set `username`='".$_POST["username"]."',`userpass`='".md5($_POST["userpass"])."'";
        $result = $db->query($sql);
        if($db->insert_id()>0){
            die("<script>alert('添加用户成功。');location.href='index.php';</script>");
        }else{
            die("<script>alert('添加用户失败.');location.href='add.php';</script>");
        }
    }else{
?>
<html>
<head>
<link href="../style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
</head>
<body>
<div class="list-div">
<div class="list-div">
<form action="add.php" method="post">
<table>
  <tr>
    <td class="narrow-label">用户名称：</td>
    <td><input type="input" class="input" name="username" /></td>
  </tr>
  <tr>
    <td class="narrow-label">用户密码：</td>
    <td><input type="password" class="input" name="userpass" /></td>
  </tr>
  <tr>
    <td class="narrow-label"></td>
    <td><input type='submit' class="button" value="添加用户" /><input type="button" class="button" value="返回首页" onclick="location.href='index.php';" /></td>
  </tr>
</table>
</form>
</div>
</body>
</html>
<?php
    }