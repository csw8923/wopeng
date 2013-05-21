<?php
require_once("email/class.mail.php");
class ucenter extends basis
{
   
	function index(){
         require('include/checking.php'); // 用户身份校验
        // 最新景点
		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$sceneryinfo = spClass('sceneryinfo');
		$this->scenerys = $sceneryinfo->findAll($conditions,'id DESC',NULL,"8");
		$this->scenerysnum = count($this->scenerys);
        $i=0;
		while( $i <= count($this->scenerys)-1)
		{		
		 $provinceid = $this->scenerys[$i]['provinceid'];  
		 $cityid = $this->scenerys[$i]['cityid'];
		 $areaid = $this->scenerys[$i]['areaid'];

		 $cityinfo = spClass('lib_cityinfo');

		 $conditions = array( 'id' => $provinceid );
		 $provinces[] = $cityinfo->find($conditions);

		 $conditions = array( 'id' => $cityid );
		 $citys[] = $cityinfo->find($conditions);

		 $conditions = array( 'id' => $areaid );
		 $areas[] = $cityinfo->find($conditions);

		  $i++;
		}

		$this->provinces = $provinces;
		$this->citys = $citys;
		$this->areas = $areas;

        // 最新图片
		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('pictureinfo'); 
        $this->pictureinfo = $e_user->findAll($conditions,'id DESC',NULL,"8");
        $this->pictureinfonum = count($this->pictureinfo);
        $scenerys_pic = array();
        $i=0;
		while( $i <= count($this->pictureinfo)-1)
		{		  
		  $linkid = $this->pictureinfo[$i]['linkid'];
		  $conditions = array( 'id' => $linkid );
		  $sceneryinfo = spClass('sceneryinfo');
		  $scenerys_pic[] = $sceneryinfo->find($conditions);
		  $i++;
		}
		$this->scenerys_pic = $scenerys_pic;

        // 用户信息
		$id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->lasttime = $e_result["lasttime"];

        // 想去的景点
		$conditions_swantgo = array('uid'=>$_SESSION["quser"]['uid']);
	    $wantgo = spClass('wantgo');
	    $this->wantgo_scenery = $wantgo->findAll($conditions_swantgo);
	    $this->wantgo_scenerynum = count($this->wantgo_scenery);
        $sceneryshow_w_s = array();
        $types_w_s = array();
		foreach($this->wantgo_scenery as $value){ 
			$conditions_w_s = array('id' => $value['linkid']);
            $sceneryinfo_w_s = spClass('sceneryinfo');
			$sceneryshow_w_s[] = $sceneryinfo_w_s->find($conditions_w_s);
			$types_w_s[] = $value['types'];
		}
		$this->sceneryshow_w_s = $sceneryshow_w_s;
		$this->types_w_s = $types_w_s;
        $i=0;
		while( $i <= count($this->sceneryshow_w_s)-1)
		{		
		 $provinceid = $this->sceneryshow_w_s[$i]['provinceid'];  
		 $cityid = $this->sceneryshow_w_s[$i]['cityid'];
		 $areaid = $this->sceneryshow_w_s[$i]['areaid'];
		 $cityinfo = spClass('lib_cityinfo');
		 $conditions_w_s = array( 'id' => $provinceid );
		 $provinces[] = $cityinfo->find($conditions_w_s);
		 $conditions_w_s = array( 'id' => $cityid );
		 $citys[] = $cityinfo->find($conditions_w_s);
		 $conditions_w_s = array( 'id' => $areaid );
		 $areas[] = $cityinfo->find($conditions_w_s);
		 $i++;
		}
		$this->provinces_w_s = $provinces;
		$this->citys_w_s = $citys;
		$this->areas_w_s = $areas;

		// 去过的景点
		$conditions_saleadygo= array('uid'=>$_SESSION["quser"]['uid']);
	    $aleadygo = spClass('aleadygo');
	    $this->aleadygo_scenery = $aleadygo->findAll($conditions_saleadygo);
	    $this->aleadygo_scenerynum = count($this->aleadygo_scenery);
        $sceneryshow_a_s = array();
        $types_a_s = array();
		foreach($this->aleadygo_scenery as $value){ 
			$conditions_a_s = array('id' => $value['linkid']);
            $sceneryinfo_a_s = spClass('sceneryinfo');
			$sceneryshow_a_s[] = $sceneryinfo_a_s->find($conditions_a_s);
			$types_a_s[] = $value['types'];
		}
		$this->sceneryshow_a_s = $sceneryshow_a_s;
		$this->types_a_s = $types_a_s;
        $i=0;
		while( $i <= count($this->sceneryshow_a_s)-1)
		{		
		 $provinceid = $this->sceneryshow_a_s[$i]['provinceid'];  
		 $cityid = $this->sceneryshow_a_s[$i]['cityid'];
		 $areaid = $this->sceneryshow_a_s[$i]['areaid'];
		 $cityinfo = spClass('lib_cityinfo');
		 $conditions_a_s = array( 'id' => $provinceid );
		 $provinces[] = $cityinfo->find($conditions_w_s);
		 $conditions_a_s = array( 'id' => $cityid );
		 $citys[] = $cityinfo->find($conditions_w_s);
		 $conditions_a_s = array( 'id' => $areaid );
		 $areas[] = $cityinfo->find($conditions_w_s);
		 $i++;
		}
		$this->provinces_a_s = $provinces;
		$this->citys_a_s = $citys;
		$this->areas_a_s = $areas;

        // 登入打卡
		$currenttime = time();
		$currentdate = date('Y-m-d',$currenttime);
		$logdate = date('Y-m-d',$this->lasttime);
		if($currentdate != $logdate)
		{
			$this->rtime = '1';
			// 加分数
			$xml = simplexml_load_file('rules/integral.xml'); // 积分配置
			$user = spClass('lib_user');
			$user_result = $user->find($conditions);
			$conditions = array('uid'=>$_SESSION["quser"]['uid']); 
			$row = array('integral'=>$user_result["integral"] + (int)$xml->login);
			$user->update($conditions, $row);  // 进行新增操作
		}
		$row = array('lasttime' => $currenttime);
		$e_userinfo->update($conditions,$row);
        // 登入打卡

		$this->avatar = str_replace(".jpg","",$e_result['avatar']);

		// if($this->avatar == "../tpl/images/pic14")
		// {
		// 	$this->error("你还没有上传一个新头像哦!上传一个属于你的头像吧...", spUrl("ucenter","UserGuide"));
		// }

		$this -> display('user/UserIndex.html');
	}

