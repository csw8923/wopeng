<?php

class albuminfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('albuminfo'); 
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
		$e_user = spClass('albuminfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->id = $id; 
		$this->id = $e_result[0]['id']; 
		$this->username = $e_result[0]['username'];
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
		$this -> display('admin/content/album.html');
	}

	function detail(){
		$this->info = $this->spArgs("info");
	    $this->id = $this->spArgs("id");

         // 人气排行
		$scenerydata = spClass('sceneryinfo'); 
        $this->showscenery = $scenerydata->find($conditions);
        $this->showsceneryAll = $scenerydata->findAll(null,'id DESC',NULL,"10"); // 好景点推荐
        //dump($this->showsceneryAll);
        
        // 哪玩达人
		$top_user = spClass('lib_user'); 
        $this->top_resultuser = $top_user->findAll(null,'integral DESC',NULL,"10"); 
        //dump($this->top_resultuser);

		if($this->info == 'info')
		{
			$conditions = array( 'id' => $this->id );
			$e_album = spClass('albuminfo'); 
	        $out_album = $e_album->find($conditions); 
			$this->id = $id; 
			$this->username = $out_album['username'];
			$this->picurl = $out_album['picurl'];
			$this->title = $out_album['title']; 

            $conditions_pic = array( 'id' => $this->id );
			$picture = spClass('pictureinfo'); 
            $this->pictures = $picture->findAll($conditions_pic);

			if($out_album == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('content/album.html');
		    }
	    }
	    else
	    {
	    	$conditions = array( 'types' => $this->info,'linkid' => $this->id  );
	    	if($this->info == "scenery")
	    	{ $e_album = spClass('pictureinfo'); }
		    else
		    { $e_album = spClass('albuminfo'); } 
            $this->album = $e_album->findAll($conditions);
            if($this->album == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('list/album.html');
		    }
	    }
	}
	
	public function photo(){
    	$this->pid = $this->spArgs("id");
    	$conditions = array('id' => $this->pid );
	    $picture = spClass('pictureinfo'); 
        $this->pictures_detailed = $picture->find($conditions);

        // 分页
        $Previoupage = array('types' => $this->pictures_detailed['types'],'linkid' => $this->pictures_detailed['linkid']);
        $this->picturespage = $picture->findAll($Previoupage);
        $this->previou = 0;
        $this->next = 0;
        $previoudata = array();
        $nextdata = array();
        $dataing = array();
        $num=0;
        foreach ($this->picturespage as $value) {
        	if ($this->pid == $value["id"]) {
        		$dataing[] = $this->picturespage[$num];
        		$this->previou = $this->picturespage[$num-1];
        		$this->next = $this->picturespage[$num+1];
        	}
        	if($this->pid > $value["id"])
        	{
        		$previoudata[] = $this->picturespage[$num];
        	}

        	if($this->pid < $value["id"])
        	{
        		$nextdata[] = $this->picturespage[$num];
        	}
        	$num++;
        }
        $this->dataing = $dataing;
        
        $delpw = count($previoudata);
        if($delpw >= 13)
        {
	        $i=0;
	        while($i<=6)
		    {
		       array_shift($previoudata);
		       $i++;
		    }
	    }
	    //dump($previoudata);
        $this->previoudata = $previoudata;
        $this->nextdata = $nextdata;
        // 分页
        $this->info = $this->pictures_detailed['types']; // 类型 
        $this->sid = $this->pictures_detailed['linkid'];
        $this->id = $this->pictures_detailed['linkid'];
    	require('include/sideinfo.php'); // 侧边信息   
        require('include/infomore.php'); // 通用数据调取 
        require('include/conent.php'); // 通用数据调取 
        $conditions_num = array('id' => $this->pid );
        $clicknumber = $this->pictures_detailed['clicknumber'];
        $clicknumber++;
        $picture->update($conditions_num,array('clicknumber'=>$clicknumber));
        // ===================================

        $conditions = array('id' => $this->pictures_detailed['linkid'] );
		$scenerydata = spClass('sceneryinfo'); 
        $this->showscenery = $scenerydata->find($conditions);

        $this->types = 'photo';
        $this->linkid =  $this->pictures_detailed["linkid"];

        $this->provinceid = $_SESSION["quser"]['provinceid'];
        $this->cityid = $_SESSION["quser"]['cityid'];

        $this->username = $_SESSION["quser"]['uname'];
        $this->userid = $_SESSION["quser"]['uid'];

        $conditions_reply = array( 'types' => 'photo','linkid' => $this->pid);
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

		$this -> display('content/photo.html');
	}

    public function phototitle(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('title'=>$data['title']);
		$user = spClass('pictureinfo');
		$data['msg'] = $user->update($conditions, $row);
		echo( $data['msg'] );
    }

    public function covertitle(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('title'=>$data['title']);
		$user = spClass('coverinfo');
		$data['msg'] = $user->update($conditions, $row);
		echo( $data['msg'] );
    }

    function dphotos(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
        $user = spClass('pictureinfo');
        $data['msg'] = $user->delete($conditions);	
		echo(json_encode($data));
	}

	public function cover(){
    	$this->id = $this->spArgs("id");
    	$conditions = array('id' => $this->id );
	    $picture = spClass('coverinfo'); 
        $this->pictures = $picture->find($conditions);
        $clicknumber = $this->pictures['clicknumber'];
        $clicknumber++;
        $picture->update($conditions,array('clicknumber'=>$clicknumber));
        // ===================================

         // 人气排行
		$scenerydata = spClass('sceneryinfo'); 
        $this->showscenery = $scenerydata->find($conditions);
        $this->showsceneryAll = $scenerydata->findAll(null,'id DESC',NULL,"10"); // 好景点推荐
        //dump($this->showsceneryAll);
        
        // 哪玩达人
		$top_user = spClass('lib_user'); 
        $this->top_resultuser = $top_user->findAll(null,'integral DESC',NULL,"10"); 
        //dump($this->top_resultuser);


        $this->types = 'cover';
        $this->linkid =  $this->pictures["linkid"];

        $this->provinceid = $_SESSION["quser"]['provinceid'];
        $this->cityid = $_SESSION["quser"]['cityid'];

        $this->username = $_SESSION["quser"]['uname'];
        $this->userid = $_SESSION["quser"]['uid'];

        $conditions_reply = array( 'types' => 'cover','linkid' => $this->id);
        $s_reply = spClass('replyinfo');
        $this->reply = $s_reply->findAll($conditions_reply);
		$this -> display('content/cover.html');
	}

    public function photoalbum(){
    	$this->id = $this->spArgs("pid");
    	$this->info = $this->spArgs("info");
    	require('include/sideinfo.php'); // 侧边信息
    	require('include/dataing.php'); // 通用数据调取 

    	$conditions = array( 'id' => $this->id );

	    $s_seoinfo = spClass('seoinfo'); 
		$this->seo = $s_seoinfo->find($conditions);
		$scenerydata = spClass('sceneryinfo'); 
        $this->showscenery = $scenerydata->find($conditions);
    	$conditions = array('types' => $this->info,'linkid' => $this->id );
	    $picture = spClass('pictureinfo');
        $this->pictures = $picture->spPager($this->spArgs('page', 1), 12)->findAll($conditions);
        $this->pager = $picture->spPager()->getPager();

		$conditions = array( 'id' => $this->id );
		if($this->info == "lines")
		{ 
			$s_scenery = spClass('linesinfo'); 
			$this->info = "lines";
		}
		else
		{ 
			$s_scenery = spClass('sceneryinfo');
			$this->info = "scenery"; 
        }
		
        $this->s_scenery = $s_scenery->find($conditions); 
		$this -> display('content/photoalbum.html');
	}

	public function coveralbum(){
    	$this->id = $this->spArgs("pid");

    	$conditions = array( 'id' => $this->id );

    	if($this->info == "lines")
		{ $scenerydata = spClass('linesinfo'); }
		else
		{ $scenerydata = spClass('sceneryinfo'); } 
        $this->showscenery = $scenerydata->find($conditions);

    	$this->info = $this->spArgs("info");
    	$conditions = array('types' => $this->info,'linkid' => $this->id );
	    $picture = spClass('coverinfo');
        $this->pictures = $picture->spPager($this->spArgs('page', 1), 12)->findAll($conditions);
        $this->pager = $picture->spPager()->getPager();

		$conditions = array( 'id' => $this->id );
		if($this->info == "lines")
		{ $s_scenery = spClass('linesinfo'); }
		else
		{ $s_scenery = spClass('sceneryinfo'); }
        $this->s_scenery = $s_scenery->find($conditions); 
		$this -> display('content/coveralbum.html');
	}

    public function uppic(){
    	$this->type = $this->spArgs("type");
    	$this->pid = $this->spArgs("pid");
		$timestamp = time();
		$this->timestamp = $timestamp;
		$this->token = md5('unique_salt' . $timestamp);
		
	    $conditions = array( 'types' => $this->type,'linkid' => $this->pid );
	    $picture = spClass('pictureinfo'); 
        $this->pictures = $picture->findAll($conditions);

		$conditions = array( 'id' => $this->pid );
		$e_user = spClass('albuminfo'); 
        $e_result = $e_user->find($conditions); 
		$this->picurl = $e_result['picurl'];

		$conditions = array( 'id' => $this->pid );
		$e_scenery = spClass('sceneryinfo'); 
        $this->scenery_show = $e_scenery->find($conditions); 
        //dump($this->$scenery_show);

		$this -> display('content/Clientfb/uppic.html');
	}

    public function upcover(){
    	$this->type = $this->spArgs("type");
    	$this->pid = $this->spArgs("pid");
    	$this->url = $this->spArgs("url");
    	$this->picname = rawurldecode($this->spArgs("picname"));
    	//dump($this->picname);
		$timestamp = time();
		$this->timestamp = $timestamp;
		$this->token = md5('unique_salt' . $timestamp);
		
	    $conditions = array( 'types' => $this->type,'linkid' => $this->pid );
	    $picture = spClass('coverinfo'); 
        $this->pictures = $picture->findAll($conditions);

		$conditions = array( 'id' => $this->pid );
		$e_user = spClass('albuminfo'); 
        $e_result = $e_user->find($conditions); 
		$this->picurl = $e_result['picurl'];

		$conditions = array( 'id' => $this->pid );
		$e_scenery = spClass('sceneryinfo'); 
        $this->scenery_show = $e_scenery->find($conditions); 
        //dump($this->$scenery_show);

		$this -> display('content/Clientfb/upcover.html');
	}
	public function uppicdata(){
		
		$data = $this->spArgs(); // $data是提交上来的数据
		if($data['releasedate'] == null)
		{ $releasedate = time(); }
		else
		{ $releasedate = strtotime($data['releasedate']); }
		$newrow = array( // PHP的数组
		'username'=>$_SESSION["quser"]['uname'],
		'picurl'=>$data['picurl'],
		'title'=>$data['title'],
		'releasedate'=>$releasedate,
		'content'=>$data['title'],
		'provinceid'=>$_SESSION["quser"]['provinceid'],
	    'cityid'=>$_SESSION["quser"]['cityid'],
	    'types'=>$data['types'],
	    'linkid'=>$data['linkid'],
		'userid'=>$_SESSION["quser"]['uid']
		);
		$user = spClass('pictureinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		$xml = simplexml_load_file('rules/integral.xml'); // 积分配置
		if($json == 'false'){ 
		   $newid = $user->create($newrow);
            $json = $newid;
		   		$Historyrow = array( // PHP的数组
				'types'=>"picture",
				'title'=>$data['title'],
				'linkid'=>$newid,
				'userid'=>$_SESSION["quser"]['uid'],
				'integral'=>(int)$xml->picture,
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

    public function upcoverdata(){
		
		$data = $this->spArgs(); // $data是提交上来的数据
		if($data['releasedate'] == null)
		{ $releasedate = time(); }
		else
		{ $releasedate = strtotime($data['releasedate']); }
		$newrow = array( // PHP的数组
		'username'=>$_SESSION["quser"]['uname'],
		'picurl'=>$data['picurl'],
		'title'=>$data['title'],
		'releasedate'=>$releasedate,
		'content'=>$data['title'],
		'provinceid'=>$_SESSION["quser"]['provinceid'],
	    'cityid'=>$_SESSION["quser"]['cityid'],
	    'types'=>$data['types'],
	    'linkid'=>$data['linkid'],
		'userid'=>$_SESSION["quser"]['uid']
		);
		$user = spClass('coverinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		$xml = simplexml_load_file('rules/integral.xml'); // 积分配置
		if($json == 'false'){ 
		   $newid = $user->create($newrow);
            $json = $newid;
		   		$Historyrow = array( // PHP的数组
				'types'=>"cover",
				'title'=>$_SESSION["quser"]['uname'],
				'linkid'=>$newid,
				'userid'=>$_SESSION["quser"]['uid'],
				'integral'=>(int)$xml->cover,
				'releasedate'=>$releasedate
				);
				$syshistory = spClass('syshistory'); 
				$syshistory->create($Historyrow);
				$userhistory = spClass('userhistory'); 
				$userhistory->create($Historyrow);

				$user = spClass('lib_user');
				$user_result = $user->find($conditions);
				$conditions = array('uid'=>$_SESSION["quser"]['uid']); 
				$row = array('integral'=>$user_result["integral"] + (int)$xml->cover);
				$user->update($conditions, $row);  // 进行新增操作
		}  // 进行新增操作

		echo($json);
	}
	
	public function topsdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['pid']);
		$row = array('picurl'=>$data['picurl']);
		$user = spClass('albuminfo');
		$json = $user->update($conditions, $row);
		echo($json);
	}

	public function setcover(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id' => $data['id']);
	    $covers = spClass('coverinfo');
        $coverdata = $covers->find($conditions);
        // ====================
		$conditions = array('id'=>$data['pid']);
		$row = array('picurl'=>$coverdata['picurl'],'fromcover'=>$coverdata['username']);
		if($data['type'] == "lines")
		{ $user = spClass('linesinfo'); }
	    else
	    { $user = spClass('sceneryinfo'); }
		$json = $user->update($conditions, $row);

		echo json_encode($json);
	}

	public function setphoto(){
		$data = $this->spArgs(); // $data是提交上来的数据

		$coverinfo = spClass('pictureinfo');
		$rowall = array('top'=>0);
		$conditionsall = array('linkid' => $data['pid']);
		$json = $coverinfo->update($conditionsall, $rowall);

		$row = array('top'=>1);
		$conditions = array('id' => $data['id']);
		$json = $coverinfo->update($conditions, $row);

        $this->pictures = $coverinfo->find($conditions);
        require 'uploadify3.2/photo.php';
        //$resizeimage1 = new resizeimage('uploads/'.$this->pictures['picurl'], "243", "295", "0","_243x295.");
		echo json_encode($this->pictures['picurl']);
	}

	function add(){
        require('include/checking.php'); // 用户身份校验
		$this->types = $this->spArgs("type");
		$this->linkid = $this->spArgs("id");			
		$this->provinceid = $_SESSION["quser"]['provinceid'];
		$this->cityid = $_SESSION["quser"]['cityid']; 
		$this->username = $_SESSION["quser"]['uname'];
		$this->userid = $_SESSION["quser"]['uid'];
		$this -> display('content/Clientfb/album.html');
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
		'picurl'=>'default',
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
		$user = spClass('albuminfo'); 
		$json = json_encode($user->spVerifier($newrow));
		if($json == 'false')
		{ 
		  $newid = $user->create($newrow);

		    $Historyrow = array( // PHP的数组
			'types'=>"album",
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

		  echo($newid);
		}  // 进行新增操作
		else
		{ echo($json); }		
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'title'=>$data['title'],'releasedate'=>$releasedate,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'types'=>$data['types'],'linkid'=>$data['linkid'],'userid'=>$data['userid'],'score'=>$data['score'],'ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('albuminfo');
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
        $user = spClass('albuminfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}
}
