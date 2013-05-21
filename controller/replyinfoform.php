<?php

class replyinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('replyinfo'); 
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
		$e_user = spClass('replyinfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->id = $id; 
		$this->id = $e_result[0]['id']; 
		$this->username = $e_result[0]['username'];
		$this->replyid = $e_result[0]['replyid']; 
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
		$this -> display('admin/content/reply.html');
	}

	function detail(){
		$info = $this->spArgs("info");
	    $id = $this->spArgs("id");

	    $conditions = array( 'id' => $this->id );
		$scenerydata = spClass('sceneryinfo'); 
        $this->showscenery = $scenerydata->find($conditions);

		if($info == 'info')
		{
			$conditions = array( 'id' => $id );
			$e_reply = spClass('replyinfo'); 
	        $out_reply = $e_reply->findAll($conditions); 
			$this->id = $id; 
			$this->username = $out_reply[0]['username'];
			$this->content = $out_reply[0]['content']; 
			if($out_reply == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('content/reply.html');
		    }
	    }
	    else
	    {
	    	$conditions = array( 'types' => $info,'linkid' => $id,'ispass' => 1);
		    $e_reply = spClass('replyinfo'); 
            $this->reply = $e_reply->findAll($conditions);
            if($this->reply == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('list/reply.html');
		    }
	    }
	}

	function add(){
        require('include/checking.php'); // 用户身份校验
		$this->types = $this->spArgs("type");
		$this->linkid = $this->spArgs("id");

		$this->provinceid = $_SESSION["quser"]['provinceid'];
		$this->cityid = $_SESSION["quser"]['cityid']; 

		$this->username = $_SESSION["quser"]['realname'];
		$this->userid = $_SESSION["quser"]['uid'];
		$this -> display('content/Clientfb/reply.html');
	}
	
	function edit(){
		require('include/checking.php'); // 用户身份校验
		$this->types = $this->spArgs("type");
		$this->id = $this->spArgs("id");
		$this->funs = 'edit';
		$conditionst = array('id' => $this->id);
	    $e_reply = spClass('replyinfo');
	    $this->out_reply = $e_reply->find($conditionst);
	    if($_SESSION["quser"]['uid'] != $this->out_reply['userid'])
		{$this->error("对不起,你没有编辑权限！！",spUrl("main","index"));}

		$this->types = $this->out_reply["types"];
		$this->linkid = $this->out_reply["linkid"];
		
		$this->username = $_SESSION["quser"]['uname'];
		$this->userid = $_SESSION["quser"]['uid'];
		$this -> display('content/Clientfb/reply.html');
	}

	function useredit(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$row = array('content'=>$data['content']);
		$user = spClass('replyinfo');
		$user->update($conditions, $row);
		echo(json_encode($data));
	}

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		if($data['releasedate'] == null)
		{ $releasedate = time(); }
		else
		{ $releasedate = strtotime($data['releasedate']); }
		if($data['areaid'] == null)
		{ $data['areaid'] = 01; }
		$newrow = array( // PHP的数组
		'id'=>$data['id'],
		'username'=>$data['username'],
		'replyid'=>$data['replyid'],
		'releasedate'=>$releasedate,
		'content'=>$data['content'],
		'provinceid'=>$data['provinceid'],
	    'cityid'=>$data['cityid'],
	    'areaid'=>$data['areaid'],
		'types'=>$data['types'],
	    'linkid'=>$data['linkid'],
		'userid'=>$data['userid']
		);
		$user = spClass('replyinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		$xml = simplexml_load_file('rules/integral.xml'); // 积分配置
		if($json == 'false'){ 
		    $newid = $user->create($newrow);

		    $Historyrow = array( // PHP的数组
			'types'=>"reply",
			'title'=>$data['pagetitle'],
			'linkid'=>$newid,
			'userid'=>$_SESSION["quser"]['uid'],
			'integral'=>(int)$xml->reply,
			'releasedate'=>$releasedate
			);
			$syshistory = spClass('syshistory'); 
			$syshistory->create($Historyrow);
			$userhistory = spClass('userhistory'); 
			$userhistory->create($Historyrow);

            $data['pageurl'] = str_replace($this->domain,"",$data['pageurl']);
	        $newrow = array( // PHP的数组
			'id'=>time(),
			'uid'=>$_SESSION["quser"]['uid'],
			'content'=>$data['pagetitle'],
			'replyid'=>$data['replyid'],
			'url'=>$data['pageurl']
			);
			$remindinfo = spClass('remindinfo');
			$newid = $remindinfo->create($newrow);

			$user = spClass('lib_user');
			$user_result = $user->find($conditions);
			$conditions = array('uid'=>$_SESSION["quser"]['uid']); 
			$row = array('integral'=>$user_result["integral"] + (int)$xml->reply);
			$user->update($conditions, $row);  // 进行新增操作

		}  // 进行新增操作

		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'replyid'=>$data['replyid'],'releasedate'=>$releasedate,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'types'=>$data['types'],'linkid'=>$data['linkid'],'userid'=>$data['userid'],'score'=>$data['score']);
		$user = spClass('replyinfo');
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
        $user = spClass('replyinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

			 //不进行二级推荐
    function notop(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('hotnumber'=>0);
        $rulesinfo = spClass('replyinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yestop(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('hotnumber'=>1);
        $rulesinfo = spClass('replyinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
	 //不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('replyinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('replyinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
