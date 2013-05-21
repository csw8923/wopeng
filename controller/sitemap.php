<?php	
header("Content-Type:text/html;charset=utf-8");
class sitemap extends basis
{
    function index()
    {
        $this -> display('sitemap.html');
    }

	function scenery(){
		$this->title = "景点数据";
		$this->type = $this->spArgs("type");
	    $content = array();
	    $contentyouji = array();
	    $contentcomment = array();   
	    $contentphotoalbum = array();   
	    $contentvisits = array();   
	    $contentask = array();   

		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $sceneryinfo = spClass('sceneryinfo'); 
	    $result = $sceneryinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
	       $showcontent[] = "<li><a href=".$this->domain."/scenery/".$result[$j]['id'].">".$result[$j]['title']."</a></li>";
		   $content[] = "<url>\n<loc>".$this->domain."/scenery/".$result[$j]['id'].".html</loc>\n</url>";
		   $contentyouji[] = "<url>\n<loc>".$this->domain."/youji/scenery/".$result[$j]['id'].".html</loc>\n</url>";
		   $contentcomment[] = "<url>\n<loc>".$this->domain."/comment/scenery/".$result[$j]['id'].".html</loc>\n</url>";
		   $contentphotoalbum[] = "<url>\n<loc>".$this->domain."/photoalbum.html?info=scenery&pid=/".$result[$j]['id'].".html</loc>\n</url>";
		   $contentvisits[] = "<url>\n<loc>".$this->domain."/visits/scenery/".$result[$j]['id'].".html</loc>\n</url>";
		   $contentask[] = "<url>\n<loc>".$this->domain."/ask/scenery/".$result[$j]['id'].".html</loc>\n</url>";

		   $j++;
	    }
	    
		$str_arr = implode("\n",$content);
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/scenery.xml",$sitemap);

		$str_arryouji = implode("\n",$contentyouji);
		$sitemapyouji = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemapyouji = $sitemapyouji."<urlset\n";
		$sitemapyouji = $sitemapyouji."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemapyouji = $sitemapyouji."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemapyouji = $sitemapyouji."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemapyouji = $sitemapyouji."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemapyouji = $sitemapyouji.$str_arryouji;
		$sitemapyouji = $sitemapyouji."</urlset>\n";
		file_put_contents("xml/youji_list.xml",$sitemapyouji);


        $str_arrcomment = implode("\n",$contentcomment);
		$sitemapcomment = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemapcomment = $sitemapcomment."<urlset\n";
		$sitemapcomment = $sitemapcomment."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemapcomment = $sitemapcomment."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemapcomment = $sitemapcomment."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemapcomment = $sitemapcomment."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemapcomment = $sitemapcomment.$str_arrcomment;
		$sitemapcomment = $sitemapcomment."</urlset>\n";
		file_put_contents("xml/comment_list.xml",$sitemapcomment);

		$str_arrphotoalbum = implode("\n",$contentphotoalbum);
		$sitemapphotoalbum = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemapphotoalbum = $sitemapphotoalbum."<urlset\n";
		$sitemapphotoalbum = $sitemapphotoalbum."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemapphotoalbum = $sitemapphotoalbum."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemapphotoalbum = $sitemapphotoalbum."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemapphotoalbum = $sitemapphotoalbum."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemapphotoalbum = $sitemapphotoalbum.$str_arrphotoalbum;
		$sitemapphotoalbum = $sitemapphotoalbum."</urlset>\n";
		file_put_contents("xml/photoalbum_list.xml",$sitemapphotoalbum);

		$str_arrvisits = implode("\n",$contentvisits);
		$sitemapvisits = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemapvisits = $sitemapvisits."<urlset\n";
		$sitemapvisits = $sitemapvisits."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemapvisits = $sitemapvisits."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemapvisits = $sitemapvisits."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemapvisits = $sitemapvisits."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemapvisits = $sitemapvisits.$str_arrvisits;
		file_put_contents("xml/visits_list.xml",$sitemapvisits);

