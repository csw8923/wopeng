<?php
header("Content-Type:text/html;charset=utf-8");

class autogather extends basis
{
    // 登入首页
	function index(){
	$id = $this->spArgs("id");
	$classid = $this->spArgs("classid");
	$rulesinfo = spClass('rulesinfo'); 
	$conditions = array( 'id' => $id );
	$result = $rulesinfo->find($conditions);  // 分页
	//dump($result);
		
	// 实际代入参数
	$geturl = $result['geturl'];
	$getlist = $result['getlist'];
    $auto = $result['autocj'];
    $getlink = $result['getlink'];
    $gethost = $result['gethost'];
	$gettitle = $result['gettitle'];
	$getpatterns = $result['patterns'];
    $getreplacements = $result['replacements'];
	$universal = $result['universal'];
	$rulefile = $result['rulefile'];
	$compatible = $result['compatible'];
	
   $md5file = md5_file($geturl);
   if($result['filemd5'] != $md5file)
   {
	// 实际代入参数
	if($compatible != "1")
	{
	    $ch = curl_init();  
		$data = array('name' => '262055400');
		curl_setopt($ch, CURLOPT_URL, $geturl);  
		//curl_setopt($ch, CURLOPT_HEADER,false);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
		curl_setopt($ch, CURLOPT_HEADER, 0);  
		$result=curl_exec($ch);  
		curl_close($ch);
	}
	else
	{
	    $result = file_get_contents($geturl);
	}
	// 编码转换
	 $coding = mb_detect_encoding($result, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
	 if ($coding == 'UTF-8')
	 {}
	 else
	 { $result = mb_convert_encoding($result, "UTF-8", "GBK"); }
	 //var_dump($result); // 预览效果
	 //var_dump(htmlspecialchars($result)); // 预览效果
?>
<!--  <link rel="stylesheet" title="Default" href="http://softwaremaniacs.org/media/soft/highlight/styles/tomorrow-night-eighties.css">
  <style>
    body {
      font: small Arial, sans-serif;
    }
    h2 {
      font: bold 100% Arial, sans-serif;
      margin-top: 2em;
      margin-bottom: 0.5em;
    }
    table {
      width: 100%; padding: 0; border-collapse: collapse;
    }
    th {
      width: 12em;
      padding: 0; margin: 0;
    }
    td {
      padding-bottom: 1em;
    }
    td, th {
      vertical-align: top;
      text-align: left;
    }
    pre {
      margin: 0; font-size: medium;
    }
    .code {
      font: medium monospace;
    }
    .code .keyword {
      font-weight: bold;
    }
  </style>
  <script src="http://softwaremaniacs.org/media/soft/highlight/highlight.pack.js"></script>
  <script>
  hljs.tabReplace = '    ';
  hljs.initHighlightingOnLoad();
  </script>
  <body>-->
    <?php if($universal == "0"){?>
  <!--  <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  -->
            <?php     
			   $getlist = $getlist;
			   $getlist = str_replace("[list]","(.*?)",$getlist);
			   $getlist = str_replace("/","\/",$getlist);
			   preg_match('/'.$getlist.'/s', $result, $matches);
			   
			   $auto = $auto;
			   if($auto == '1')
			   {
				   preg_match_all("/href=('|\")(.*?)(?1)\s+/",$matches[0],$mas);
				   //var_dump($mas[1]);
				   $url = array();
				   for($i=0;$i<count($mas[0]);$i++)
				   {
					   $url[] = $gethost.preg_replace("/".$mas[1][$i]."/","",$mas[2][$i]);
				   }
				   var_dump($url); // 预览效果
			   }
			   else
			   {
				   $getlink = $getlink;
				   $getlink = str_replace("[link]","(.*?)",$getlink);
				   $getlink = str_replace("/","\/",$getlink);
				   preg_match_all('/'.$getlink.'/s',$matches[0],$mas);
				   $url = array();
				   for($i=0;$i<count($mas[0]);$i++)
				   {
					   $url[] = $gethost.$mas[1][$i];
				   }
				   var_dump($url); // 预览效果
			   } 
            ?>
      <!--    </code></pre>
        </td>
     </tr>
    </table>
    
    <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  -->
            <?php     			   
			   // 自定义 替换
			   // ==== 寻找内容 ====
			   $patterns = array();
			   $patterns_temp = $getpatterns;
			   $patterns_temp = str_replace("/","\/",$patterns_temp);
			   $patterns = explode(",",$patterns_temp);
			   $i=0;
			   foreach ($patterns as $value)
			   {
				  $patterns[$i] = "/".$value."/s";
			      $i++;
			   }
			   // ==== 替换内容 ====
			   $replacements = array();
			   $replacements_temp = $getreplacements;
			   $replacements_temp = str_replace("/","\/",$replacements_temp);
			   $replacements = explode(",",$replacements_temp);
			   
			   // ==== 执行替换 ====
			   $matches[0] = preg_replace($patterns, $replacements,$matches[0]);
			   // 自定义 替换
			   
			   $gettitle = $gettitle;
			   $gettitle = str_replace("[title]","(.*?)",$gettitle);
			   $gettitle = str_replace("/","\/",$gettitle);
			   // 筛选出 标题
			   preg_match_all('/'.$gettitle.'/s',$matches[0],$title);

			   var_dump($title[1]);
            ?>
    <!--      </code></pre>
        </td>
     </tr>
    </table>-->
    <?php } else {?>
  <!--   <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  -->
            <?php     
			   $getlist = $getlist;
			   $getlist = str_replace("[list]","(.*?)",$getlist);
			   $getlist = str_replace("/","\/",$getlist);
			   preg_match('/'.$getlist.'/s', $result, $matches);
			   
			   // 自定义 替换
			   // ==== 寻找内容 ====
			   $patterns = array();
			   $patterns_temp = $getpatterns;
			   $patterns_temp = str_replace("/","\/",$patterns_temp);
			   $patterns = explode(",",$patterns_temp);
			   $i=0;
			   foreach ($patterns as $value)
			   {
				  $patterns[$i] = "/".$value."/s";
			      $i++;
			   }
			   // ==== 替换内容 ====
			   $replacements = array();
			   $replacements_temp = $getreplacements;
			   $replacements_temp = str_replace("/","\/",$replacements_temp);
			   $replacements = explode(",",$replacements_temp);
			   
			   // ==== 执行替换 ====
			   $matches[0] = preg_replace($patterns, $replacements,$matches[0]);
			   // 自定义 替换
			   preg_match_all('/<a.*?(?: |\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>(.+?)<\/a.*?>/sim',$matches[0],$m);
			   
			   $j=0;
			   foreach ($m[1] as $value)
			   {
				  $m[1][$j] = $gethost.$value;
			      $j++;
			   }
			   $url = array();
			   var_dump($m[1]);
			   $url = $m[1];
			   var_dump($m[2]);
			   $title[1] = $m[2];
            ?>
     <!--     </code></pre>
        </td>
     </tr>
    </table>-->
    <?php }?>
    
<!-- </body>-->
<?php
       $j = 0;
	   foreach ($title[1] as $value)
	   {
        if($j != 50 )
        {
        $conditions = array( 'title' => $value );
        $e_user = spClass('messageinfo'); 
        $e_result = $e_user->find($conditions); 
        if($e_result == 0)
        { 
           $newrow = array( // PHP的数组
         'username'=>$rulefile,
         'title'=>$value,
         'releasedate'=>time(),
         'classid'=>$classid,
         'cjurls'=>$url[$j],
         'cjid'=>$id,
        );
        $messageinfo = spClass('messageinfo'); 
        $messageinfo->create($newrow);
        }
        else
        { echo 'yes';}
		  }
		  else
		  {break;}
		 $j++;
	   }		   
   }

}
}
