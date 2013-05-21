<?php

class linesinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('linesinfo'); 
		if(empty($filter) && empty($classid))
		{ 
		  $conditions = "releasedate >= '".date('Ymd',strtotime("-15 day"))."'";
		  $results = $user->findAll($conditions,'id DESC',NULL,"150"); 
		}
		elseif(!empty($classid))
		{
		  $conditions = array( 'cityid' => $classid );
		  $results = $user->findAll($conditions,"id desc");  // 分页		
		}
		else
		{
		  $conditions = 'title like '.$user->escape('%'.$filter.'%');
		  $results = $user->findAll($conditions,"id desc");  // 分页		
		}
		$i=0;
		while( $i <= count($results)-1)
	    {		  
		  $conditions = array( 'id' => $results[$i]['cityid'] );
		  $c_name = spClass('lib_cityinfo'); 
		  $c_result = $c_name->findAll($conditions); 
		  $results[$i]['cityid'] = $c_result[0]['citystate'];
		  $i++;
		}
		$this->results = $results; 
		// 列出信息内容
		
		$cataObj = spClass("lib_cityinfo");
		$this->resultclass = $cataObj->getCatalogSub();
		
		$cataObj = spClass("lib_cityinfo");
		$this->resultsub = $cataObj->getCatalogList();
		
		$rulesinfo = spClass('chain'); 
		$conditions = array( 'used' => 1 );
        $chainresult = $rulesinfo->findAll($conditions);  // 分页
		$this->chainresult = $chainresult; 
	
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_user = spClass('linesinfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->id = $id; 
		$this->id = $e_result[0]['id']; 
		$this->username = $e_result[0]['username'];
		$this->picurl = $e_result[0]['picurl'];
		$this->ispic = $e_result[0]['ispic'];
		$this->title = $e_result[0]['title']; 

		if(!empty($e_result[0]['releasedate'])){
		 $this->releasedate = date('Y-m-d H:i:s',$e_result[0]['releasedate']); 
		}		
		$this->content = $e_result[0]['content']; 
        $this->provinceid = $e_result[0]['provinceid']; 
		$this->cityid = $e_result[0]['cityid']; 
		$this->areaid = $e_result[0]['areaid']; 
		$this->address = $e_result[0]['address']; 
        $this->tickets = $e_result[0]['tickets']; 
		$this->phone = $e_result[0]['phone']; 
		$this->map = $e_result[0]['map']; 
		$this->tags = $e_result[0]['tags'];
        $this->clicknumber = $e_result[0]['clicknumber'];
        $this->ispass = $e_result[0]['ispass'];
        $this->tophot = $e_result[0]['tophot'];
        $this->ishot = $e_result[0]['ishot'];

        $rulesinfo = spClass('chain'); 
		$conditions = array( 'choose' => 1 );
        $chainresult = $rulesinfo->findAll($conditions);  // 分页

        $num=0;
		foreach ($chainresult as $value) {
			if(strpos("1".$this->tags,$value['keywords']) != 0)
			{
			  $chainresult[$num]['temp1'] = 1;
			}
			$num++;
		}

        $this->chainresult = $chainresult;

		$this -> display('admin/content/lines.html');
	}

	function show(){
		$this->id = $this->spArgs("id");
		$this->city = $this->spArgs("city");
		$this->tempcity = $this->spArgs("city");
		$this->info = "lines";
		$conditions = array( 'id' => $this->id);
		$s_lines = spClass('linesinfo'); 
		$this->show = $s_lines->find($conditions);

        // 根据路径参数获取城市标示信息.
		$conditions_cityinfo = array( 'another' => $this->city );
		$e_city = spClass('lib_cityinfo'); 
        $show_city = $e_city->find($conditions_cityinfo);

        // 根据城市及线路id获取出团信息.
        $conditions_soldout = array( 'cityid' => $show_city['id'],'linesid' => $this->id);
		$e_soldout = spClass('soldoutinfo'); 
        $this->show_soldout = $e_soldout->find($conditions_soldout); 
        //dump($this->show_soldout);
        $soldoutarray = json_decode(stripcslashes($this->show_soldout['details']));

        // 获取详细行程
		function unescape($str) { 
		  $ret = ''; 
		  $len = strlen ( $str ); 
		  for($i = 0; $i < $len; $i ++) { 
		    if ($str [$i] == '%' && $str [$i + 1] == 'u') { 
		      $val = hexdec ( substr ( $str, $i + 2, 4 ) ); 
		      if ($val < 0x7f) 
		        $ret .= chr ( $val ); 
		      else if ($val < 0x800) 
		        $ret .= chr ( 0xc0 | ($val >> 6) ) . chr ( 0x80 | ($val & 0x3f) ); 
		      else 
		        $ret .= chr ( 0xe0 | ($val >> 12) ) . chr ( 0x80 | (($val >> 6) & 0x3f) ) . chr ( 0x80 | ($val & 0x3f) ); 
		      $i += 5; 
		    } else if ($str [$i] == '%') { 
		      $ret .= urldecode ( substr ( $str, $i, 3 ) ); 
		      $i += 2; 
		    } else 
		      $ret .= $str [$i]; 
		  } 
		return $ret; 
	   }
        // 行程信息加工
        $circuit = array();
        foreach ($soldoutarray as $value) {
        	$psinfo = explode("|",$value);
        	$jxcontent = unescape($psinfo[1]);
        	preg_match_all('/id="time">(.*?)<\/td>/s', $jxcontent, $timeout);
        	preg_match_all('/id="detail">(.*?)<\/td>/s', $jxcontent, $contentout);
        	preg_match_all('/id="link">(.*?)<\/td>/s', $jxcontent, $viewpoint);

        	$tplcontent="";
        	$num=0;
        	foreach ($timeout[1] as $timevalue) {
        		$tplcontent = $tplcontent.'<div class="xlzy_tit06_l">'.$timevalue."</div><div class='xlzy_tit06_r'><div class='xlzy_tit06_r'>".$contentout[1][$num]."</div>";
        		$viewpoint[1][$num] = str_replace("<br />","",$viewpoint[1][$num]);
        		if($viewpoint[1][$num] != ''){
        		 $words = explode(",",$viewpoint[1][$num]);
                 foreach ($words as $wordsvalue) {
                 	// 关键字数据调取
                 	$conditions_word = array( 'title' => strip_tags($wordsvalue));
					$word_scenery = spClass('sceneryinfo'); 
					$this->word_show = $word_scenery->find($conditions_word);
					$title = $this->word_show["title"];
					$content = $this->word_show["content"];
					$picurl = $this->word_show["picurl"];
					// 关键字数据调取
					// datatpl
        		    $tplcontent = $tplcontent.'<div class="xlzy_tit06_r2 mar_t10">'.$title.'</div><div class="xlzy_tit06_r3" style="padding-bottom:10px;text-indent:1.5em;"><img style="float:right;margin-left:10px;" src="'.$picurl.'" width="167" height="115" />'.mb_substr($content, 0, 150, 'utf-8').'...</div><div id="tit02_r" class="tgreen" style="width:70%">显示全部 <img src="../../tpl/images/nlw_ic10.gif" width="7" height="4" /></div>';
        	      }
                  $tplcontent = $tplcontent.'</div>';
        	    }
        	    else
        	    {
        	    	$tplcontent = $tplcontent.'</div>';
        	    }
        		$num++;
        	}
        	$circuit[] = array("name" =>$psinfo[0],"content" =>$tplcontent);
        }
        $this->circuit = $circuit;
        //dump($this->sceneryinfo0);
        // 获取详细行程

		$s_seoinfo = spClass('lineseoinfo'); 
		$this->seo = $s_seoinfo->find($conditions);

		$sumnumber = $s_lines->find(null,'clicknumber DESC','clicknumber'); // 获取最高点击数
        $clicknumber = $this->show['clicknumber']; //获取本文章点击数
        $clicknumber++;
        $score = round($this->show['clicknumber']/$sumnumber['clicknumber'],2)*10; // 计算评分
        if($score == 10)
        {$score = 9.8;} // 满分降分

        if($this->show['ishot'] == 0) // 官方手动控制
        {
        	$scoredata = $score;
        }
        else
        {
        	$scoredata = $this->show['clicknumber'];
        }

        $s_lines->update($conditions,array('clicknumber'=>$clicknumber,'tophot'=>$scoredata));
        require('include/sideinfo.php'); // 侧边信息
        //推荐景点
		$conditions = array( 'id' => $this->id);
		$s_lines = spClass('linesinfo'); 
		$this->random_liness = $s_lines->findAll($conditions);
		//dump($this->random_liness);
		
		if($this->show == null)
		{
			$this->current_url = str_replace("lines","lblines",$this->current_url);
			$this->jump($this->current_url);
		}
		else
		{
	        require('include/dataing.php'); // 通用数据调取
			// 获取头像
            $userarray = array();
            foreach ($this->comment as $value)
			{
			  	$conditions = array( 'uid' => $value['userid'] );
				$e_user = spClass('lib_user'); 
		        $e_result = $e_user->find($conditions);
		        $userarray[] = $e_result['avatar'];
			}
            $this->userarray = $userarray;
            // 获取头像

			$conditions_reply = array( 'types' => 'lines','linkid' => $this->id,'ispass' => 0);
			$s_reply = spClass('replyinfo'); 
			$this->reply = $s_reply->findAll($conditions_reply);  
			
			$conditions_album = array( 'types' => 'lines','linkid' => $this->id,'ispass' => 0);
			$s_album = spClass('albuminfo'); 
			$this->album = $s_album->findAll($conditions_album); 
			// ===================================
			$this->types = 'lines';
			$this->linkid = $this->spArgs("id");

			$this->provinceid = $_SESSION["quser"]['provinceid'];
			$this->cityid = $_SESSION["quser"]['cityid']; 

			$this->username = $_SESSION["quser"]['realname'];
			$this->userid = $_SESSION["quser"]['uid'];
			$this -> display('content/lines.html');
	    }
	}

	function add(){
        require('include/checking.php'); // 用户身份校验
		$cataObj = spClass("lib_cityinfo");
		$this->resultclass = $cataObj->getCatalogSub();
		
		$cataObj = spClass("lib_cityinfo");
		$this->resultsub = $cataObj->getCatalogList();
		//dump($_SESSION["quser"]);
		$this->username = $_SESSION["quser"]['uname'];

		$rulesinfo = spClass('chain'); 
		$conditions = array( 'used' => 1 );
        $chainresult = $rulesinfo->findAll($conditions);  // 分页
		$this->chainresult = $chainresult;

		$this -> display('content/Clientfb/lines.html');
	}

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		if($data['releasedate'] == null)
		{ $releasedate = time(); }
		else
		{ $releasedate = strtotime($data['releasedate']); }
		$newrow = array( // PHP的数组
		'id'=>$data['id'],
		'username'=>$data['username'],
		'userid'=>$_SESSION["quser"]['uid'],
		'title'=>$data['title'],
		'picurl'=>"../tpl/images/pic15.jpg",
		'releasedate'=>$releasedate,
		'content'=>$data['content'],
		'provinceid'=>$data['provinceid'],
	    'cityid'=>$data['cityid'],
	    'areaid'=>$data['areaid'],
	    'ispic'=>0,
	    'ishot'=>0,
		'address'=>$data['address'],
		'tickets'=>$data['tickets'],
	    'phone'=>$data['phone'],
		'tags'=>$data['tags'],
		'tophot'=>$data['tophot']
		);
		$user = spClass('linesinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		$xml = simplexml_load_file('rules/integral.xml'); // 积分配置
		if($json == 'false')
		{
			$newid = $user->create($newrow);
            $json = json_encode($newid);
            // 记录历史
			$Historyrow = array( // PHP的数组
			'types'=>"lines",
			'title'=>$data['title'],
			'linkid'=>$newid,
			'userid'=>$_SESSION["quser"]['uid'],
			'integral'=>(int)$xml->lines,
			'releasedate'=>$releasedate
			);
			$syshistory = spClass('syshistory'); 
			$syshistory->create($Historyrow);
			$userhistory = spClass('userhistory'); 
			$userhistory->create($Historyrow);

			$conditions_lineseoinfo = array('stitle'=>"");
			$lineseoinfo = spClass('lineseoinfo'); 
			$lineseoinfo->create($conditions_lineseoinfo);

			$user = spClass('lib_user');

			$conditions = array('uid'=>$_SESSION["quser"]['uid']);
            $user_result = $user->find($conditions);
			$row = array('integral'=>$user_result["integral"] + (int)$xml->lines);
			$user->update($conditions, $row);  // 进行新增操作

			$lineseoinfo = spClass('lineseoinfo'); 
			$lineseoinfo->create($lineseoinfo);

		}  // 进行新增操作

		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'ispic'=>$data['ispic'],'title'=>$data['title'],'releasedate'=>$releasedate,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'address'=>$data['address'],'tickets'=>$data['tickets'],'phone'=>$data['phone'],'map'=>$data['map'],'tags'=>$data['tags'],'clicknumber'=>$data['clicknumber'],'ispass'=>$data['ispass'],'tophot'=>$data['tophot'],'ishot'=>$data['ishot']);
		$user = spClass('linesinfo');
		$json = json_encode($user->spVerifier($row));
		if($json == 'false'){$user->update($conditions, $row);} 
		echo($json);
	}
	
	function subclass(){
		$data = $this->spArgs(); // $data是提交上来的数据
		
		$conditions = array( 'pid'=>$data['id'] );
		$c_name = spClass('lib_cityinfo'); 
		$data['cityinfo'] = $c_name->findAll($conditions); 

		echo json_encode($data);
	}
	
	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
        $user = spClass('linesinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

	 //不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('linesinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('linesinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