    // 用户资料
    function UserData(){
    	require('include/checking.php'); // 用户身份校验

		$lib_user = spClass('lib_user'); 
        $result = $lib_user->findAll($conditions); 
		$this->result = $result; 
		$id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result['uname']; 
		$this->upass = $e_result['upass']; 
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->realname = $e_result['realname'];
		$this->Phone = $e_result['Phone']; 
		$this->QQ = $e_result['QQ']; 
		$this->avatar = str_replace(".jpg","",$e_result['avatar']);
		$this->Email = $e_result['Email']; 
		$this->Address = $e_result['Address']; 
	    $this->provinceid = $e_result['provinceid']; 
		$this->cityid = $e_result['cityid']; 
		$this->areaid = $e_result['areaid']; 

		$cataObj = spClass("lib_cityinfo");
		$this->resultclass = $cataObj->getCatalogSub();

		$cataObj = spClass("lib_cityinfo");
		$this->resultsub = $cataObj->getCatalogList();

		$this -> display('user/UserData.html');
	}

	 // 用户向导
    function UserGuide(){
    	require('include/checking.php'); // 用户身份校验
		$lib_user = spClass('lib_user'); 
        $result = $lib_user->findAll($conditions); 
		$this->result = $result; 
		$id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result['uname']; 
		$this->upass = $e_result['upass']; 
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->realname = $e_result['realname'];
		$this->Phone = $e_result['Phone']; 
		$this->QQ = $e_result['QQ']; 
		$this->avatar = str_replace(".jpg","",$e_result['avatar']);
		$this->Email = $e_result['Email']; 
		$this->Address = $e_result['Address']; 
	    $this->provinceid = $e_result['provinceid']; 
		$this->cityid = $e_result['cityid']; 
		$this->areaid = $e_result['areaid']; 

		$cataObj = spClass("lib_cityinfo");
		$this->resultclass = $cataObj->getCatalogSub();

		$cataObj = spClass("lib_cityinfo");
		$this->resultsub = $cataObj->getCatalogList();

		$timestamp = time();
		$this->timestamp = $timestamp;
		$this->token = md5('unique_salt' . $timestamp);
        
        $this->srcurl = $e_result['avatar'];
        $src = explode("/", $this->srcurl);
        $this->srcout = $src[count($src)-1];

        $this->action = $this->spArgs("action");
		$this -> display('user/UserGuide.html');
	}

