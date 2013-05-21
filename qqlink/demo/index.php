<?php
    header("Content-type:text/html; charset=UTF-8;");
    session_start();
    if(isset($_SESSION["user"])&&!empty($_SESSION["user"])){
        echo "当前用户:".$_SESSION["user"]["username"].",<a href='exit.php'>用户退出</a>";
        //判断用户是否绑定QQ[新加]
        include("common/config.php");
        include("common/mysql.php");
        $db = new mysql($aConfig["db"]["host"],$aConfig["db"]["user"],$aConfig["db"]["pass"],$aConfig["db"]["name"],$aConfig["db"]["lang"]);
        $sql = "select * from `user_bind_info` where `userid`='".$_SESSION["user"]["userid"]."'";
        $result =$db->query($sql);
        if($db->num_rows($result)==0){
            echo "该用户尚未绑定QQ,<a href='oauth/login.php'><img src='images/qqlogin.png' border='0' /></a>";
        }else{
            $aUserBind = $db->fetch_array($result);
            echo "该用户绑定QQ:".$aUserBind["usernick"]."<a href='unbind.php'>解除绑定</a>";
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
<form action="login.php" method="POST">
<table>
<tr>
   <td class="narrow-label">用户名称:</td>
   <td><input type='input' class="input" name="username" /></td>
</tr>
<tr>
   <td class="narrow-label">用户密码:</td>
   <td><input type='password' class="input" name="userpass" /></td>
</tr>
<tr>
  <td class="narrow-label"></td>
  <td><input type="submit" value="用户登录" class="button" /><input type="button" value="添加用户" class="button" onclick="location.href='add.php';" /><a href='oauth/login.php'><img src='images/qqlogin.png' border='0' /></a></td>
</tr>
</table>
</form>
</div>
</body>
</html>
<?php
    }