<?php

class messageinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		$this->sclassid = $classid;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('messageinfo'); 
		if(empty($filter) && empty($classid))
		{ 
		  $conditions = "releasedate >= '".date('Ymd',strtotime("-15 day"))."'";
		  $results = $user->findAll($conditions,'id DESC',NULL,"150"); 
		}
		elseif(!empty($classid))
		{
		  $conditions = array( 'classid' => $classid );
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
		  $conditions = array( 'id' => $results[$i]['classid'] );
		  $c_name = spClass('lib_category'); 
		  $c_result = $c_name->findAll($conditions); 
		  $results[$i]['classid'] = $c_result[0]['cname'];
		  $i++;
		}
		$this->results = $results; 
		// 列出信息内容
		
		$cataObj = spClass("lib_category");
		$this->resultclass = $cataObj->getCatalogList();
	
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_user = spClass('messageinfo'); 
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
        $this->classid = $e_result[0]['classid']; 
		$this -> display('admin/content/message.html');
	}

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$releasedate = strtotime($data['releasedate']);
		$newrow = array( // PHP的数组
		'id'=>$data['id'],
		'username'=>$data['username'],
		'picurl'=>$data['picurl'],
		'title'=>$data['title'],
		'releasedate'=>$releasedate,
		'content'=>$data['content'],
		'classid'=>$data['classid']
		);
		$user = spClass('messageinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		if($json == 'false'){ $user->create($newrow);}  // 进行新增操作
		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$releasedate = strtotime($data['releasedate']);
		$row = array('username'=>$data['username'],'picurl'=>$data['picurl'],'title'=>$data['title'],'releasedate'=>$releasedate,'content'=>$data['content'],'classid'=>$data['classid']);
		$user = spClass('messageinfo');
		$json = json_encode($user->spVerifier($row));
		if($json == 'false'){$user->update($conditions, $row);} 
		echo($json);
	}
	
	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
        $user = spClass('messageinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}
}
