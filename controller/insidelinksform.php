<?php

class insidelinksform extends basis
{

	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$limit = $this->spArgs("limit");
		$this->filter = $filter;  // 获取搜索关键字
		$this->limit = $limit;  // 获取几个数据分页
		$insidelinks = spClass('insidelinks'); 
		$conditions = 'name like '.$insidelinks->escape('%'.$filter.'%');		
        $result = $insidelinks->spPager($this->spArgs('page', 1), $limit)->findAll($conditions,"id desc");  // 分页
		$this->result = $result; 
		$this->pager = $insidelinks->spPager()->getPager(); // 声明分页
		
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_insidelinks = spClass('insidelinks'); 
        $e_result = $e_insidelinks->findAll($conditions); 
		$this->id = $id; 
		$this->name = $e_result[0]['name'];
		$this->url = $e_result[0]['url']; 
		$this -> display('admin/form/insidelinksform.html');
	}
	    
	// 添加数据
	function adddata(){
		$data = $this->spArgs(); 
		$newrow = array( 
		'name' => trim($data['name']),	
		'url'=>trim($data['url']),
		);
		$insidelinks = spClass('insidelinks'); 
		$json = json_encode($insidelinks->spVerifier($newrow));
		if($json == 'false'){ $insidelinks->create($newrow);} 
		echo($json);
	}
	
	// 编辑数据
	function editdata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		$row = array('name'=>trim($data['name']),'url'=>trim($data['url']));
		$insidelinks = spClass('insidelinks');
		$json = json_encode($insidelinks->spVerifier($row));
		if($json == 'false'){$insidelinks->update($conditions, $row);} 
		echo($json);
	}
	
	// 删除数据
	function deldata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']);
        $insidelinks = spClass('insidelinks');
        $data['msg'] = $insidelinks->delete($conditions);	
		echo ( $data['msg'] );
	}
}
