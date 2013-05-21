<?php

class visitsinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('visitsinfo'); 
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
		$e_user = spClass('visitsinfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->id = $id; 
		$this->id = $e_result[0]['id']; 
		$this->username = $e_result[0]['username'];
		$this->ctitle = $e_result[0]['ctitle'];
		$this->ckeyword = $e_result[0]['ckeyword'];
		$this->cdirections = $e_result[0]['cdirections'];
		$this->picurl = $e_result[0]['picurl'];
		$this->title = $e_result[0]['title']; 
		if(!empty($e_result[0]['releasedate'])){
		 $this->releasedate = date('Y-m-d H:i:s',$e_result[0]['releasedate']); 
		}		
		$this->content = $e_result[0]['content']; 
        $this->provinceid = $e_result[0]['provinceid']; 
		$this->cityid = $e_result[0]['cityid']; 
		$this->areaid = $e_result[0]['areaid']; 
		$this->types = $e_result[0]['types']; 
		$this->linkid = $e_result[0]['linkid']; 
		$this->userid = $e_result[0]['userid']; 
		$this->score = $e_result[0]['score'];
        $this->clicknumber = $e_result[0]['clicknumber'];
        $this->ispass = $e_result[0]['ispass'];
		$this -> display('admin/content/visits.html');
	}

	function detail(){
		$this->info = $this->spArgs("info");
	    $this->id = $this->spArgs("id");

        if($this->info == "scenery")
        {
		   $s_seoinfo = spClass('seoinfo');
		}
		else
		{
		   $s_seoinfo = spClass('lineseoinfo');
		} 
		$conditions = array( 'id' => $this->id );
		$this->seo = $s_seoinfo->find($conditions);

        require('include/sideinfo.php'); // 侧边信息
		if($this->info == 'info')
		{
			$conditions = array( 'id' => $this->id );
			$e_visits = spClass('visitsinfo'); 
	        $out_visits = $e_visits->find($conditions);
	        require('include/inside.php'); // 通用数据调取
	        $out_visits['content'] = inside($out_visits['content']);
	        $this->out_visits = $out_visits;
	        $this->info = $this->out_visits['types']; // 类型
	        $this->sid = $this->out_visits['linkid']; // 类型
	        
		    // 省
			$conditions_province = array( 'id' => $this->out_visits['provinceid'] );
			$t_province = spClass('lib_cityinfo'); 
			$this->province = $t_province->find($conditions_province); 

            // 市
			$conditions_city = array( 'id' => $this->out_visits['cityid'] );
			$t_city = spClass('lib_cityinfo'); 
			$this->city = $t_city->find($conditions_city);

			// 区
			$conditions_area = array( 'id' => $this->out_visits['areaid'] );
			$t_area = spClass('lib_cityinfo'); 
			$this->area = $t_area->find($conditions_area); 

			require('include/infomore.php'); // 通用数据调取 
			require('include/conent.php'); // 通用数据调取 

            $clicknumber = $this->out_visits['clicknumber'];
            $clicknumber++;
            $e_visits->update($conditions,array('clicknumber'=>$clicknumber));
			// ===================================
			$this->types = 'visits';
			$this->linkid =  $this->out_visits["linkid"];

			$this->provinceid = $_SESSION["quser"]['provinceid'];
			$this->cityid = $_SESSION["quser"]['cityid']; 

			$this->username = $_SESSION["quser"]['uname'];
			$this->userid = $_SESSION["quser"]['uid'];

			$conditions_reply = array( 'types' => 'visits','linkid' => $this->id);
			$s_reply = spClass('replyinfo'); 
			$this->reply = $s_reply->findAll($conditions_reply); 

            // 获取头像
            $userarray = array();
            foreach ($this->reply as $value)
			{
			  	$conditions = array( 'uid' => $value['userid'] );
				$e_user = spClass('lib_user'); 
		        $e_result = $e_user->find($conditions);
		        $userarray[] = array('img' =>$e_result['avatar'],"uid"=>$e_result['uid']);
			}
            $this->userarray = $userarray;
            // 获取头像
            
			if($this->out_visits == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('content/visits.html');
		    }
	    }
	    else
	    {
	    	require('include/dataing.php'); // 通用数据调取 
	    	$conditions = array('types' => $this->info,'linkid' => $this->id,'ispass' => 0);
		    $e_visits = spClass('visitsinfo');
            $visits = $e_visits->spPager($this->spArgs('page', 1), 10)->findAll($conditions);
            require('include/inside.php'); // 通用数据调取
            // 获取头像
            $userarray = array();
            $num = 0;
            foreach ($visits as $value)
			{
			  	$conditions = array( 'uid' => $value['userid'] );
				$e_user = spClass('lib_user'); 
		        $e_result = $e_user->find($conditions);
		        $userarray[] = $e_result['avatar'];
		        $visits[$num]['content'] = inside($visits[$num]['content']);
			}
            $this->userarray = $userarray;
            // 获取头像
            $this->pager = $e_visits->spPager()->getPager();
            $this->visits = $visits;

            if($this->visits == null)
			{
				//import(APP_PATH.'/tpl/404.php');exit();
				$this -> display('list/visits.html');
			}
			else
			{
			    $this -> display('list/visits.html');
		    }
	    }
	}

	function add(){
        require('include/checking.php'); // 用户身份校验
		
		$this->types = $this->spArgs("type");
		$this->linkid = $this->spArgs("id");	

		$conditions = array( 'id' => $this->linkid);
		if($this->types == "lines")
		{ 
			$s_scenery = spClass('linesinfo'); 
		}
	    else
	    {
	    	$s_scenery = spClass('sceneryinfo'); 
	    }
		$this->show = $s_scenery->find($conditionst);
		$this->provinceid = $this->show['provinceid'];
		$this->cityid = $this->show['cityid']; 
		$this->areaid = $this->show['areaid']; 

		$this->username = $_SESSION["quser"]['uname'];
		$this->userid = $_SESSION["quser"]['uid'];
		$this -> display('content/Clientfb/visits.html');
	}
	
	function edit(){
        require('include/checking.php'); // 用户身份校验
		
		$this->types = $this->spArgs("type");
		$this->linkid = $this->spArgs("id");	
		$this->funs = 'edit';
	    $conditionst = array( 'id' => $this->linkid );
		$e_visits = spClass('visitsinfo'); 
	    $this->out_visits = $e_visits->find($conditionst);
		if($_SESSION["quser"]['uid'] != $this->out_visits['userid'])
		{$this->error("对不起,你没有编辑权限！！",spUrl("main","index"));}

		$conditions = array( 'id' =>  $this->out_visits['linkid']);
		if($this->types == "lines")
		{ 
			$s_scenery = spClass('linesinfo'); 
		}
	    else
	    {
	    	$s_scenery = spClass('sceneryinfo'); 
	    }
		$this->show = $s_scenery->find($conditionst);
		$this->provinceid = $this->show['provinceid'];
		$this->cityid = $this->show['cityid']; 
		$this->areaid = $this->show['areaid']; 

		$this->username = $_SESSION["quser"]['uname'];
		$this->userid = $_SESSION["quser"]['uid'];
		$this -> display('content/Clientfb/visits.html');
	}

	function useredit(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$row = array('title'=>$data['title'],'content'=>$data['content']);
		$user = spClass('visitsinfo');
		$json = $data['id'];
		$user->update($conditions, $row);
		echo($json);
	}
	
	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		if($data['releasedate'] == null)
		{ $releasedate = time(); }
		else
		{ $releasedate = strtotime($data['releasedate']); }
		if($data['areaid'] == null)
		{ $data['areaid'] = 01; }
	    preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i',$data['content'],$match);

	    if(empty($match[1]))
    	{ $match[1] = "default.jpg"; }
    
		$newrow = array( // PHP的数组
		'id'=>$data['id'],
		'username'=>$data['username'],
		'picurl'=>$match[1],
		'title'=>$data['title'],
		'releasedate'=>$releasedate,
		'content'=>$data['content'],
		'provinceid'=>$data['provinceid'],
	    'cityid'=>$data['cityid'],
	    'areaid'=>$data['areaid'],
		'types'=>$data['types'],
	    'linkid'=>$data['linkid'],
		'userid'=>$data['userid']
		);
		$user = spClass('visitsinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		$xml = simplexml_load_file('rules/integral.xml'); // 积分配置
		if($json == 'false'){ 

		$newid = $user->create($newrow);
            $json = json_encode($newid);
		$Historyrow = array( // PHP的数组
		'types'=>"visits",
		'title'=>$data['title'],
		'linkid'=>$newid,
		'userid'=>$_SESSION["quser"]['uid'],
		'integral'=>(int)$xml->visits,
		'releasedate'=>$releasedate
		);
		$syshistory = spClass('syshistory'); 
		$syshistory->create($Historyrow);
		$userhistory = spClass('userhistory'); 
		$userhistory->create($Historyrow);

		$user = spClass('lib_user');
		$user_result = $user->find($conditions);
		$conditions = array('uid'=>$_SESSION["quser"]['uid']); 
		$row = array('integral'=>$user_result["integral"] + (int)$xml->visits);
		$user->update($conditions, $row);  // 进行新增操作
		
		}  // 进行新增操作

		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'title'=>$data['title'],'releasedate'=>$releasedate,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'types'=>$data['types'],'linkid'=>$data['linkid'],'userid'=>$data['userid'],'score'=>$data['score'],'clicknumber'=>$data['clicknumber'],'ispass'=>$data['ispass'],'ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('visitsinfo');
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
        $user = spClass('visitsinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

	 //不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('visitsinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('visitsinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
