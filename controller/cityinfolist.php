<?php

class cityinfolist extends basis
{
	function index(){
		$cataObj = spClass("lib_cityinfo");  // 文章分类列表
		$this->results = $cataObj->getCatalogList();
	    //dump($this->results);
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_user = spClass('lib_cityinfo'); 
        $e_result = $e_user->findAll($conditions);
		$this->id = $id; 
		$this->pid = $e_result[0]['pid']; 
		$this->citystate = $e_result[0]['citystate'];
		if(!empty($e_result[0]['seq']))
		{ $this->seq = $e_result[0]['seq']; }
	    else
	    { $this->seq = 0; }
		$this->ismenu = $e_result[0]['ismenu'];
		$this->part = $e_result[0]['part'];
		$this->another = $e_result[0]['another']; 
		$this->title = $e_result[0]['title']; 
		$this->keywords = $e_result[0]['keywords']; 
		$this->servicephone = $e_result[0]['servicephone']; 
		$this->description = $e_result[0]['description']; 
		$this -> display('admin/content/cityinfolist.html');
	}

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$newrow = array( // PHP的数组
		'pid'=>$data['pid'],
		'citystate'=>$data['citystate'],
		'seq'=>$data['seq'],
		'ismenu'=>$data['ismenu'],
		'part'=>$data['part'],
		'another'=>$data['another'],
		'title'=>$data['title'],
		'keywords'=>$data['keywords'],
		'servicephone'=>$data['servicephone'],
		'description'=>$data['description']
		);
		$user = spClass('lib_cityinfo'); 
		$json = json_encode($user->spVerifier($newrow));
		if($json == 'false'){ $user->create($newrow);}
		echo($json);
	}
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
		$row = array('pid'=>$data['pid'],'citystate'=>$data['citystate'],'seq'=>$data['seq'],'ismenu'=>$data['ismenu'],'part'=>$data['part'],'another'=>$data['another'],'title'=>$data['title'],'keywords'=>$data['keywords'],'servicephone'=>$data['servicephone'],'description'=>$data['description']);
		$user = spClass('lib_cityinfo');
		$json = json_encode($user->spVerifier($row));
		if($json == 'false'){$user->update($conditions, $row);}  // 进行更新操作
		echo($json);
	}
	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); // 构造条件
        $user = spClass('lib_cityinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] ); // 发送到模板
	}
}
