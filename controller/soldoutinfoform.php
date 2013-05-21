<?php

class soldoutinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('soldoutinfo'); 
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
		$citysx=array();
		$i=0;
		while( $i <= count($results)-1)
	    {		  
		  $conditions = array( 'id' => $results[$i]['cityid'] );
		  $c_name = spClass('lib_cityinfo'); 
		  $c_result = $c_name->findAll($conditions); 
		  $results[$i]['cityid'] = $c_result[0]['citystate'];
		  $citysx[] = array("sx" =>$c_result[0]['another']);
		  $i++;
		}
		$this->citysx = $citysx;
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
		$e_user = spClass('soldoutinfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->id = $id; 
		$this->id = $e_result[0]['id']; 
		$this->username = $e_result[0]['username'];
		$this->price = $e_result[0]['price'];
		$this->title = $e_result[0]['title']; 
		$this->ctitle = $e_result[0]['ctitle'];
		$this->ckeyword = $e_result[0]['ckeyword'];
		$this->cdirections = $e_result[0]['cdirections'];
		$this->details = $e_result[0]['details'];
		
		//dump(json_decode($this->details));
		// $ex = json_decode($this->details);
  //       $showdetails=array();
		// foreach ($ex as $value)
		// {
		// 	 $pf = explode("|",$value);
		// 	 $showdetails[] = array("title" => $pf[0],"content" =>$pf[1]);
		// }
		// $this->showdetails = $showdetails;

		$this->tips = $e_result[0]['tips'];
		$this->traffic = $e_result[0]['traffic'];
        $this->instation = $e_result[0]['instation']; 
        $this->childprices = $e_result[0]['childprices']; 
		$this->releasedate = $e_result[0]['releasedate']; 
		$this->content = $e_result[0]['content']; 
        $this->provinceid = $e_result[0]['provinceid']; 
		$this->cityid = $e_result[0]['cityid']; 
		$this->linesid = $e_result[0]['linesid']; 
		$this->areaid = $e_result[0]['areaid']; 
		$this->address = $e_result[0]['address']; 
		$this->tags = $e_result[0]['tags']; 
		$this -> display('admin/content/soldout.html');
	}

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$newrow = array( // PHP的数组
		'id'=>$data['id'],
		'username'=>$data['username'],
		'price'=>$data['price'],
		'title'=>$data['title'],
		'details'=>$data['details'],
        'tips'=>$data['tips'],
		'traffic'=>$data['traffic'],
		'address'=>$data['address'],
		'releasedate'=>$data['releasedate'],
		'content'=>$data['content'],
		'childprices'=>$data['childprices'],
		'instation'=>$data['instation'],
		'provinceid'=>$data['provinceid'],
	    'cityid'=>$data['cityid'],
	    'linesid'=>$data['linesid'],
	    'areaid'=>$data['areaid'],
		'tags'=>$data['tags']
		);
		$user = spClass('soldoutinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		if($json == 'false'){ $user->create($newrow);}  // 进行新增操作
		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$row = array('username'=>$data['username'],'price'=>$data['price'],'title'=>$data['title'],'details'=>$data['details'],'tips'=>$data['tips'],'instation'=>$data['instation'],'traffic'=>$data['traffic'],'address'=>$data['address'],'releasedate'=>$data['releasedate'],'content'=>$data['content'],'provinceid'=>$data['provinceid'],'childprices'=>$data['childprices'],'cityid'=>$data['cityid'],'areaid'=>$data['areaid'],'tags'=>$data['tags'],'ctitle'=>$data['ctitle'],'linesid'=>$data['linesid'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('soldoutinfo');
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
        $user = spClass('soldoutinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

	    	 //不进行二级推荐
    function nosignup(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('issignup'=>0);
        $rulesinfo = spClass('soldoutinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yessignup(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('issignup'=>1);
        $rulesinfo = spClass('soldoutinfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
