<title>爬站模拟系统</title>
<?php
	header("Content-Type:text/html;charset=utf-8");
	//$header[]="Content-Type: text/xml; charset=utf-8";
	$ch = curl_init();  
	$data = array('name' => '262055400');
	curl_setopt($ch, CURLOPT_URL, "http://www.admin5.com/browse/177/index.shtml");  
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
               var_dump(htmlspecialchars($result)); // 预览效果
            ?>
          </code></pre>
        </td>
     </tr>
    </table>
    
   <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  
            <?php     
			   preg_match('/<ul class="list">(.*?)<\/ul>/s', $result, $matches);
               var_dump(htmlspecialchars($matches[0])); // 预览效果
            ?>
          </code></pre>
        </td>
     </tr>
    </table>
    
    <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  
            <?php     
			   preg_match('/<ul class="list">(.*?)<\/ul>/s', $result, $matches);
			   preg_match_all("/href=('|\")(.*?)(?1)\s+/",$matches[0],$mas);
			   //var_dump($mas[1]);
			   $url = array();
			   for($i=0;$i<count($mas[0]);$i++)
			   {
				   $url[] = 'http://www.admin5.com'.preg_replace("/".$mas[1][$i]."/","",$mas[2][$i]);
			   }
               var_dump($url); // 预览效果
            ?>
          </code></pre>
        </td>
     </tr>
    </table>
    
    <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  
            <?php     
			   preg_match('/<ul class="list">(.*?)<\/ul>/s', $result, $matches);
			   preg_match_all('/href="(.*?)"/s',$matches[0],$mas);
			   $url = array();
			   for($i=0;$i<count($mas[0]);$i++)
			   {
				   $url[] = 'http://www.admin5.com'.$mas[1][$i];
			   }
               var_dump($url); // 预览效果
            ?>
          </code></pre>
        </td>
     </tr>
    </table>
    
   <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  
            <?php     
			   preg_match('/<ul class="list">(.*?)<\/ul>/s', $result, $matches);
			   
			   // 自定义 替换
			   $patterns = array();
			   //$patterns[0] = '/<b>(.*?)<\/b>/s';
			   $patterns[0] = '/<b>/s';
			   $patterns[1] = '/<\/b>/s';
			   $replacements = array();
			   //$replacements[0] = '';
			   $replacements[0] = '';
			   $replacements[1] = '';
			   $matches[0] = preg_replace($patterns, $replacements,$matches[0]);
			   // 自定义 替换
			   
			   preg_match_all('/<a.*?(?: |\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>(.+?)<\/a.*?>/sim',$matches[0],$m);
               //print_r($m[1]);
               //print_r($m[2]);
               var_dump($m); // 预览效果
            ?>
          </code></pre>
        </td>
     </tr>
    </table>
    
    
    <table id="autotest">
      <tr>
        <td class="xml">
           <pre><code>  
            <?php     
			   // 过滤 文字列表
			   preg_match('/<ul class="list">(.*?)<\/ul>/s', $result, $matches);
			   
			   // 自定义 替换
			   $patterns = array();
			   //$patterns[0] = '/<b>(.*?)<\/b>/s';
			   $patterns[0] = '/<b>/s';
			   $patterns[1] = '/<\/b>/s';
			   $replacements = array();
			   //$replacements[0] = '';
			   $replacements[0] = '';
			   $replacements[1] = '';
			   $matches[0] = preg_replace($patterns, $replacements,$matches[0]);
			   // 自定义 替换
			   
			   // 筛选出 标题
			   preg_match_all('/target="_blank">(.*?)<\/a>/s',$matches[0],$title);

			   var_dump($title[1]);
            ?>
          </code></pre>
        </td>
     </tr>
    </table>
    
 </body>