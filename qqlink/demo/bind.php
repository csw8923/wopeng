<?php
    header("Content-type:text/html;charset=UTF-8");
    session_start();
    if(!isset($_SESSION["openid"])||empty($_SESSION["openid"])){
        die("<script>location.href='oauth/login.php';</script>");
    }
    include('common/config.php');
    include('common/mysql.php');
    $db = new mysql($aConfig["db"]["host"],$aConfig["db"]["user"],$aConfig["db"]["pass"],$aConfig["db"]["name"],$aConfig["db"]["lang"]);
    //查询数据库中是否有openid的记录
    $sql = "select * from `user_bind_info` where `openid`='".$_SESSION["openid"]."'";
    $result = $db->query($sql);
    if($db->num_rows($result)>0){
        if(isset($_SESSION["user"])&&!empty($_SESSION["user"])){
            die("<script>alert('该QQ绑定了其他用户');location.href='index.php';</script>");
        }else{
            //存在记录
            $aUserBind = $db->fetch_array($result);
            $sql = "select * from `user` where `userid`='".$aUserBind["userid"]."'";
            $result = $db->query($sql);
            $aUser = $db->fetch_array($result);
            if(empty($aUser)){
                die("<script>alert('用户不存在');location.href='index.php';</script>");
            }else{
                //执行登录过程
                $_SESSION["user"] = $aUser;
                die("<script>alert('用户登录成功。');location.href='index.php';</script>");
            }
        }
    }else{
        //openid不在数据的情况
        //用户是否已经登录
        if(isset($_SESSION["user"])&&!empty($_SESSION["user"])){
            //执行登录之后的绑定
            $sql = "insert into `user_bind_info` set `userid`='".intval($_SESSION["user"]["userid"])."',`openid`='".$_SESSION["openid"]."',`access_token`='".$_SESSION["access_token"]."',`usernick`='".$_SESSION["qquser"]["nickname"]."'";
            $result = $db->query($sql);
            if($db->affected_rows($result)>0){
                die("<script>alert('绑定用户成功。');location.href='index.php';</script>");
            }else{
                die("<script>alert('绑定用户失败。');location.href='index.php';</script>");
            }
        }else{
            //判断动作，是否为执行相关动作
            if(isset($_POST)&&!empty($_POST)){
                $do = $_POST["do"];
                //按照指定指令执行操作
                if($do=="bind"){
                    //执行用户登录绑定，先判断用户登录是否成功
                    if(!isset($_POST["username"])||empty($_POST["username"])){
                        die("<script>alert('用户名称为空。');location.href='bind.php';</script>");
                    }
                    if(!isset($_POST["userpass"])||empty($_POST["userpass"])){
                        die("<script>alert('用户密码为空。');location.href='bind.php';</script>");
                    }
                    $sql = "select * from `user` where `username`='".$_POST["username"]."'";
                    $result = $db->query($sql);
                    if($db->num_rows($result)==0){
                        die("<script>alert('用户不存在。');location.href='bind.php';</script>");
                    }
                    $aUser = $db->fetch_array($result);
                    if($aUser["userpass"]!=md5($_POST["userpass"])){
                        die("<script>alert('用户密码错误');location.href='bind.php';</script>");
                    }
                    //数据库执行绑定
                    $sql = "insert into `user_bind_info` set `userid`='".intval($aUser["userid"])."',`openid`='".$_SESSION["openid"]."',`access_token`='".$_SESSION["access_token"]."',`usernick`='".$_SESSION["qquser"]["nickname"]."'";
                    $result = $db->query($sql);
                    if($db->affected_rows($result)==0){
                        die("<script>alert('用户登录绑定失败。');location.href='bind.php';</script>");
                    }else{
                        $_SESSION["user"] = $aUser;
                        die("<script>alert('用户登录绑定成功。');location.href='index.php';</script>");
                    }
                }elseif($do=="new"){
                    //执行用户注册绑定
                    if(!isset($_POST["username"])||empty($_POST["username"])){
                        die("<script>alert('用户名称为空。');location.href='bind.php';</script>");
                    }
                    if(!isset($_POST["userpass"])||empty($_POST["userpass"])){
                        die("<script>alert('用户密码为空。');location.href='bind.php';</script>");
                    }
                    //用户注册流程
                    $sql = "select * from `user` where `username`='".$_POST["username"]."'";
                    $result = $db->query($sql);
                    if($db->num_rows($result)>0){
                        die("<script>alert('该用户已经被注册。');location.href='bind.php';</script>");
                    }
                    $sql = "insert into `user` set `username`='".$_POST["username"]."',`userpass`='".md5($_POST["userpass"])."'";
                    $result  = $db->query($sql);
                    $iUserId = $db->insert_id();
                    if($iUserId<=0){
                        die("<script>alert('用户注册失败。');location.href='bind.php';</script>");
                    }
                    //数据库执行绑定
                    $sql = "insert into `user_bind_info` set `userid`='".intval($iUserId)."',`openid`='".$_SESSION["openid"]."',`access_token`='".$_SESSION["access_token"]."',`usernick`='".$_SESSION["qquser"]["nickname"]."'";
                    $result = $db->query($sql);
                    if($db->affected_rows($result)==0){
                        die("<script>alert('用户注册成功,用户绑定失败。');location.href='bind.php';</script>");
                    }else{
                        $_SESSION["user"] = array(
                            "userid" => $iUserId,
                            "username" => $_POST["username"],
                            "userpass" => md5($_POST["userpass"])
                        );
                        die("<script>alert('用户注册绑定成功。');location.href='index.php';</script>");
                    }
                }
            }else{
                //显示绑定页面
?>
<html>
<head>
<link href="../style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
</head>
<body>
<div class="list-div">
<form action="bind.php" method="POST">
<table>
<tr>
   <th colspan="2">用户登录绑定</th>
</tr>
<tr>
   <td class="narrow-label">当前用户:</td>
   <td><?php echo $_SESSION["qquser"]["nickname"];?></td>
<tr>
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
  <td><input type="submit" value="用户登录绑定" class="button" /><input type="hidden" name="do" value="bind" /></td>
</tr>
</table>
</form>
<br />
<br />
<form action="bind.php" method="POST">
<table>
<tr>
   <th colspan="2">用户注册绑定</th>
</tr>
<tr>
   <td class="narrow-label">当前用户:</td>
   <td><?php echo $_SESSION["qquser"]["nickname"];?></td>
<tr>
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
  <td><input type="submit" value="用户注册绑定" class="button" /><input type="hidden" name="do" value="new" /></td>
</tr>
</table>
</form>
</div>
</body>
</html>
<?php
            }
        }
    }