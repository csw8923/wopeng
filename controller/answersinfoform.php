<?php

class answersinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('answersinfo'); 
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
		$e_user = spClass('answersinfo'); 
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
        $this->clicknumber = $e_result[0]['clicknumber'];
        $this->ispass = $e_result[0]['ispass'];
		$this -> display('admin/content/answers.html');
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
			$e_answers = spClass('answersinfo'); 
	        $out_answers = $e_answers->find($conditions);
	        require('include/inside.php'); // 通用数据调取
	        $out_answers['content'] = inside($out_answers['content']);
			$this->out_answers = $out_answers;
	        $this->info = $this->out_answers['types']; // 类型
	        $this->sid = $this->out_answers['linkid'];
	        
			// 省
			$conditions_province = array( 'id' => $this->out_answers['provinceid'] );
			$t_province = spClass('lib_cityinfo'); 
			$this->province = $t_province->find($conditions_province); 

            // 市
			$conditions_city = array( 'id' => $this->out_answers['cityid'] );
			$t_city = spClass('lib_cityinfo'); 
			$this->city = $t_city->find($conditions_city);

			// 区
			$conditions_area = array( 'id' => $this->out_answers['areaid'] );
			$t_area = spClass('lib_cityinfo'); 
			$this->area = $t_area->find($conditions_area); 

	        require('include/infomore.php'); // 通用数据调取 
	        require('include/conent.php'); // 通用数据调取 
            $clicknumber = $this->out_answers['clicknumber'];
            $clicknumber++;
            $conditions_ans = array( 'id' => $this->id );
            $e_answers->update($conditions_ans,array('clicknumber'=>$clicknumber));
			// ===================================
			$this->types = 'answers';
            $this->linkid =  $this->out_answers["linkid"];

			$this->provinceid = $_SESSION["quser"]['provinceid'];
			$this->cityid = $_SESSION["quser"]['cityid']; 

			$this->username = $_SESSION["quser"]['uname'];
			$this->userid = $_SESSION["quser"]['uid'];

			$conditions_reply = array( 'types' => 'answers','linkid' => $this->id);
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
            
			if($this->out_answers == null)
			{
				import(APP_PATH.'/tpl/404.php');exit();
			}
			else
			{
			    $this -> display('content/answers.html');
		    }
	    }
	    else
	    {
	    	require('include/dataing.php'); // 通用数据调取 
	    	$conditions = array('types' => $this->info,'linkid' => $this->id,'ispass' => 0);
		    $e_answers = spClass('answersinfo');
            $this->answers = $e_answers->spPager($this->spArgs('page', 1), 10)->findAll($conditions);
            $this->pager = $e_answers->spPager()->getPager();
            $showtext=array();
            $replies=array();
			foreach($this->answers as $value){ 
                // 回复数查询
				$conditions = array( 'types' => 'answers','linkid' => $value['id']);
				$e_user = spClass('replyinfo'); 
		        $e_result = $e_user->findAll($conditions); 
		        $replies[] = count($e_result);
                // 回复数查询
				$conditions = array( 'id' => $value['linkid']);
				if($this->info == 'lines')
				{
				    $s_scenery = spClass('linesinfo'); 
				}
				 else
				{
                    $s_scenery = spClass('sceneryinfo');
				}
				$this->show = $s_scenery->find($conditions);
				$showtext[] = array('id'=>$this->show["id"],'title'=>$this->show["title"]);
			}

			$conditions = array( 'id' => $this->id);
			if($this->info == 'lines')
			{
			    $s_sceneryseo = spClass('linesinfo'); 
			}
			 else
			{
                $s_sceneryseo = spClass('sceneryinfo');
			}
			$this->showseo = $s_sceneryseo->find($conditions);

			$this->showtext = $showtext;
			$this->replies = $replies;
			$this -> display('list/answers.html');
	    }
	}

	function add(){
        require('include/checking.php'); // 用户身份校验
		$this->types = $this->spArgs("type");
		$this->linkid = $this->spArgs("id");	

    	$conditionst = array('id' => $this->linkid);
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
		$this -> display('content/Clientfb/answers.html');
	}

	function edit(){
        require('include/checking.php'); // 用户身份校验
		$this->types = $this->spArgs("type");
		$this->linkid = $this->spArgs("id");	
		$this->funs = 'edit';

    	$conditionst = array('id' => $this->linkid);
	    $e_answers = spClass('answersinfo');
	    $this->answers = $e_answers->find($conditionst);
	    if($_SESSION["quser"]['uid'] != $this->answers['userid'])
		{$this->error("对不起,你没有编辑权限！！",spUrl("main","index"));}
        //dump($this->answers);
		$conditions = array( 'id' => $this->answers['linkid']);
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
		$this -> display('content/Clientfb/answers.html');
	}
	
    function useredit(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$row = array('title'=>$data['title'],'content'=>$data['content']);
		$user = spClass('answersinfo');
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
		$user = spClass('answersinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		$xml = simplexml_load_file('rules/integral.xml'); // 积分配置
		if($json == 'false'){
	     	$newid =  $user->create($newrow);
            $json = json_encode($newid);
	        $Historyrow = array( // PHP的数组
			'types'=>"answers",
			'title'=>$data['title'],
			'linkid'=>$newid,
			'userid'=>$_SESSION["quser"]['uid'],
			'integral'=>(int)$xml->answers,
			'releasedate'=>$releasedate
			);
			$syshistory = spClass('syshistory'); 
			$syshistory->create($Historyrow);
			$userhistory = spClass('userhistory'); 
			$userhistory->create($Historyrow);

			$user = spClass('lib_user');
			$user_result = $user->find($conditions);
			$conditions = array('uid'=>$_SESSION["quser"]['uid']); 
			$row = array('integral'=>$user_result["integral"] + (int)$xml->answers);
			$user->update($conditions, $row);  // 进行新增操作

	    }  // 进行新增操作

		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'title'=>$data['title'],'releasedate'=>$releasedate,'content'=>$data['content'],'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'userid'=>$data['userid'],'score'=>$data['score'],'clicknumber'=>$data['clicknumber'],'ispass'=>$data['ispass'],'ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'types'=>$data['types'],'linkid'=>$data['linkid'],'cdirections'=>$data['cdirections']);
		$user = spClass('answersinfo');
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
        $user = spClass('answersinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

		 //不进行二级推荐
    function notop(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('hotnumber'=>0);
        $rulesinfo = spClass('answersinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yestop(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('hotnumber'=>1);
        $rulesinfo = spClass('answersinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
	 //不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('answersinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('answersinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