    // 用户密码
    function UserPass(){
    	require('include/checking.php'); // 用户身份校验

		$lib_user = spClass('lib_user'); 
        $result = $lib_user->findAll($conditions); 
		$this->result = $result; 
		$id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result['uname']; 
		$this->upass = $e_result['upass']; 
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->realname = $e_result['realname'];
		$this->Phone = $e_result['Phone']; 
		$this->QQ = $e_result['QQ']; 
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 
		$this->Email = $e_result['Email']; 
		$this->Address = $e_result['Address']; 
	    $this->provinceid = $e_result['provinceid']; 
		$this->cityid = $e_result['cityid']; 
		$this->areaid = $e_result['areaid']; 

		$cataObj = spClass("lib_cityinfo");
		$this->resultclass = $cataObj->getCatalogSub();

		$cataObj = spClass("lib_cityinfo");
		$this->resultsub = $cataObj->getCatalogList();

		$this -> display('user/UserPass.html');
	}

	  // 用户中心_游记
	function UserPayment(){
        require('include/checking.php'); // 用户身份校验

        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('payment'); 
        $payment = $e_user->spPager($this->spArgs('page', 1), 15)->findAll($conditions,'id DESC');
        $this->pager = $e_user->spPager()->getPager();

        $paycitys = array();
        $n=0;
        foreach ($payment as $value) {
        	if($value['types'] == 'activity')
        	{
        		$conditions = array( 'id' => $value['linkid']);
				$s_activity = spClass('activityinfo'); 
				$activity = $s_activity->find($conditions);
				$payment[$n]['linkid'] = $activity;
				//dump($activity['cityid']);
				$conditions = array( 'id' => $activity['cityid'] );
				$e_citys = spClass('lib_cityinfo'); 
		        $e_city = $e_citys->find($conditions);
		        $paycitys[] = $e_city;
        	}
        	elseif($value['types'] == 'lines')
        	{
        		$conditions = array( 'id' => $value['linkid']);
				$s_activity = spClass('linesinfo'); 
				$activity = $s_activity->find($conditions);
				$payment[$n]['linkid'] = $activity;
				//dump($activity['cityid']);
				$conditions = array( 'id' => $activity['cityid'] );
				$e_citys = spClass('lib_cityinfo'); 
		        $e_city = $e_citys->find($conditions);
		        $paycitys[] = $e_city;
        	}
        	$n++;
        }
        $this->payment = $payment;
        $this->paycitys = $paycitys;
        //dump($this->payment);

		$this -> display('user/UserPayment.html');
	}

