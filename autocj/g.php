 <?php
  header("Content-Type: text/html;charset=gbk");
  $id = $_REQUEST['id'];
 ?>
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<input name="detail" type="button" onclick="detail()" id="detail" value="��ȡԴ��" />
<input name="intercept" type="button" onclick="intercept()" id="intercept" value="��ȡ�б�" />
<input name="cjurl" type="button" onclick="cjurl()" id="cjurl" value="��ȡ����" />
<input name="isarticle" type="button" onclick="isarticle()" id="isarticle" value="��ȡ����" />
<input name="savedata" type="button" onclick="savedata()" id="savedata" value="�ύ����" />
<input name="code" type="button" onclick="code()" id="code" value="����" />
<script>
function detail(){
  window.location.href="g.php?id=<?php echo $id?>&detail=1&intercept=0&cjurl=0&isarticle=0"
}

function intercept(){
  window.location.href="g.php?id=<?php echo $id?>&detail=0&intercept=1&cjurl=0&isarticle=0"
}

function cjurl(){
  window.location.href="g.php?id=<?php echo $id?>&detail=0&intercept=0&cjurl=1&isarticle=0"
}

function isarticle(){
  window.location.href="g.php?id=<?php echo $id?>&detail=0&intercept=0&cjurl=0&isarticle=1"
}

function code(){
    window.location.href="u.php?id=<?php echo $id?>&detail=0&intercept=0&cjurl=0&isarticle=1"
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
   // ��$.post��������
    $.post('../../index.php?c=rulesform&a=pyedata', formdata, function(result){
      $('#resulttable').append(result); // ���ص�����ֱ��׷�ӵ�resulttable���ĺ���
      alert(result);
      window.location.reload(); 
    }); 
}
</script>
<?php
require 'config.php'; 
// ��������¼��ѯ
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
�ɼ�Դ��ַ:<input id="url" name="url" type="text" size="60" value="<?php echo $url ?>" /><br />
�ɼ��б�Χ����:<input id="list" name="list" type="text" size="60" value="<?php echo $list ?>" /><br />
��ȡ�ɼ���ַ:<input id="listurl" name="listurl" type="text" size="60" value="<?php echo $listurl ?>" /><br />
�������룺<input id="domain" name="domain" type="text" size="60" value="<?php echo $domain ?>" /><br />
������ַ��<input id="articleurl" name="articleurl" type="text" size="60" value="<?php echo $articleurl ?>" /><br />
�������ݷ�Χ����:<input id="article" name="article" type="text" size="60" value="<?php echo $article ?>" /><br />
�Ƿ�����ҳ:<input id="ispage" name="ispage" type="text" size="60" value="<?php echo $ispage ?>" /><br />
��ҳ��ַ����<input id="articlepageurl" name="articlepageurl" type="text" size="60" value="<?php echo $articlepageurl ?>" /><br />
��ҳ��ַ��Χ����:<input id="getrange" name="getrange" type="text" size="60" value="<?php echo $getrange ?>" /><br />
��ҳ���ݷ�Χ����:<input id="articlepage" name="articlepage" type="text" size="60" value="<?php echo $articlepage ?>" /><br />
�����滻:<input id="replace_content" name="replace_content" type="text" size="60" value="<?php echo $replace_content ?>" /><br />
�滻��ʲô:<input id="target_content" name="target_content" type="text" size="60" value="<?php echo $target_content ?>" /><br />
���� utf-8 1:<input id="code" name="code" type="text" size="60" value="<?php echo $code ?>" />
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