<title>爬站模拟系统</title>
<?php
	header("Content-Type:text/html;charset=utf-8");
	//$header[]="Content-Type: text/xml; charset=utf-8";
	$compatible = $_REQUEST["compatible"];
	if($compatible != "1")
	{
	    $ch = curl_init();  
		$data = array('name' => '262055400');
		curl_setopt($ch, CURLOPT_URL, $_REQUEST["geturl"]);  
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
	    $result = file_get_contents($_REQUEST["geturl"]);
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
			   $getlist = $_REQUEST["getlist"];
			   $getlist = str_replace("[list]","(.*?)",$getlist);
			   $getlist = str_replace("/","\/",$getlist);
			   preg_match('/'.$getlist.'/s', $result, $matches);
			   
			   // 自定义 替换
			   // ==== 寻找内容 ====
			   $patterns = array();
			   $patterns_temp = $_REQUEST["patterns"];
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
			   $replacements_temp = $_REQUEST["replacements"];
			   $replacements_temp = str_replace("/","\/",$replacements_temp);
			   $replacements = explode(",",$replacements_temp);
			   
			   // ==== 执行替换 ====
			   $matches[0] = preg_replace($patterns, $replacements,$matches[0]);
			   // 自定义 替换
			   
			   $gettitle = $_REQUEST["gettitle"];
			   $gettitle = str_replace("[title]","(.*?)",$gettitle);
			   $gettitle = str_replace("/","\/",$gettitle);
			   // 筛选出 标题
			   preg_match_all('/'.$gettitle.'/s',$matches[0],$title);

			   var_dump($title[1]);
            ?>
          </code></pre>
        </td>
     </tr>
    </table>
 </body>