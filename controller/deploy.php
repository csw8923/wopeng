 <?php
header("Content-Type:text/html;charset=utf-8");
require_once("email/class.mail.php");
class deploy extends basis
{
    // 登入首页
	function index(){
		$xml = simplexml_load_file('rules/deploy.xml');
// print_r($xml);
         $n = count($xml);
		$i=0;
		$infoaction=array();
		while($i<=$n-1)
		{
		  $name  = $xml-> article[$i] -> name;
		  $event  = $xml-> article[$i] -> event;
		  $infoaction[] = array('name'=>$name,'event'=>$event);
		  $i++;
	    }
	    $this->infoaction=$infoaction;
		$this -> display('admin/form/deploy.html');
	}

   function add(){
      $article_array = array( 
	  		"one" => array( 
				"name"=>"主域名",  
				"event"=>$this->spArgs('domain')
			), 
			"two" => array( 
				"name"=>"公告id",  
				"event"=>$this->spArgs('newsid')
			), 
			"three" => array( 
				"name"=>"邮箱SMTP服务器",  
				"event"=>$this->spArgs('emailhttp')
			), 
			"four" => array( 
				"name"=>"邮箱端口",  
				"event"=>$this->spArgs('emailport')
			), 
			"five" => array( 
				"name"=>"服务器邮箱账号",  
				"event"=>$this->spArgs('emailid')
			), 
			"six" => array( 
				"name"=>"密码",  
				"event"=>$this->spArgs('emailpassword')
			), 
			"seven" => array( 
				"name"=>"首页标题",  
				"event"=>$this->spArgs('title')
			), 
			"eight" => array( 
				"name"=>"首页关键字",  
				"event"=>$this->spArgs('keywords')
			), 
			"nine" => array( 
				"name"=>"首页说明",  
				"event"=>$this->spArgs('description')
			), 
	   ); 
	   
	   $dom = new DOMDocument('1.0', 'UTF-8'); 
	   $dom->formatOutput = true; 
	   $rootelement = $dom->createElement("MoreWindows"); 
	   foreach ($article_array as $key=>$value) 
	   { 
			$article = $dom->createElement("article"); 
			$name = $dom->createElement("name", $value['name']); 
			$event = $dom->createElement("event", $value['event']); 
			$article->appendChild($name); 
			$article->appendChild($event); 
			$rootelement->appendChild($article); 
		} 
		$dom->appendChild($rootelement); 
		$filename = "rules/deploy.xml"; 
		$dom->save($filename);
		$this->success("修改完成！", spUrl("deploy","index"));
   }

}