	  // 用户中心_游记
	function UserPrivateletter(){
        require('include/checking.php'); // 用户身份校验
        $this->id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $this->id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

	    $user = spClass('privateletterinfo'); 
		if(empty($filter) && empty($classid))
		{ 
		  $conditions = 'uid ='.$this->id.' or replyid = '.$this->id.'';
		  $results = $user->findAll($conditions,'id DESC',NULL,"150"); 
		}
        $n=0;
		foreach ($results as $value) {
			$user = spClass('lib_user'); 
		    $conditions = array( 'uid' => $value['uid'] );
		    $result_user = $user->find($conditions);  // 分页
		    $results[$n]['uid'] = $result_user;

		    $user = spClass('lib_user'); 
		    $conditions = array( 'uid' => $value['replyid'] );
		    $result_user = $user->find($conditions);  // 分页
		    $results[$n]['replyid'] = $result_user;
		    $n++;
		}
		$this->results = $results; 

		$this -> display('user/UserPrivatelette.html');
	}

	  // 用户中心_提醒
	function UserRemind(){
        require('include/checking.php'); // 用户身份校验
        $this->id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $this->id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

	    $user = spClass('remindinfo'); 
		if(empty($filter) && empty($classid))
		{ 
		  $conditions = 'uid ='.$this->id.' or replyid = '.$this->id.'';
		  $results = $user->findAll($conditions,'id DESC',NULL,"150"); 
		}
        $n=0;
		foreach ($results as $value) {
			$user = spClass('lib_user'); 
		    $conditions = array( 'uid' => $value['uid'] );
		    $result_user = $user->find($conditions);  // 分页
		    $results[$n]['uid'] = $result_user;

		    $user = spClass('lib_user'); 
		    $conditions = array( 'uid' => $value['replyid'] );
		    $result_user = $user->find($conditions);  // 分页
		    $results[$n]['replyid'] = $result_user;
		    $n++;
		}
		$this->results = $results; 

		$this -> display('user/UserRemind.html');
	}

	  // 用户中心_游记
	function UserActivity(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('activityinfo'); 
        $activityinfo = $e_user->spPager($this->spArgs('page', 1), 15)->findAll($conditions);
        $this->pager = $e_user->spPager()->getPager();
        $n = 0;
        foreach ($activityinfo as $value) {
        	$conditions_city = array( 'id' => $value['cityid'] );
			$t_city = spClass('lib_cityinfo'); 
			$this->city = $t_city->find($conditions_city);
			//dump($this->city);
			$activityinfo[$n]['cityid'] = $this->city;
        	$n++;
        }
        $this->activityinfo = $activityinfo;

		$this -> display('user/UserActivity.html');
	}

	  // 用户中心_游记
	function UserListbd(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('listbdinfo'); 
        $listbdinfo = $e_user->spPager($this->spArgs('page', 1), 15)->findAll($conditions);
        $this->pager = $e_user->spPager()->getPager();
        $n = 0;
        foreach ($listbdinfo as $value) {
        	$conditions_city = array( 'id' => $value['cityid'] );
			$t_city = spClass('lib_cityinfo'); 
			$this->city = $t_city->find($conditions_city);
			//dump($this->city);
			$listbdinfo[$n]['cityid'] = $this->city;
        	$n++;
        }
        $this->listbdinfo = $listbdinfo;

  //       $scenerys = array();
  //       $i=0;
		// while( $i <= count($this->youjiinfo)-1)
		// {		  
		//   $linkid = $this->youjiinfo[$i]['linkid'];
		//   $conditions = array( 'id' => $linkid );
		//   if($this->youjiinfo[$i]['types'] == 'scenery')
		//   {
		//      $sceneryinfo = spClass('sceneryinfo');
		//   }
		//   else
		//   {
  //            $sceneryinfo = spClass('linesinfo');
		//   }
		//   $scenerys[] = $sceneryinfo->find($conditions);
		//   $i++;
		// }
		// $this->scenerys = $scenerys;

		$this -> display('user/UserListbd.html');
	}

