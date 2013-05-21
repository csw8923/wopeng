 <?php
   header("Content-Type: text/html;charset=utf-8");
  $id = $_REQUEST["id"];
 ?>
<?php
require 'config.php'; 
// 黑名单记录查询
$con = mysql_connect($host,$user,$pass);
if(!$con) die('mysql error:'.mysql_error());
mysql_select_db($dbdata,$con);
mysql_query("set names utf-8;");
$info_config = "select * from rulesinfo where id ='".$id."'";
$res_info = mysql_query($info_config);
while ($row_info = mysql_fetch_array($res_info))
{ 
   $rulename =  $row_info["rulename"];
   $rulefile =  $row_info["rulefile"];
   $part =  $row_info["part"];
   $addtime =  $row_info["addtime"];
   $autotime =  $row_info["autotime"];
   $url =  $row_info["geturl"];
   $list =  $row_info["getlist"];
   $listurl = $row_info["getlink"];
   $autocj = $row_info["autocj"];
   $domain = $row_info["gethost"];
   $gettitle = $row_info["gettitle"];
   $patterns = $row_info["patterns"];
   $replacements = $row_info["replacements"];
   $universal = $row_info["universal"];
   $getdetailurl = $row_info["getdetailurl"];
   $articleurl = $row_info["getdetailurl"];
   $article = $row_info["article"];
   $ispage = $row_info["ispage"];
   $articlepageurl = $row_info["articlepageurl"];
   $articlepage = $row_info["articlepage"];
   $getrange = $row_info["getrange"];
   $replace_content = $row_info["dpatterns"];
   $target_content = $row_info["dreplacements"];
   $uses = $row_info["uses"]; 
   $approval = $row_info["approval"]; 
   $compatible = $row_info["compatible"]; 
   $code = $row_info["code"]; 
}

 $url = html_entity_decode($url);
 $list = str_replace("[list]","(.*?)",html_entity_decode($list));
 $list = str_replace("<","[",$list);
 $list = str_replace(">","]",$list);

 $listurl = str_replace("[link]","(.*?)",html_entity_decode($listurl));
 $listurl = str_replace("<","[",$listurl);
 $listurl = str_replace(">","]",$listurl);
 
 $domain = html_entity_decode($domain);
 $articleurl = html_entity_decode($articleurl);
 $article = str_replace("[article]","(.*?)",html_entity_decode($article));
 $article = str_replace("<","[",$article);
 $article = str_replace(">","]",$article);
 $articlepageurl = html_entity_decode($articlepageurl);
 $articlepage = str_replace("[pagelist]","(.*?)",html_entity_decode($articlepage));
 $articlepage = str_replace("<","[",$articlepage);
 $articlepage = str_replace(">","]",$articlepage);
 $getrange = str_replace("[getrange]","(.*?)",html_entity_decode($getrange));
 $getrange = str_replace("<","[",$getrange);
 $getrange = str_replace(">","]",$getrange);
 $replace_content = html_entity_decode($replace_content);
 $target_content = html_entity_decode($target_content);

$arr = array('rulename'=>$rulename,'rulefile'=>$rulefile,'part'=>$part,'addtime'=>$addtime,'autotime'=>$autotime,'url'=>$url,'list'=>$list,'listurl'=>$listurl,'autocj'=>$autocj,'domain'=>$domain,'gettitle'=>$gettitle,'patterns'=>$patterns,'replacements'=>$replacements,'universal'=>$universal,'getdetailurl'=>$getdetailurl,'uses'=>$uses,'compatible'=>$compatible,'articleurl'=>$articleurl,'article'=>$article,'ispage'=>$ispage,'articlepageurl'=>$articlepageurl,'articlepage'=>$articlepage,'getrange'=>$getrange,'replace_content'=>$replace_content,'target_content'=>$target_content,'compatible'=>$compatible,'uses'=>$uses,'approval'=>$approval,'code'=>$code);
echo json_encode($arr);
?>