<?php
//这里是我的一个数据库类
require("common.php");
error_reporting(E_ALL & ~E_NOTICE);
$id = $_REQUEST["id"];
 $sysfun = new sysfun('rulesinfo'); //实例化
   // 查找数据 - 无条件
 $conditions = array( 'id' => $id);
 $find_demo = $sysfun->findAll($conditions);
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="Acquisition/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="Acquisition/artDialog4.1.6/plugins/iframeTools.js"></script>
<form action="a_save.php" method="post" enctype="multipart/form-data">
<table align="center" style="margin-top:60px;" border="1" id='resulttable'>
  <tr>
    <td>
      <fieldset>
       <legend>添加数据</legend>
        <input type="hidden" name="id" id="id" value="<?php echo $find_demo[0]['id']; ?>">
        <p>规则名称：<label>
          <input type="text" name="rulename" id="rulename" value="<?php echo $find_demo[0]['rulename']; ?>">
        </label></p>
        <p>来源：<label><input type="text" name="rulefile" id="rulefile" value="<?php echo $find_demo[0]['rulefile']; ?>"></label></p>
        <p>【兼容模式】：<label>
          <?php 
             if($find_demo[0]['compatible'] == 1)
             {
          ?>
                <input id="compatible" name="compatible" type="checkbox" checked />
             <?php 
             }
             else 
             {
             ?>
                <input id="compatible" name="compatible" type="checkbox" />
             <?php } ?>

                 </label></p>
        <p>【获取源码】：<label>
          <input id="geturl" name="geturl" size="60" type="text" value="<?php echo $find_demo[0]['geturl']; ?>">
          <input id="getcode" class='btn btn-primary' name="66" type="button" value="获取源码">
        </label></p>     
         <p>【获取列表】：<label>
         <input id="getlist" name="getlist" size="60" type="text" value='<?php echo $find_demo[0]['getlist']; ?>'>
         <input id="getlistcode" class='btn btn-primary' name="66" type="button" value="获取列表">
        </label></p>                       
        <p>【获取链接】：<label>
           <input id="getlink" name="getlink" size="60" type="text" value='<?php echo $find_demo[0]['getlink']; ?>'>
        </label></p>     
        <p>【自动获取】：<label>
          <?php 
             if($find_demo[0]['autocj'] == 1)
             {
          ?>
                <input id="autocj" name="autocj" type="checkbox" checked />
             <?php 
             }
             else 
             {
             ?>
                <input id="autocj" name="autocj" type="checkbox" />
             <?php } ?>
                    </label></p>    
        <p>【获取链接】：<label>
           <input id="gethost" name="gethost" size="60" type="text" value='<?php echo $find_demo[0]['gethost']; ?>'>
           <input id="getlinkcode" class='btn btn-primary' name="66" type="button" value="获取链接">
        </label></p>   
        <p>【标题规则】：<label>
           <input id="gettitle" name="gettitle" size="60" type="text" value='<?php echo $find_demo[0]['gettitle']; ?>'>
        </label></p>   
        <p>【待替换目标】：<label>
           <input id="patterns" name="patterns" size="60" type="text" value='<?php echo $find_demo[0]['patterns']; ?>'>
        </label></p>      
        <p>【替换后结果】：<label>
           <input id="replacements" name="replacements" size="60" type="text" value='<?php echo $find_demo[0]['replacements']; ?>'>
           <input id="gettitlecode" class='btn btn-primary' name="66" type="button" value="获取标题">
        </label></p>   
        <p>【通用获取】：<label>
            <?php 
             if($find_demo[0]['universal'] == 1)
             {
          ?>
                <input id="universal" name="universal" type="checkbox" checked />
             <?php 
             }
             else 
             {
             ?>
                <input id="universal" name="universal" type="checkbox" />
             <?php } ?>
            <input id="getuniversalcode" class='btn btn-primary' name="66" type="button" value="通用方法获取标题和链接">
        </label></p>   
        <p>【文章地址】：<label>
           <input id="getdetailurl" name="getdetailurl" size="60" type="text" value="<?php echo $find_demo[0]['getdetailurl']; ?>">
        </label></p>   
        <p>【匹配规则】：<label>
           <input id="getrange" name="getrange" size="60" type="text" value='<?php echo $find_demo[0]['getrange']; ?>'>
        </label></p>   
        <p>【待替换目标】：<label>
           <input id="dpatterns" name="dpatterns" size="60" type="text" value='<?php echo $find_demo[0]['dpatterns']; ?>'>
        </label></p>   
        <p>【替换后结果】：<label>
          <input id="dreplacements" name="dreplacements" size="60" type="text" value='<?php echo $find_demo[0]['dreplacements']; ?>'>
          <input id="getdetailcode" class='btn btn-primary' name="66" type="button" value="获取内容">
        </label></p>   
        <p>分类：<label>
          <input id="dreplacements" name="dreplacements" size="60" type="text" value='<?php echo $find_demo[0]['part']; ?>'>
        </label></p>   
       <p>添加时间：<label>
         <input type="text" name="addtime" id="addtime" value="<?php echo date('Y-m-d H:i:s',$find_demo[0]['addtime']); ?>">
        </label></p>  
        <p>【编码-打钩 utf-8】：<label>
          <?php 
             if($find_demo[0]['code'] == 1)
             {
          ?>
                <input id="code" name="code" type="checkbox" checked />
             <?php 
             }
             else 
             {
             ?>
                <input id="code" name="code" type="checkbox" />
             <?php } ?>
                    </label></p>  
    </td>
  </tr>
  <tr>
    <th align="left" colspan="13">
    <label><input type="submit" name="add" value="保存" id="rulesbutton"></label>
    </th>
  </tr>
