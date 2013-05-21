<?php

class activityinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('activityinfo'); 
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
		$e_user = spClass('activityinfo'); 
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

		if(!empty($e_result[0]['endtime'])){
		 $this->endtime = date('Y-m-d H:i:s',$e_result[0]['endtime']); 
		}	

		$this->content = $e_result[0]['content']; 
        $this->provinceid = $e_result[0]['provinceid']; 
		$this->cityid = $e_result[0]['cityid']; 
		$this->areaid = $e_result[0]['areaid']; 
		$this->address = $e_result[0]['address']; 
        $this->tickets = $e_result[0]['tickets']; 
        $this->instation = $e_result[0]['instation']; 
        $this->childprices = $e_result[0]['childprices']; 
		$this->phone = $e_result[0]['phone']; 
		$this->map = $e_result[0]['map']; 
		$this->tags = $e_result[0]['tags'];
        $this->clicknumber = $e_result[0]['clicknumber'];
        $this->ispass = $e_result[0]['ispass'];
        $this->tophot = $e_result[0]['tophot'];
        $this->ishot = $e_result[0]['ishot'];
		$this -> display('admin/content/activity.html');
	}

	function show(){
		$this->id = $this->spArgs("id");
		$this->city = $this->spArgs("city");
		$this->tempcity = $this->spArgs("city");
		$this->info = "activity";
		$conditions = array( 'id' => $this->id);
		$s_lines = spClass('activityinfo'); 
		$this->show = $s_lines->find($conditions);

        // 根据路径参数获取城市标示信息.
		$conditions_cityinfo = array( 'another' => $this->city );
		$e_city = spClass('lib_cityinfo'); 
        $show_city = $e_city->find($conditions_cityinfo);

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
		$s_lines = spClass('activityinfo'); 
		$this->random_liness = $s_lines->findAll($conditions);
		//dump($this->random_liness);

		// 省
		$conditions_province = array( 'id' => $this->random_liness[0]['provinceid'] );
		$t_province = spClass('lib_cityinfo'); 
		$this->province = $t_province->find($conditions_province); 
	    // 市
		$conditions_city = array( 'id' => $this->random_liness[0]['cityid'] );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);
	    // 区
		$conditions_area = array( 'id' => $this->random_liness[0]['areaid'] );
		$t_area = spClass('lib_cityinfo'); 
		$this->area = $t_area->find($conditions_area); 
		
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
			$this -> display('content/activity.html');
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

		$timestamp = time();
		$this->timestamp = $timestamp;
		$this->token = md5('unique_salt' . $timestamp);
		$this->types = 'add';

		$this -> display('content/Clientfb/activity.html');
	}

	function edit(){
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

		$timestamp = time();
		$this->timestamp = $timestamp;
		$this->token = md5('unique_salt' . $timestamp);

        $this->id = $this->spArgs("id");
		$conditions = array( 'id' => $this->id);
		$s_activity = spClass('activityinfo'); 
		$this->activity = $s_activity->find($conditions);
		//dump($this->activity);
		$this->types = 'edit';

		$this -> display('content/Clientfb/activity.html');
	}

	function addactivity(){
		$data = $this->spArgs();
        $user = spClass('activityinfo'); 
	    $conditions = 'title like '.$user->escape('%'.$data['title'].'%');
	    $result_data = $user->find($conditions,"id desc");  // 分页

	    if(empty($data["title"]))
	    {
	    	$this->error("标题不能为空");
	    }

	    if(empty($result_data["title"]))
	    {
           		if($data['releasedate'] == null)
				{ $releasedate = time(); }
				else
				{ $releasedate = strtotime($data['releasedate']); }
				if($data['endtime'] == null)
				{ $endtime = time(); }
				else
				{ $endtime = strtotime($data['endtime']); }

				$newrow = array( // PHP的数组
				'id'=>$data['id'],
				'username'=>$data['username'],
				'userid'=>$_SESSION["quser"]['uid'],
				'title'=>$data['title'],
				'picurl'=>$data['picurl'],
				'releasedate'=>$releasedate,
				'endtime'=>$endtime,
				'content'=>$data['content'],
				'instation'=>$data['instation'],
				'provinceid'=>$data['provinceid'],
			    'cityid'=>$data['cityid'],
			    'areaid'=>$data['areaid'],
			    'tickets'=>$data['tickets'],
			    'childprices'=>$data['childprices'],
			    'ispic'=>0,
			    'ishot'=>0,
				'address'=>$data['address'],
			    'phone'=>$data['phone']
				);
				$user = spClass('activityinfo'); 
				$newid = $user->create($newrow);
			    if(empty($newid))
			    { 
		           $this->error("发布失败");
			    }
			    else
			    {
			    	$this->success("发布成功",$this->$domain."/activity/xm/".$newid.".htm");
			    }
	    }
	    else
	    {
		    	$this->error("你发起的活动有重复了哦");
	    }	

	}

	function editactivity(){
		$data = $this->spArgs();

    	$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$endtime = strtotime($data['endtime']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'instation'=>$data['instation'],'title'=>$data['title'],'releasedate'=>$releasedate,'endtime'=>$endtime,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'address'=>$data['address'],'tickets'=>$data['tickets'],'childprices'=>$data['childprices'],'phone'=>$data['phone']);
		$user = spClass('activityinfo');
		$newid = $user->update($conditions, $row);
		if($newid == 1)
		{
		   $this->success("编辑成功", spUrl("ucenter","UserActivity"));
		}
		else
		{
			$this->success("编辑失败");
		}
	}

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		if($data['releasedate'] == null)
		{ $releasedate = time(); }
		else
		{ $releasedate = strtotime($data['releasedate']); }
		if($data['endtime'] == null)
		{ $endtime = time(); }
		else
		{ $endtime = strtotime($data['endtime']); }
		$newrow = array( // PHP的数组
		'id'=>$data['id'],
		'username'=>$data['username'],
		'userid'=>$_SESSION["quser"]['uid'],
		'title'=>$data['title'],
		'picurl'=>"../tpl/images/pic15.jpg",
		'instation'=>$data['instation'],
		'releasedate'=>$releasedate,
		'endtime'=>$endtime,
		'content'=>$data['content'],
		'provinceid'=>$data['provinceid'],
	    'cityid'=>$data['cityid'],
	    'areaid'=>$data['areaid'],
	    'ispic'=>0,
	    'ishot'=>0,
		'address'=>$data['address'],
		'tickets'=>$data['tickets'],
		'childprices'=>$data['childprices'],
	    'phone'=>$data['phone'],
		'tags'=>$data['tags'],
		'tophot'=>$data['tophot']
		);
		$user = spClass('activityinfo'); 
		$json = json_encode($user->spVerifier($newrow));
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
			'integral'=>6,
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
			$row = array('integral'=>$user_result["integral"] + 6);
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
		$endtime = strtotime($data['endtime']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'instation'=>$data['instation'],'ispic'=>$data['ispic'],'title'=>$data['title'],'releasedate'=>$releasedate,'endtime'=>$endtime,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'address'=>$data['address'],'tickets'=>$data['tickets'],'childprices'=>$data['childprices'],'phone'=>$data['phone'],'map'=>$data['map'],'tags'=>$data['tags'],'clicknumber'=>$data['clicknumber'],'ispass'=>$data['ispass'],'tophot'=>$data['tophot'],'ishot'=>$data['ishot']);
		$user = spClass('activityinfo');
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
        $user = spClass('activityinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

	 //不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('activityinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('activityinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }

    	 //不进行二级推荐
    function nopush(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispush'=>0);
        $rulesinfo = spClass('activityinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yespush(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispush'=>1);
        $rulesinfo = spClass('activityinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
