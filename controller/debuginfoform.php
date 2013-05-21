<?php

class debuginfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字

	    $user = spClass('debuginfo'); 
	    if($_SESSION["quser"]['uid'] == 1){
          	// 列出信息内容 且 修改分类名称
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
			$this->results = $results; 
			// 列出信息内容
	    }
	    else
	    {
	    	          	// 列出信息内容 且 修改分类名称
			if(empty($filter) && empty($classid))
			{ 
			  $conditions = "userid = ".$_SESSION["quser"]['uid']." and releasedate >= '".date('Ymd',strtotime("-15 day"))."'";
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
			$this->results = $results; 
			// 列出信息内容
	    }
	
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_user = spClass('debuginfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->uid = $id; 
		$this->id = $e_result[0]['id']; 
		$this->username = $e_result[0]['username'];
		$this->title = $e_result[0]['title'];

		if(!empty($e_result[0]['releasedate'])){
		 $this->releasedate = date('Y-m-d H:i:s',$e_result[0]['releasedate']); 
		}		
		$this->content = $e_result[0]['content'];
        $this->ispass = $e_result[0]['ispass'];
		$this -> display('admin/content/debug.html');
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
		'releasedate'=>$releasedate,
		'content'=>$data['content']
		);
		$user = spClass('debuginfo'); 
		$newid = $user->create($newrow);
	    $json = json_encode($newid);
		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'title'=>$data['title'],'releasedate'=>$releasedate,'content'=>$data['content'],'ispass'=>$data['ispass']);
		$user = spClass('debuginfo');
		$user->update($conditions, $row);
		echo(json_encode($data));
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
        $user = spClass('debuginfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

	 //不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('debuginfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('debuginfo');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
