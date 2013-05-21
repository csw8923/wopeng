<?php

class youjiinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('youjiinfo'); 
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
		$e_user = spClass('youjiinfo'); 
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
		$this -> display('admin/content/youji.html');
	}

    // 详细内容
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
			$e_youji = spClass('youjiinfo'); 
	        $out_youji = $e_youji->find($conditions);
	        require('include/inside.php'); // 通用数据调取
	        $out_youji['content'] = inside($out_youji['content']);
			$this->out_youji = $out_youji;

			$this->info = $this->out_youji['types']; // 类型
            $this->sid = $this->out_youji['linkid'];

		    // 省
			$conditions_province = array( 'id' => $this->out_youji['provinceid'] );
			$t_province = spClass('lib_cityinfo'); 
			$this->province = $t_province->find($conditions_province); 

            // 市
			$conditions_city = array( 'id' => $this->out_youji['cityid'] );
			$t_city = spClass('lib_cityinfo'); 
			$this->city = $t_city->find($conditions_city);

			// 区
			$conditions_area = array( 'id' => $this->out_youji['areaid'] );
			$t_area = spClass('lib_cityinfo'); 
			$this->area = $t_area->find($conditions_area); 

			//dump($this->city);
            require('include/infomore.php'); // 通用数据调取 
	        require('include/conent.php'); // 通用数据调取 
            $clicknumber = $this->out_youji['clicknumber'];
            $clicknumber++;
            $e_youji->update($conditions,array('clicknumber'=>$clicknumber));
            
            // 自动获取封面
            preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png|jpeg))\"?.+>/i',$this->out_youji['content'],$match);
            $mrpicurl = "/tpl/images/pic15thumb_01.jpeg";
		    if($this->out_youji['picurl'] == $mrpicurl && !empty($match[1]))
	    	{ 
	    	  $conditions_uppic = array( 'id' => $this->id );
              $e_youji->update($conditions_uppic,array('picurl'=>$match[1]));
            }
            // 自动获取封面
            // ===================================
			$this->types = 'youji';
			$this->linkid = $this->out_youji["linkid"];

			$this->provinceid = $_SESSION["quser"]['provinceid'];
			$this->cityid = $_SESSION["quser"]['cityid']; 

			$this->username = $_SESSION["quser"]['uname'];
			$this->userid = $_SESSION["quser"]['uid'];

			$conditions_reply = array( 'types' => 'youji','linkid' => $this->id,'ispass' => 0);
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

			if($this->out_youji == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('content/youji.html');
		    }
	    }
	    else
	    {
		    require('include/dataing.php'); // 通用数据调取 
	    	$conditions = array('types' => $this->info,'linkid' => $this->id,'ispass' => 0);
		    $e_youji = spClass('youjiinfo');
            //标题栏的所有游记列表
            $this->youji = $e_youji->spPager($this->spArgs('page', 1), 2)->findAll($conditions);
            $this->pager = $e_youji->spPager()->getPager();
//            dump( $this->pager);
            if($this->youji == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('list/youji.html');
		    }
	    }
	}

	function add(){
		require('include/checking.php'); // 用户身份校验
		$this->types = $this->spArgs("type");
		$this->linkid = $this->spArgs("id");

		$conditionst = array( 'id' => $this->linkid);
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

        // 调取用户信息
		$this->username = $_SESSION["quser"]['uname'];
		$this->userid = $_SESSION["quser"]['uid'];

		$this -> display('content/Clientfb/youji.html');
	}

	function edit(){
		require('include/checking.php'); // 用户身份校验
		$this->types = $this->spArgs("type");
		$this->linkid = $this->spArgs("id");	
		$this->funs = 'edit';

		$conditionst = array('id' => $this->linkid);
	    $e_youji = spClass('youjiinfo');
	    $this->out_youji = $e_youji->find($conditionst);
	    if($_SESSION["quser"]['uid'] != $this->out_youji['userid'])
		{$this->error("对不起,你没有编辑权限！！",spUrl("main","index"));}

		$conditions = array( 'id' => $this->out_youji['linkid']);
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

        // 调取用户信息
		$this->username = $_SESSION["quser"]['uname'];
		$this->userid = $_SESSION["quser"]['uid'];

		$this -> display('content/Clientfb/youji.html');
	}
	
	function useredit(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$row = array('title'=>$data['title'],'content'=>$data['content']);
		$user = spClass('youjiinfo');
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

		$newrow = array( // PHP的数组
		'id'=>$data['id'],
		'username'=>$data['username'],
		'picurl'=> "/tpl/images/pic15thumb_01.jpeg",
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
		$user = spClass('youjiinfo');
        $json = json_encode($user->spVerifier($newrow));
        $xml = simplexml_load_file('rules/integral.xml'); // 积分配置
		if($json == 'false'){
			$newid = $user->create($newrow);
            $json = json_encode($newid);
			$Historyrow = array( // PHP的数组
			'types'=>"youji",
			'title'=>$data['title'],
			'linkid'=>$newid,
			'userid'=>$_SESSION["quser"]['uid'],
			'integral'=>(int)$xml->youji,
			'releasedate'=>$releasedate
			);
			$syshistory = spClass('syshistory'); 
			$syshistory->create($Historyrow);
			$userhistory = spClass('userhistory'); 
			$userhistory->create($Historyrow);
            
			$user = spClass('lib_user');
			$user_result = $user->find($conditions);
			$conditions = array('uid'=>$_SESSION["quser"]['uid']); 
			$row = array('integral'=>$user_result["integral"] + (int)$xml->youji);
			$user->update($conditions, $row);  // 进行新增操作

		}  // 进行新增操作
		echo($json);
}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'title'=>$data['title'],'releasedate'=>$releasedate,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'types'=>$data['types'],'linkid'=>$data['linkid'],'userid'=>$data['userid'],'score'=>$data['score'],'clicknumber'=>$data['clicknumber'],'ispass'=>$data['ispass'],'ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('youjiinfo');
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
        $user = spClass('youjiinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

	 //不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('youjiinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('youjiinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
