<?php
    header("Content-type:text/html; charset=utf-8;");
    session_start();
    include("common/config.php");
    include("common/mysql.php");
    if(!isset($_POST["username"])||empty($_POST["username"])){
        die("<script>alert('用户名称为空。');location.href='index.php';</script>");
    }
    if(!isset($_POST["userpass"])||empty($_POST["userpass"])){
        die("<script>alert('用户密码为空');location.href='index.php';</script>");
    }
    $db = new mysql($aConfig["db"]["host"],$aConfig["db"]["user"],$aConfig["db"]["pass"],$aConfig["db"]["name"],$aConfig["db"]["lang"]);
    $sql = "select * from `user` where `username`='".$_POST["username"]."'";
    $result = $db->query($sql);
    if($db->num_rows($result)==0){
        die("<script>alert('用户不存在。');location.href='index.php';</script>");
    }
    $aUser = $db->fetch_array($result);
    if($aUser["userpass"]!==md5($_POST["userpass"])){
        die("<script>alert('用户密码错误');location.href='index.php';</script>");
    }
    $_SESSION["user"] =$aUser;
    die("<script>alert('登录成功。');location.href='index.php';</script>");