    // 用户中心_游记
	function UserYouji(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('youjiinfo'); 
        $this->youjiinfo = $e_user->spPager($this->spArgs('page', 1), 10)->findAll($conditions);
        $this->pager = $e_user->spPager()->getPager();
        $scenerys = array();
        $i=0;
		while( $i <= count($this->youjiinfo)-1)
		{		  
		  $linkid = $this->youjiinfo[$i]['linkid'];
		  $conditions = array( 'id' => $linkid );
		  if($this->youjiinfo[$i]['types'] == 'scenery')
		  {
		     $sceneryinfo = spClass('sceneryinfo');
		  }
		  else
		  {
             $sceneryinfo = spClass('linesinfo');
		  }
		  $scenerys[] = $sceneryinfo->find($conditions);
		  $i++;
		}
		$this->scenerys = $scenerys;

		$this -> display('user/UserYouji.html');
	}

// 用户中心_游记
	function UserVisits(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('visitsinfo'); 
        $this->visitsinfo = $e_user->spPager($this->spArgs('page', 1), 15)->findAll($conditions);
        $this->pager = $e_user->spPager()->getPager();
        $scenerys = array();
        $i=0;
		while( $i <= count($this->visitsinfo)-1)
		{		  
		  $linkid = $this->visitsinfo[$i]['linkid'];
		  $conditions = array( 'id' => $linkid );
		  if($this->visitsinfo[$i]['types'] == 'scenery')
		  {
		     $sceneryinfo = spClass('sceneryinfo');
		  }
		  else
		  {
             $sceneryinfo = spClass('linesinfo');
		  }
		  $scenerys[] = $sceneryinfo->find($conditions);
		  $i++;
		}
		$this->scenerys = $scenerys;

		$this -> display('user/UserVisits.html');
	}
	
    // 用户中心_论坛
	function UserAnswers(){
        require('include/checking.php'); // 用户身份校验
        
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('answersinfo'); 
        $this->answersinfo = $e_user->spPager($this->spArgs('page', 1), 15)->findAll($conditions);
        $this->pager = $e_user->spPager()->getPager();
        $scenerys = array();
        $i=0;
		while( $i <= count($this->answersinfo)-1)
		{		  
		  $linkid = $this->answersinfo[$i]['linkid'];
		  $conditions = array( 'id' => $linkid );
		  if($this->answersinfo[$i]['types'] == 'scenery')
		  {
		     $sceneryinfo = spClass('sceneryinfo');
		  }
		  else
		  {
             $sceneryinfo = spClass('linesinfo');
		  }
		  $scenerys[] = $sceneryinfo->find($conditions);
		  $i++;
		}
		$this->scenerys = $scenerys;

		$this -> display('user/UserAnswers.html');
	}

    // 用户中心_点评
	function UserComment(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('commentinfo'); 
        $this->commentinfo = $e_user->spPager($this->spArgs('page', 1), 15)->findAll($conditions);
        $this->pager = $e_user->spPager()->getPager();
        $scenerys = array();
        $i=0;
		while( $i <= count($this->commentinfo)-1)
		{		  
		  $linkid = $this->commentinfo[$i]['linkid'];
		  $conditions = array( 'id' => $linkid );
		  if($this->commentinfo[$i]['types'] == 'scenery')
		  {
		     $sceneryinfo = spClass('sceneryinfo');
		  }
		  else
		  {
             $sceneryinfo = spClass('linesinfo');
		  }
		  $sceneryinfo = spClass('sceneryinfo');
		  $scenerys[] = $sceneryinfo->find($conditions);
		  $i++;
		}
		$this->scenerys = $scenerys;

		$this -> display('user/UserComment.html');
	}

	    // 用户中心_图片
	function UserPicture(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id;
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('pictureinfo'); 
        $this->pictureinfo = $e_user->spPager($this->spArgs('page', 1), 15)->findAll($conditions,'id DESC');
        $this->pager = $e_user->spPager()->getPager();
        $this->pictureinfonum = count($this->pictureinfo);
        $scenerys = array();
        $i=0;
		while( $i <= count($this->pictureinfo)-1)
		{		  
		  $linkid = $this->pictureinfo[$i]['linkid'];
		  $conditions = array( 'id' => $linkid );
		  $sceneryinfo = spClass('sceneryinfo');
		  $scenerys[] = $sceneryinfo->find($conditions,"id DESC");
		  $i++;
		}
		$this->scenerys = $scenerys;

		$this -> display('user/UserPicture.html');
	}

