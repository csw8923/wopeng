<title>爬站模拟系统</title>
<?php
	header("Content-Type:text/html;charset=utf-8");
	//$header[]="Content-Type: text/xml; charset=utf-8";
	
	// 实际代入参数
	$getdetailurl = 'http://www.admin5.com/article/20121128/474600.shtml';
	$getrange = '<div class="content">[detail]</div>';
	$dpatterns = '11月27日,禁止';
	$dreplacements = '11月28日,允许';
	// 实际代入参数
	
	$ch = curl_init();  
	$data = array('name' => '262055400');
	curl_setopt($ch, CURLOPT_URL, $getdetailurl);  
	//curl_setopt($ch, CURLOPT_HEADER,false);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出
	curl_setopt($ch, CURLOPT_POST, 1);  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
	curl_setopt($ch, CURLOPT_HEADER, 0);  
	$result=curl_exec($ch);  
	curl_close($ch);
	// 编码转换
	 $coding = mb_detect_encoding($result, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
	 if ($coding == 'UTF-8')
	 {}
	 else
	 { $result = mb_convert_encoding($result, "UTF-8", "GBK"); }
	 //var_dump($result); // 预览效果
	 //var_dump(htmlspecialchars($result)); // 预览效果
?>
  <link rel="stylesheet" title="Default" href="http://softwaremaniacs.org/media/soft/highlight/styles/tomorrow-night-eighties.css">
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
  <body>
    <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  
            <?php     
			   $getdetailurl = $getrange;
			   $getdetailurl = str_replace("[detail]","(.*?)",$getdetailurl);
			   $getdetailurl = str_replace("/","\/",$getdetailurl);
			   preg_match('/'.$getdetailurl.'/s', $result, $matches);
	
			   	// 自定义 替换
			   // ==== 寻找内容 ====
			   $patterns = array();
			   $patterns_temp = $dpatterns;
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
			   $replacements_temp = $dreplacements;
			   $replacements_temp = str_replace("/","\/",$replacements_temp);
			   $replacements = explode(",",$replacements_temp);
			   
			   // ==== 执行替换 ====
			   $detail = preg_replace($patterns, $replacements,$matches[1]);
			   
			   var_dump($detail);
            ?>
          </code></pre>
        </td>
     </tr>
    </table>
 </body>