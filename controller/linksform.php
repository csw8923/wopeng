<?php

class linksform extends basis
{

	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$limit = $this->spArgs("limit");
		$this->filter = $filter;  // 获取搜索关键字
		$this->limit = $limit;  // 获取几个数据分页
		$links = spClass('links'); 
		$conditions = 'name like '.$links->escape('%'.$filter.'%');		
        $result = $links->spPager($this->spArgs('page', 1), $limit)->findAll($conditions,"id desc");  // 分页
		$this->result = $result; 
		$this->pager = $links->spPager()->getPager(); // 声明分页
		
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_links = spClass('links'); 
        $e_result = $e_links->findAll($conditions); 
		$this->id = $id; 
		$this->name = $e_result[0]['name'];
		$this->url = $e_result[0]['url']; 
		$this -> display('admin/form/linksform.html');
	}
	    
	// 添加数据
	function adddata(){
		$data = $this->spArgs(); 
		$newrow = array( 
		'name' => trim($data['name']),	
		'url'=>trim($data['url']),
		);
		$links = spClass('links'); 
		$json = json_encode($links->spVerifier($newrow));
		if($json == 'false'){ $links->create($newrow);} 
		echo($json);
	}
	
	// 编辑数据
	function editdata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		$row = array('name'=>trim($data['name']),'url'=>trim($data['url']));
		$links = spClass('links');
		$json = json_encode($links->spVerifier($row));
		if($json == 'false'){$links->update($conditions, $row);} 
		echo($json);
	}
	
	// 删除数据
	function deldata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']);
        $links = spClass('links');
        $data['msg'] = $links->delete($conditions);	
		echo ( $data['msg'] );
	}
}
