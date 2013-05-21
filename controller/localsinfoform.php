<?php

class localsinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('localsinfo'); 
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
		$e_user = spClass('localsinfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->id = $id; 
		$this->id = $e_result[0]['id']; 
		$this->username = $e_result[0]['username'];
		$this->picurl = $e_result[0]['picurl'];
		$this->title = $e_result[0]['title']; 
		$this->ctitle = $e_result[0]['ctitle'];
		$this->ckeyword = $e_result[0]['ckeyword'];
		$this->cdirections = $e_result[0]['cdirections'];
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
		$this -> display('admin/content/locals.html');
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
			$e_locals = spClass('localsinfo'); 
	        $out_locals = $e_locals->findAll($conditions); 
			$this->id = $id; 
			$this->username = $out_locals[0]['username'];
			$this->picurl = $out_locals[0]['picurl'];
			$this->title = $out_locals[0]['title']; 

			// ===================================
			$this->types = 'locals';
			$this->linkid = $this->spArgs("id");

			$this->provinceid = $_SESSION["quser"]['provinceid'];
			$this->cityid = $_SESSION["quser"]['cityid']; 

			$this->username = $_SESSION["quser"]['realname'];
			$this->userid = $_SESSION["quser"]['uid'];

			$conditions_reply = array( 'types' => 'locals','linkid' => $this->id);
			$s_reply = spClass('replyinfo'); 
			$this->reply = $s_reply->findAll($conditions_reply); 

			if($out_locals == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('content/locals.html');
		    }
	    }
	    else
	    {
	    	$conditions = array( 'types' => $info,'linkid' => $id,'ispass' => 1);
		    $e_locals = spClass('localsinfo'); 
            $this->locals = $e_locals->findAll($conditions);
            if($this->locals == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('list/locals.html');
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
		$this -> display('content/Clientfb/locals.html');
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
		$user = spClass('localsinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		if($json == 'false'){ 
		  $newid = $user->create($newrow);

		  	$Historyrow = array( // PHP的数组
			'types'=>"locals",
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

			$user = spClass('lib_user');
			$user_result = $user->find($conditions);
			$conditions = array('uid'=>$_SESSION["quser"]['uid']); 
			$row = array('integral'=>$user_result["integral"] + 6);
			$user->update($conditions, $row);  // 进行新增操作

		}  // 进行新增操作

		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'title'=>$data['title'],'releasedate'=>$releasedate,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'types'=>$data['types'],'linkid'=>$data['linkid'],'userid'=>$data['userid'],'score'=>$data['score'],'ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('localsinfo');
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
        $user = spClass('localsinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}
}
