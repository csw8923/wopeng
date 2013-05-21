<?php
class newsword extends basis
{
	// 首页
	public function index(){
	 		  // 筛选热门新闻关键字
		  $wordmd5 = file_get_contents("hotinfo/wordmd5.txt");
		  $md5file = md5_file("http://top.baidu.com/rss_xml.php?p=top10");
		  // 上面根据获取源地址MD5数据来判断是否更新
		  if($wordmd5 != $md5file)
		  {
			  file_put_contents("hotinfo/wordmd5.txt",$md5file);

			  $geturl = "http://top.baidu.com/rss_xml.php?p=top10";
			  $ch = curl_init();  
			  $data = array('name' => '262055400');
			  curl_setopt($ch, CURLOPT_URL, $geturl);  
			  //curl_setopt($ch, CURLOPT_HEADER,false);  
			  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出
			  curl_setopt($ch, CURLOPT_POST, 1);  
			  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
			  curl_setopt($ch, CURLOPT_HEADER, 0);  
			  $hotwords = curl_exec($ch);  
			  curl_close($ch);
			  $coding = mb_detect_encoding($hotwords, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
			  if ($coding == 'UTF-8')
			  {}
			  else
			  { $hotwords = mb_convert_encoding($hotwords, "UTF-8", "GBK"); }
			  preg_match_all('/<a.*?(?: |\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>(.+?)<\/a.*?>/sim',$hotwords,$mas);
			  $newkeyword = array_unique($mas[2]);
			  $keywordarray = implode(",",$newkeyword);
			  // 上面截取数据，并组合关键字。
			  $patterns = array();
			  $patterns[0] = '/新闻,/s';
			  $patterns[1] = '/贴吧,/s';
			  $patterns[2] = '/视频,/s';
			  $replacements = array();
			  $replacements[0] = '';
			  $replacements[1] = '';
			  $replacements[2] = '';
			  $keywordarray = preg_replace($patterns, $replacements,$keywordarray);
			  $keywordarray = str_replace(" ",",",$keywordarray);
			  $findkeyword = explode(",",$keywordarray);
			  $top_keyword = array();
			  $emailne = array();
			  $saveids = array();
			  
			  $ids = file_get_contents("hotinfo/ids.txt");
			  foreach ($findkeyword as $value)
			  {
				 //$condition = array('title'=> $value );
				 $condition = " title like '%".$value."%'";
				 $findinfo = spClass('messageinfo'); 
				 $find_result = $findinfo->find($condition);
				 if(!empty($find_result))
				 {
					 $top_keyword[] = $value;
					 if(strpos($ids,$find_result['id']) == 0)
					 {
						 $emailne[] = array('title'=>$find_result['title'],'id'=>$find_result['id']);
						 $saveids[] = $find_result['id'];
					 }
					 else
					 {//echo strpos($ids,$find_result['id']);
					 }
				 }		 
			  }
			  // 比较关键字
			  $keyword_data = implode("|",$top_keyword);
			  file_put_contents("hotinfo/keyword.txt",$keyword_data);
//	          dump($saveids);
			  if(!empty($saveids))
			  {
				  // 当发送 HTML 电子邮件时，请始终设置 content-type
				  $detail="";
				  $mobile="";
				  foreach ($emailne as $values)
				  {
					 $detail = $detail."<a target='_blank' href='http://www.xmoline.com/article/".$values['id'].".html'>".$values['title']."</a><br />"; 
					 $mobile = $mobile."\n".$values['title']."\n"."http://www.xmoline.com/article/".$values['id']."\n"; 
				  }
				  $detail = $detail. date("Y-m-d h:i:s");
				  $mobile = $mobile. date("Y-m-d h:i:s");
				  $headers = "MIME-Version: 1.0" . "\r\n";
				  $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
				  $headers .= 'From: <csw8923@126.com>' . "\r\n";
				  //$headers .= 'Cc: myboss@example.com' . "\r\n";
				  mail("525744707@qq.com;csw8923@qq.com","厦门在线新闻网新闻快讯",$detail,$headers);
				  // 当发送 HTML 电子邮件时，请始终设置 content-type
				  require 'PHPFetion.php';
				  $fetion = new PHPFetion('15959239970', 'CSW520Lss23');	// 手机号、飞信密码
				  $fetion->send('15959239970',$mobile);	// 接收人手机号、飞信内容
				  $fetion->send('15160081689',$mobile);	// 接收人手机号、飞信内容
				  
				  $ids_data = implode(",",array_unique($saveids));
				  $file = fopen("hotinfo/ids.txt","a+");
				  fwrite($file,','.$ids_data);
				  fclose($file);
			  }
			  // 发布邮箱和短信信息
		  }
		  else
		  {
		  }

	}

}	