	// 用户中心_我想去的景点
	function UserSantgoScenery(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id;
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

        // ===================
		$conditions_swantgo = array('uid'=>$_SESSION["quser"]['uid']);
	    $wantgo = spClass('wantgo');
	    $this->wantgo_scenery = $wantgo->spPager($this->spArgs('page', 1), 15)->findAll($conditions_swantgo,'linkid DESC');
        $this->pager = $wantgo->spPager()->getPager();
        $sceneryshow_w_s = array();
        $this->wantgo_scenerynum = count($this->wantgo_scenery);
		foreach($this->wantgo_scenery as $value){ 
			$conditions_w_s = array('id' => $value['linkid']);
            $sceneryinfo_w_s = spClass('sceneryinfo');
			$sceneryshow_w_s[] = $sceneryinfo_w_s->find($conditions_w_s);
			$types_w_s[] = $value['types'];
		}
		$this->sceneryshow_w_s = $sceneryshow_w_s;
		$this->types_w_s = $types_w_s;
        $i=0;
		while( $i <= count($this->sceneryshow_w_s)-1)
		{		
		 $provinceid = $this->sceneryshow_w_s[$i]['provinceid'];  
		 $cityid = $this->sceneryshow_w_s[$i]['cityid'];
		 $areaid = $this->sceneryshow_w_s[$i]['areaid'];
		 $cityinfo = spClass('lib_cityinfo');
		 $conditions_w_s = array( 'id' => $provinceid );
		 $provinces[] = $cityinfo->find($conditions_w_s);
		 $conditions_w_s = array( 'id' => $cityid );
		 $citys[] = $cityinfo->find($conditions_w_s);
		 $conditions_w_s = array( 'id' => $areaid );
		 $areas[] = $cityinfo->find($conditions_w_s);
		 $i++;
		}
		$this->provinces_w_s = $provinces;
		$this->citys_w_s = $citys;
		$this->areas_w_s = $areas;

		$this -> display('user/UserSantgoScenery.html');
	}

	// 用户中心_我去过的地方
	function UserSaleadygoScenery(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id;
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

        // 去过的景点
		$conditions_saleadygo= array('uid'=>$_SESSION["quser"]['uid']);
	    $aleadygo = spClass('aleadygo');
	    $this->aleadygo_scenery = $aleadygo->findAll($conditions_saleadygo);
	    $this->aleadygo_scenerynum = count($this->aleadygo_scenery);
        $sceneryshow_a_s = array();
        $types_a_s = array();
		foreach($this->aleadygo_scenery as $value){ 
			$conditions_a_s = array('id' => $value['linkid']);
            $sceneryinfo_a_s = spClass('sceneryinfo');
			$sceneryshow_a_s[] = $sceneryinfo_a_s->find($conditions_a_s);
			$types_a_s[] = $value['types'];
		}
		$this->sceneryshow_a_s = $sceneryshow_a_s;
		$this->types_a_s = $types_a_s;
        $i=0;
		while( $i <= count($this->sceneryshow_a_s)-1)
		{		
		 $provinceid = $this->sceneryshow_a_s[$i]['provinceid'];  
		 $cityid = $this->sceneryshow_a_s[$i]['cityid'];
		 $areaid = $this->sceneryshow_a_s[$i]['areaid'];
		 $cityinfo = spClass('lib_cityinfo');
		 $conditions_a_s = array( 'id' => $provinceid );
		 $provinces[] = $cityinfo->find($conditions_a_s);
		 $conditions_a_s = array( 'id' => $cityid );
		 $citys[] = $cityinfo->find($conditions_a_s);
		 $conditions_a_s = array( 'id' => $areaid );
		 $areas[] = $cityinfo->find($conditions_a_s);
		 $i++;
		}
		$this->provinces_a_s = $provinces;
		$this->citys_a_s = $citys;
		$this->areas_a_s = $areas;

		$this -> display('user/UserSaleadygoScenery.html');
	}

