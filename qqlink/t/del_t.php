<?php
    header("Content-type:text/html; charset=UTF-8;");
	if(!file_exists('../common/config.php')){
		header("Location:../install/index.php");
		exit;
	}
    include_once("../common/function.php");
	if(!isset($_SESSION["openid"])||empty($_SESSION["openid"])){
		header("location:../oauth/login.php");
		exit;
	}
?>
<html>
<head>
<link href="../style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
</head>
<body>
<h1>QQ互联 - 删除微博</h1>
<div class="list-div">
<?php
    if(isset($_GET["id"])&&is_numeric($_GET["id"])){
        echo "<table>";
        $sUrl = "https://graph.qq.com/t/del_t";
        $aPOSTParam = array(
            "access_token" => $_SESSION["access_token"],
            "oauth_consumer_key"    =>    $aConfig["appid"],
            "openid"                =>    $_SESSION["openid"],
            "format"                =>    "json",
            "id"                =>        $_GET["id"]
        );
        $sContent = post($sUrl,$aPOSTParam);
        if($sContent!==FALSE){
            $aResult = json_decode($sContent,true);
            if($aResult["ret"]==0){
                echo "<tr><td class='narrow-label'>微博编号</td><td>".$aResult["data"]["id"]."</td></tr>";
            }else{
                echo "<tr><td class='narrow-label'>错误信息</td><td>".$aResult["msg"]."</td></tr>";
            }
        }
        echo "<tr><td class='narrow-label'></td><td><input type='button' class='button' value='返回首页' onclick='location.href=\"../index.php\";' /></td></tr>";
        echo "</table>";
    }
?>
</div>
</body>
</html>