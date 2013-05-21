<?php

class sceneryinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('sceneryinfo'); 
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
	
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_user = spClass('sceneryinfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->uid = $id; 
		$this->id = $e_result[0]['id']; 
		$this->username = $e_result[0]['username'];
		$this->picurl = $e_result[0]['picurl'];
		$this->ispic = $e_result[0]['ispic'];
		$this->title = $e_result[0]['title']; 
		$this->ndly = $e_result[0]['ndly'];
	    $this->nearby = $e_result[0]['nearby'];

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
		
		$this -> display('admin/content/scenery.html');
	}
	
	function show(){
		$this->id = $this->spArgs("id");
		$this->info = "scenery";
		$conditions = array( 'id' => $this->id);
		$s_scenery = spClass('sceneryinfo'); 
		$show = $s_scenery->find($conditions);
        require('include/inside.php'); // 通用数据调取
        $show['content'] = inside($show['content']);
        $show['ndly'] = inside($show['ndly']);
        $nearby = explode("、",$show['nearby']);
        
        $kwname = array();
        $kwurl = array();
        foreach ($nearby as $value) {
        	$conditions_title = 'title like '.$s_scenery->escape('%'.$value.'%');
            $myscenery = $s_scenery->find($conditions_title,"id desc");  // 分页
	        $kwname[] = $value;
	        if(empty($myscenery['id']))
	        {
	        	$kwurl[] = $value;
	        }
	        else
	        {
	        	$kwurl[] = '<a target="_blank" href='.$myscenery['id'].'.html>'.$value.'</a>';
	        }
        }
        $show['nearby'] = implode("、",$kwurl);
		$this->show = $show;

        if($this->info == "scenery")
        {
		   $s_seoinfo = spClass('seoinfo');
		}
		else
		{
		   $s_seoinfo = spClass('lineseoinfo');
		} 
		$this->seo = $s_seoinfo->find($conditions);

		$sumnumber = $s_scenery->find(null,'clicknumber DESC','clicknumber'); // 获取最高点击数
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

        $s_scenery->update($conditions,array('clicknumber'=>$clicknumber,'tophot'=>$scoredata));
        require('include/sideinfo.php'); // 侧边信息
        //推荐景点
		$conditions = array( 'cityid' => $this->showscenery['cityid']);
		$s_scenery = spClass('sceneryinfo'); 
		$this->random_scenerys = $s_scenery->findAll($conditions,'id DESC',NULL,"5");
		//dump($this->random_scenerys);
		
		if($this->show == null)
		{
			import(APP_PATH.'/tpl/404.php');exit();
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

			$conditions_reply = array( 'types' => 'scenery','linkid' => $this->id,'ispass' => 0);
			$s_reply = spClass('replyinfo'); 
			$this->reply = $s_reply->findAll($conditions_reply);  
			
			$conditions_album = array( 'types' => 'scenery','linkid' => $this->id,'ispass' => 0);
			$s_album = spClass('albuminfo'); 
			$this->album = $s_album->findAll($conditions_album); 
			// ===================================
			$this->types = 'scenery';
			$this->linkid = $this->spArgs("id");

			$this->provinceid = $_SESSION["quser"]['provinceid'];
			$this->cityid = $_SESSION["quser"]['cityid']; 

			$this->username = $_SESSION["quser"]['realname'];
			$this->userid = $_SESSION["quser"]['uid'];

			$conditions = array( 'types'=>'scenery','linkid'=>$this->id );
			$wantgo = spClass('wantgo');
		    $this->wantgosum = $wantgo->findCount($conditions);

		    $conditions = array( 'types'=>'scenery','linkid'=>$this->id );
			$aleadygo = spClass('aleadygo');
		    $this->aleadygosum = $aleadygo->findCount($conditions);
			
			$this -> display('content/scenery.html');
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

		$this -> display('content/Clientfb/scenery.html');
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
		'ndly'=>$data['ndly'],
		'nearby'=>$data['nearby'],
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
		$user = spClass('sceneryinfo'); 

		$conditions = 'title like '.$user->escape('%'.$data['title'].'%');
		$repeat = $user->findAll($conditions);  // 分页	
        if(empty($repeat)){
			$json = json_encode($user->spVerifier($newrow));
			$xml = simplexml_load_file('rules/integral.xml'); // 积分配置
			if($json == 'false')
			{
				$newid = $user->create($newrow);
	            $json = json_encode($newid);
	            // 记录历史
				$Historyrow = array( // PHP的数组
				'types'=>"scenery",
				'title'=>$data['title'],
				'linkid'=>$newid,
				'userid'=>$_SESSION["quser"]['uid'],
				'integral'=>(int)$xml->scenery,
				'releasedate'=>$releasedate
				);
				$syshistory = spClass('syshistory'); 
				$syshistory->create($Historyrow);
				$userhistory = spClass('userhistory'); 
				$userhistory->create($Historyrow);

				$conditions_seoinfo = array('stitle'=>"");
				$seoinfo = spClass('seoinfo'); 
				$seoinfo->create($conditions_seoinfo);

				$user = spClass('lib_user');

				$conditions = array('uid'=>$_SESSION["quser"]['uid']);
	            $user_result = $user->find($conditions);
				$row = array('integral'=>$user_result["integral"] + (int)$xml->scenery);
				$user->update($conditions, $row);  // 进行新增操作
			}  // 进行新增操作
	    }
	    else
	    { $json = "repeat"; }
		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'ispic'=>$data['ispic'],'title'=>$data['title'],'ndly'=>$data['ndly'],'nearby'=>$data['nearby'],'releasedate'=>$releasedate,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'address'=>$data['address'],'tickets'=>$data['tickets'],'phone'=>$data['phone'],'map'=>$data['map'],'tags'=>$data['tags'],'clicknumber'=>$data['clicknumber'],'ispass'=>$data['ispass'],'tophot'=>$data['tophot'],'ishot'=>$data['ishot']);
		$user = spClass('sceneryinfo');
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
        $user = spClass('sceneryinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

	 //不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('sceneryinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('sceneryinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
