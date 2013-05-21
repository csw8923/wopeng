<?php
//var_dump($_POST);
require 'config.php'; 
// 黑名单记录查询
$con = mysql_connect($cjhost,$cjuser,$cjpass);
if(!$con) die('mysql error:'.mysql_error());
mysql_select_db($cjdbdata,$con);
mysql_query("set names utf-8;");
if($_POST['approval'] == 1){
    $info_config = "select * from articles where url ='".$_POST['url']."'";
}
else
{
	$info_config = "select * from articlestest where url ='".$_POST['url']."'";
}
$res_info = mysql_query($info_config);
while ($row_info = mysql_fetch_array($res_info))
{ 
   $title =  $row_info["title"];
}
if(empty($title)){
   echo '录入数据库';
   if($_POST['approval'] == 1){
	$sql = "insert into articles(cid,title,content,addtime,url) ";
	$sql .= " values('{$_POST['part']}',";
	$sql .= " '{$_POST['titlecontent']}',";
	$sql .= " '{$_POST['newcontent']}',";
	$sql .= " '{$_POST['nowtime']}',";
	$sql .= " '{$_POST['url']}') ";
   }
   else
   {
   	 $sql = "insert into articlestest(cid,title,content,addtime,url) ";
	 $sql .= " values('{$_POST['part']}',";
	 $sql .= " '{$_POST['titlecontent']}',";
	 $sql .= " '{$_POST['newcontent']}',";
	 $sql .= " '{$_POST['nowtime']}',";
	 $sql .= " '{$_POST['url']}') ";
   }
   $res = mysql_query($sql);
}
else
{
	echo '重复';
}
?>