		$str_arrask = implode("\n",$contentask);
		$sitemapask = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemapask = $sitemapask."<urlset\n";
		$sitemapask = $sitemapask."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemapask= $sitemapask."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemapask = $sitemapask."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemapask = $sitemapask."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemapask = $sitemapask.$str_arrask;
		file_put_contents("xml/ask_list.xml",$sitemapask);
        $this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
	}

	function lines(){
		$this->title = "线路数据";
	    $content = array(); 
		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $linesinfo = spClass('linesinfo'); 
	    $result = $linesinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
	       $conditions_city = array( 'id' => $result[$j]['cityid'] );
		   $t_city = spClass('lib_cityinfo'); 
		   $city = $t_city->find($conditions_city);
		   $content[] = "<url>\n<loc>".$this->domain."/lines/".$city['another']."/".$result[$j]['id'].".html</loc>\n</url>";
		   $showcontent[] = "<li><a href=".$this->domain."/lines/".$city['another']."/".$result[$j]['id'].".html>".$result[$j]['title']."</a></li>";
		   $j++;
	    }

		$str_arr = implode("\n",$content);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/lines.xml",$sitemap);
		$this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
		
	}

	function youji(){
		$this->title = "游记数据";
	    $content = array(); 
		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $youjiinfo = spClass('youjiinfo'); 
	    $result = $youjiinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
		   $content[] = "<url>\n<loc>".$this->domain."/youji/info/".$result[$j]['id'].".html</loc>\n</url>";
		   $showcontent[] = "<li><a href=".$this->domain."/youji/info/".$result[$j]['id'].".html>".$result[$j]['title']."</a></li>";
		   $j++;
	    }

		$str_arr = implode("\n",$content);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/youji.xml",$sitemap);
		$this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
		
	}

	function comment(){
		$this->title = "点评数据";
	    $content = array(); 
		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $commentinfo = spClass('commentinfo'); 
	    $result = $commentinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
		   $content[] = "<url>\n<loc>".$this->domain."/comment/info/".$result[$j]['id'].".html</loc>\n</url>";
		   $showcontent[] = "<li><a href=".$this->domain."/comment/info/".$result[$j]['id'].">".$result[$j]['id']."</a></li>";
		   $j++;
	    }

		$str_arr = implode("\n",$content);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/comment.xml",$sitemap);
		$this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
		
	}

	function visits(){
		$this->title = "自由行线路数据";
	    $content = array(); 
		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $visitsinfo = spClass('visitsinfo'); 
	    $result = $visitsinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
		   $content[] = "<url>\n<loc>".$this->domain."/visits/info/".$result[$j]['id'].".html</loc>\n</url>";
		   $showcontent[] = "<li><a href=".$this->domain."/visits/info/".$result[$j]['id'].".html>".$result[$j]['title']."</a></li>";
		   $j++;
	    }

		$str_arr = implode("\n",$content);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/visits.xml",$sitemap);
		$this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
	}

	function answers(){
		$this->title = "论坛数据";
	    $content = array(); 
		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $answersinfo = spClass('answersinfo'); 
	    $result = $answersinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
		   $content[] = "<url>\n<loc>".$this->domain."/answers/info/".$result[$j]['id'].".html</loc>\n</url>";
		   $showcontent[] = "<li><a href=".$this->domain."/answers/info/".$result[$j]['id'].".html>".$result[$j]['title']."</a></li>";
		   $j++;
	    }

		$str_arr = implode("\n",$content);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/answers.xml",$sitemap);
		$this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
	}

	function photo(){
		$this->title = "图片数据";
	    $content = array(); 
		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $pictureinfo = spClass('pictureinfo'); 
	    $result = $pictureinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
		   $content[] = "<url>\n<loc>".$this->domain."/photo/".$result[$j]['id']."</loc>\n</url>";
		   $showcontent[] = "<li><a href=".$this->domain."/photo/".$result[$j]['id'].">".$result[$j]['title']."</a></li>";
		   $j++;
	    }

		$str_arr = implode("\n",$content);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/photo.xml",$sitemap);
		$this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
	}

	function activity(){
		$this->title = "活动数据";
	    $content = array(); 
		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $activityinfo = spClass('activityinfo'); 
	    $result = $activityinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
	       $conditions_city = array( 'id' => $result[$j]['cityid'] );
		   $t_city = spClass('lib_cityinfo'); 
		   $city = $t_city->find($conditions_city);
		   $content[] = "<url>\n<loc>".$this->domain."/activity/".$city['another']."/".$result[$j]['id'].".html</loc>\n</url>";
		   $showcontent[] = "<li><a href=".$this->domain."/activity/".$city['another']."/".$result[$j]['id'].".html>".$result[$j]['title']."</a></li>";
		   $j++;
	    }

		$str_arr = implode("\n",$content);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/activity.xml",$sitemap);
		$this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
	}

	function listbd(){
		$this->title = "榜单数据";
	    $content = array(); 
		$timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
		$timebefore = strtotime(date('Y-m-d 00:00:00'));
		$conditions= "releasedate >=". $timebefore30 ." AND releasedate <= ".$timebefore;
	    $listbdinfo = spClass('listbdinfo'); 
	    $result = $listbdinfo->findAll($conditions,'id DESC'); // 查找
	    $j=0;
	    while($j<=count($result)-1)
	    {
	       $conditions_city = array( 'id' => $result[$j]['cityid'] );
		   $t_city = spClass('lib_cityinfo'); 
		   $city = $t_city->find($conditions_city);
		   $content[] = "<url>\n<loc>".$this->domain."/listbd/".$city['another']."/".$result[$j]['id'].".html</loc>\n</url>";
		   $showcontent[] = "<li><a href=".$this->domain."/listbd/".$city['another']."/".$result[$j]['id'].".html>".$result[$j]['title']."</a></li>";
		   $j++;
	    }

		$str_arr = implode("\n",$content);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap = $sitemap."<urlset\n";
		$sitemap = $sitemap."\t\t\txmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap = $sitemap."\t\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap = $sitemap."\t\t\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap = $sitemap."\t\t\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap = $sitemap.$str_arr;
		$sitemap = $sitemap."</urlset>\n";
		file_put_contents("xml/listbd.xml",$sitemap);
		$this->showcontent = $showcontent;
		$this -> display('sitemap/show.html');
	}	

}
?>