</table>
</form>
<script>
$(document).ready(function(){  
  $('#getcode').click(function (){  
       var geturl = $('#geturl').val();
     var compatible = $('#compatible').is(':checked')? "1":"0";
       showDialog(geturl,null,null,null,null,null,null,null,null,compatible,1);
  });  
  $('#getlistcode').click(function (){  
       var geturl = $('#geturl').val();
       var getlist = $('#getlist').val();
     var compatible = $('#compatible').is(':checked')? "1":"0";
       showDialog(geturl,getlist,null,null,null,null,null,null,null,compatible,2);
  });  
  $('#getlinkcode').click(function (){  
       var geturl = $('#geturl').val();
       var getlist = $('#getlist').val();
     var getlink = $('#getlink').val();
     var gethost = $('#gethost').val();
     var auto = $('#autocj').is(':checked')? "1":"0";
     var compatible = $('#compatible').is(':checked')? "1":"0";
     showDialog(geturl,getlist,getlink,auto,gethost,null,null,null,null,compatible,3);
  });  
  $('#gettitlecode').click(function (){  
       var geturl = $('#geturl').val();
       var getlist = $('#getlist').val();
     var getlink = $('#getlink').val();
     var gethost = $('#gethost').val();
     var gettitle = $('#gettitle').val();
     var patterns = $('#patterns').val();
     var replacements = $('#replacements').val();
     var compatible = $('#compatible').is(':checked')? "1":"0";
       showDialog(geturl,getlist,getlink,null,gethost,gettitle,patterns,replacements,null,compatible,4);
  }); 
  $('#getuniversalcode').click(function (){  
       var geturl = $('#geturl').val();
       var getlist = $('#getlist').val();
     var gethost = $('#gethost').val();
     var patterns = $('#patterns').val();
     var replacements = $('#replacements').val();
     var universal = $('#universal').is(':checked')? "1":"0";
     var compatible = $('#compatible').is(':checked')? "1":"0";
       showDialog(geturl,getlist,getlink,null,gethost,gettitle,patterns,replacements,universal,compatible,5);
  }); 
  
  $('#getdetailcode').click(function (){  
       var getdetailurl = $('#getdetailurl').val();
     var getrange = $('#getrange').val();
     var dpatterns = $('#dpatterns').val();
     var dreplacements = $('#dreplacements').val();
     var compatible = $('#compatible').is(':checked')? "1":"0";
       DetailDialog(getdetailurl,getrange,dpatterns,dreplacements,compatible);
  }); 
});  

function showDialog(geturl,getlist,getlink,auto,gethost,gettitle,patterns,replacements,universal,compatible,mode){
  if(mode == 1)
  {
    art.dialog.open('Acquisition/getcode.php?geturl='+geturl+'&compatible='+compatible,
    {title: '获取源码', width: 980, height: 500});
  }
  
  if(mode == 2)
  {
    art.dialog.open('Acquisition/getlist.php?geturl='+geturl+'&getlist='+getlist+'&compatible='+compatible,
    {title: '获取列表', width: 980, height: 500});
  }
  
  if(mode == 3)
  {
    if(auto == 0)
    {
       art.dialog.open('Acquisition/getlink.php?geturl='+geturl+'&getlist='+getlist+'&getlink='+getlink+'&auto='+auto+'&getlink='+getlink+'&gethost='+gethost+'&compatible='+compatible,
       {title: '获取链接', width: 980, height: 500});
    }
    else
    {
       art.dialog.open('Acquisition/getlink.php?geturl='+geturl+'&getlist='+getlist+'&getlink='+getlink+'&auto='+auto+'&getlink='+getlink+'&gethost='+gethost+'&compatible='+compatible,
       {title: '获取链接(自动)', width: 980, height: 500});
    }
  }
  
  if(mode == 4)
  {
    art.dialog.open('Acquisition/gettitle.php?geturl='+geturl+'&getlist='+getlist+'&getlink='+getlink+'&gettitle='+gettitle+'&patterns='+patterns+'&replacements='+replacements+'&compatible='+compatible,
    {title: '获取标题', width: 980, height: 500});
  }
  
  if(mode == 5)
  {
    art.dialog.open('Acquisition/getuniversal.php?geturl='+geturl+'&getlist='+getlist+'&getlink='+getlink+'&gethost='+gethost+'&patterns='+patterns+'&replacements='+replacements+'&universal='+universal+'&compatible='+compatible,
    {title: '通用方法获取标题和链接', width: 980, height: 500});
  }
}

function DetailDialog(getdetailurl,getrange,dpatterns,dreplacements,compatible){
        art.dialog.open('Acquisition/getdetail.php?getdetailurl='+getdetailurl+'&getrange='+getrange+'&dpatterns='+dpatterns+'&dreplacements='+dreplacements+'&compatible='+compatible,
    {title: '获取内容', width: 980, height: 500});
}
</script>