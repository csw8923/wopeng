<?php
//这里是我的一个数据库类
require("common.php");
error_reporting(E_ALL & ~E_NOTICE);
// include_once 'mysql_class.php';
// $db = new mysql();
$pagenum = $_REQUEST['page'];
if(empty($pagenum))
{ 
  $pagenum = 0;
} 
 $sysfun = new sysfun('rulesinfo'); //实例化
   // 查找数据 - 无条件
 $listnum = 3;
 $page = $pagenum*$listnum;
 $conditions = "rulesinfo";
 $find_demo = $sysfun->findAll($conditions,"id ASC LIMIT ".$page.",".$listnum."");
 $find_count = $sysfun->findAll($conditions,"id ASC LIMIT ".$page.",".$listnum."");
?>
<style>
 .pager{ display:inline;}
 .pager li{ display:inline;}
</style>
【<a target="_blank" href='add.php'>添加</a>】
<br />
<br />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script>
	function initialize(id){
			$.post('initialize.php','id='+id, function(result){
				if(result == 1)
				{alert('失效状态!!');}else{alert('设置失败!!')}
				window.location.reload(); 
			});
	}

	function approval(id){
			$.post('approval.php','id='+id, function(result){
				if(result == 1)
				{alert('正常状态!!');}else{alert('设置失败!!')}
				window.location.reload(); 
			});
	}
</script>
 <table border="1">
  <tr>
    <th>ID</th>
    <th>规则名称</th>
    <th>来源</th>
    <th>地址</th>
    <th>分类</th>
    <th>添加时间</th>
    <th>操作</th>
  </tr>
<?php
if($find_demo){
	foreach($find_demo as $v){
	    echo '<tr><td>'.$v['id'].'</td>';
	    echo '<td>'.$v['rulename'].'</td>';
	    echo '<td>'.$v['rulefile'].'</td>';
	    echo '<td><a target="_blank" href='.$v['geturl'].'>点击查看</a></td>';
		echo '<td>'.$v['part'].'</td>';
		echo '<td>'.date('Y-m-d H:i:s',$v['addtime']).'</td>';
        echo '<td>';
        if($v['approval'] == '0')
        { echo '<a href="javascript:approval('.$v['id'].')">失效状态</a>'; }
        else
        { echo '<a href="javascript:initialize('.$v['id'].')">正常使用</a>'; }
		echo ' | <a target="_blank" href="u.php?id='.$v['id'].'&detail=0&intercept=0&cjurl=0&isarticle=0">采集向导</a> | <a target="_blank" href="edit.php?id='.$v['id'].'">编辑</a> | <a href="del.php?id='.$v['id'].'">删除</a> </td>';
	    echo '</tr>';
	}
	//生成表格
	$str = '<tr><td colspan="7">';
	    $str=$str."<ul class='pager'>";
	    if($pagenum > 0){
	       $str=$str."<li><a href='index.php?page=".($pagenum-1)."'>前一页</a></li>";
	    }
	    $str=$str."<li><a>第".($pagenum+1)."页</a></li>";
	    if(($pagenum+1) <= count($find_count)-1)
	    {
	       $str=$str."<li><a href='index.php?page=".($pagenum+1)."'>后一页</a></li>";
	    }
	    $str=$str."</ul>&nbsp;<span>总共：".count($find_count)." 条记录</span>";
	    $str=$str.'</td></tr>';
	    echo $str;
}
else
{ echo 'no';}
?>
</table>