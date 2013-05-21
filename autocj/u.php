 <?php
  header("Content-Type: text/html;charset=utf-8");
  $id = $_REQUEST['id'];
 ?>
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<input name="detail" type="button" onclick="detail()" id="detail" value="获取源码" />
<input name="intercept" type="button" onclick="intercept()" id="intercept" value="获取列表" />
<input name="cjurl" type="button" onclick="cjurl()" id="cjurl" value="获取链接" />
<input name="isarticle" type="button" onclick="isarticle()" id="isarticle" value="获取内容" />
<input name="savedata" type="button" onclick="savedata()" id="savedata" value="提交数据" />
<input name="code" type="button" onclick="code()" id="code" value="编码" />
<script>
function detail(){
  window.location.href="u.php?id=<?php echo $id?>&detail=1&intercept=0&cjurl=0&isarticle=0"
}

function intercept(){
  window.location.href="u.php?id=<?php echo $id?>&detail=0&intercept=1&cjurl=0&isarticle=0"
}

function cjurl(){
  window.location.href="u.php?id=<?php echo $id?>&detail=0&intercept=0&cjurl=1&isarticle=0"
}

function isarticle(){
  window.location.href="u.php?id=<?php echo $id?>&detail=0&intercept=0&cjurl=0&isarticle=1"
}

function code(){
    window.location.href="g.php?id=<?php echo $id?>&detail=0&intercept=0&cjurl=0&isarticle=1"
}

function savedata(){
  var formdata = {
      'geturl' : $('#url').val(),
      'getlist' : $('#list').val(),
      'getlink' : $('#listurl').val(),
      'gethost' : $('#domain').val(),
      'getdetailurl' : $('#articleurl').val(),
      'article' : $('#article').val(),
      'ispage' : $('#ispage').val(),
      'articlepageurl' : $('#articlepageurl').val(),
      'getrange' : $('#getrange').val(),
      'articlepage' : $('#articlepage').val(),
      'dpatterns' : $('#replace_content').val(),
      'dreplacements' : $('#target_content').val(),
      'code' : $('#code').val(),
      'id' : $('#eid').val()
    };
   // 用$.post发送数据
    $.post('pyedata.php', formdata, function(result){
      $('#resulttable').append(result); // 返回的数据直接追加到resulttable表格的后面
      alert(result);
      window.location.reload(); 
    }); 
}
</script>
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
   $url =  $row_info["geturl"];
   $list =  $row_info["getlist"];
   $listurl = $row_info["getlink"];
   $domain = $row_info["gethost"];
   $articleurl = $row_info["getdetailurl"];
   $article = $row_info["article"];
   $ispage = $row_info["ispage"];
   $articlepageurl = $row_info["articlepageurl"];
   $articlepage = $row_info["articlepage"];
   $getrange = $row_info["getrange"];
   $replace_content = $row_info["dpatterns"];
   $target_content = $row_info["dreplacements"]; 
   $code = $row_info["code"]; 
}
?>  
<?php
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
    $code = html_entity_decode($code);
?> 
<hr />
<?php
   $url = htmlspecialchars($url);

   $list = str_replace("[","<",$list);
   $list = str_replace("]",">",$list);
   $list = str_replace("&quot;",'"',$list);
   $list = htmlspecialchars(str_replace("(.*?)","[list]",$list));

   $listurl = str_replace("[","<",$listurl);
   $listurl = str_replace("]",">",$listurl);
   $listurl = str_replace("&quot;",'"',$listurl);
   $listurl = htmlspecialchars(str_replace("(.*?)","[link]",$listurl));

   $domain = htmlspecialchars($domain);
   $articleurl = htmlspecialchars($articleurl);

   $article = str_replace("[","<",$article);
   $article = str_replace("]",">",$article);
   $article = str_replace("&quot;",'"',$article);
   $article = htmlspecialchars(str_replace("(.*?)","[article]",$article));

   $articlepageurl = htmlspecialchars($articlepageurl);

   $articlepage = str_replace("[","<",$articlepage);
   $articlepage = str_replace("]",">",$articlepage);
   $articlepage = str_replace("&quot;",'"',$articlepage);
   $articlepage = htmlspecialchars(str_replace("(.*?)","[pagelist]",$articlepage));

   $getrange = str_replace("[","<",$getrange);
   $getrange = str_replace("]",">",$getrange);
   $getrange = str_replace("&quot;",'"',$getrange);
   $getrange = htmlspecialchars(str_replace("(.*?)","[getrange]",$getrange));

   $replace_content = htmlspecialchars($replace_content);
   $target_content = htmlspecialchars($target_content);
   $code = htmlspecialchars($code);

?>
采集源地址:<input id="url" name="url" type="text" size="60" value="<?php echo $url ?>" /><br />
采集列表范围规则:<input id="list" name="list" type="text" size="60" value="<?php echo $list ?>" /><br />
获取采集网址:<input id="listurl" name="listurl" type="text" size="60" value="<?php echo $listurl ?>" /><br />
域名补齐：<input id="domain" name="domain" type="text" size="60" value="<?php echo $domain ?>" /><br />
文章网址：<input id="articleurl" name="articleurl" type="text" size="60" value="<?php echo $articleurl ?>" /><br />
文章内容范围规则:<input id="article" name="article" type="text" size="60" value="<?php echo $article ?>" /><br />
是否开启分页:<input id="ispage" name="ispage" type="text" size="60" value="<?php echo $ispage ?>" /><br />
分页网址规则：<input id="articlepageurl" name="articlepageurl" type="text" size="60" value="<?php echo $articlepageurl ?>" /><br />
分页网址范围划定:<input id="getrange" name="getrange" type="text" size="60" value="<?php echo $getrange ?>" /><br />
分页内容范围划定:<input id="articlepage" name="articlepage" type="text" size="60" value="<?php echo $articlepage ?>" /><br />
内容替换:<input id="replace_content" name="replace_content" type="text" size="60" value="<?php echo $replace_content ?>" /><br />
替换成什么:<input id="target_content" name="target_content" type="text" size="60" value="<?php echo $target_content ?>" /><br />
编码 utf-8 1:<input id="code" name="code" type="text" size="60" value="<?php echo $code ?>" />
<input id="eid" name="eid" type="hidden" size="60" value="<?php echo $id ?>" />
<hr />
<?php
$detail = $_REQUEST['detail'];
$intercept = $_REQUEST['intercept'];
$cjurl = $_REQUEST['cjurl'];
$isarticle = $_REQUEST['isarticle'];
if (!empty($id))
{
    $id = trim($id);
    passthru('python ./some.py '.$id.' '.$detail.' '.$intercept.' '.$cjurl.' '.$isarticle);
}