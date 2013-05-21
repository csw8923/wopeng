<?php
    header("Content-type: text/html; charset=UTF-8;");
    session_start();
    include("common/config.php");
    include("common/mysql.php");
    $db = new mysql($aConfig["db"]["host"],$aConfig["db"]["user"],$aConfig["db"]["pass"],$aConfig["db"]["name"],$aConfig["db"]["lang"]);
    $sql ="delete from `user_bind_info` where `userid`='".intval($_SESSION["user"]["userid"])."'";
    $result = $db->query($sql);
    if($db->affected_rows($result)>0){
        die("<script>alert('解除QQ绑定成功。');location.href='index.php';</script>");
    }else{
        die("<script>alert('解除QQ绑定失败。');location.href='index.php';</script>");
    }