	// 用户中心_动态
	function UserLog(){
		require('include/checking.php'); // 用户身份校验
		$id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$userhistory = spClass('userhistory');
		$this->userhistory = $userhistory->spPager($this->spArgs('page', 1), 15)->findAll($conditions,'id DESC');
        $this->pager = $userhistory->spPager()->getPager();

		$this -> display('user/UserLog.html');
	}

		// 用户中心_动态
	function UserReply(){
        require('include/checking.php'); // 用户身份校验
        $id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->find($conditions);
		$this->id = $id; 
		$this->uname = $e_result["uname"];
		$this->integral = $e_result["integral"];
		$this->qqlink = $e_result["qqlink"];
		$this->avatar = str_replace(".jpg","",$e_result['avatar']); 

		$conditions = array( 'userid' => $_SESSION["quser"]['uid'] );
		$e_user = spClass('replyinfo'); 
        $this->replyinfo = $e_user->spPager($this->spArgs('page', 1), 15)->findAll($conditions,'id DESC');
        $this->pager = $e_user->spPager()->getPager();
        //dump($this->replyinfo);
  //       $scenerys = array();
  //       $i=0;
		// while( $i <= count($this->youjiinfo)-1)
		// {		  
		//   $linkid = $this->youjiinfo[$i]['linkid'];
		//   $conditions = array( 'id' => $linkid );
		//   $sceneryinfo = spClass('sceneryinfo');
		//   $scenerys[] = $sceneryinfo->find($conditions);
		//   $i++;
		// }
		// $this->scenerys = $scenerys;

		$this -> display('user/UserReply.html');
	}

	// 用户中心_测试
	function UserTest(){
		$this -> display('user/UserTest.html');
	}

	// 编辑用户
	function editdata(){
		$data = $this->spArgs(); 
		$conditions = array('uid'=>$data['id']); 
		$lib_user = spClass('lib_user');
        $result = $lib_user->findAll($conditions); 
		$data['OldPass'] = $result[0]['upass'];
		if(!empty($data['Password']))
		{
			$row = array('Password' => $data['Password'],'checkPass' => md5($data['checkPass']),'OldPass' => $data['OldPass']);
			$lib_user = spClass('lib_user');
			$lib_user->verifier = $lib_user->verifier_ucmodify; // 切换验证规则
			$json = json_encode($lib_user->spVerifier($row));
			if($json == 'false'){ 
			  $row['upass'] = md5($data['Password']);
			  $lib_user->update($conditions,$row);
			}  // 进行新增操作
		}else{
			$row = array('uname' => $data['uname'],'realname' => $data['realname'],'avatar' => $data['avatar'],'Phone' => $data['Phone'],'QQ' => $data['QQ'],'Email' => $data['Email'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'Address' => $data['Address']);
			$lib_user = spClass('lib_user');
			$lib_user->verifier = $lib_user->verifier_nopass; // 切换验证规则
			$json = json_encode($lib_user->spVerifier($row));
			if($json == 'false'){ 
			  $lib_user->update($conditions,$row);
			}  // 进行新增操作
		}
		echo($json);
		// 返回（显示）结果HTML，该结果由Smarty模板提供
		//$this -> display('mydata.html'); 
	}
	
	function subclass(){
		$data = $this->spArgs(); // $data是提交上来的数据
		
		$conditions = array( 'pid'=>$data['id'] );
		$c_name = spClass('lib_cityinfo'); 
		$data['cityinfo'] = $c_name->findAll($conditions); 

		echo json_encode($data);
	}

	// 退出登录
    function logout(){
		// 这里是PHP.net关于删除SESSION的方法
		unset($_SESSION['quser']);
		// 跳转回首页
		$this->success("已退出，返回首页！", spUrl("login","index"));
	}
}
