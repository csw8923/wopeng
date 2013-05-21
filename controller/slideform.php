<?php

class slideform extends basis
{

	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$limit = $this->spArgs("limit");
		$this->filter = $filter;  // 获取搜索关键字
		$this->limit = $limit;  // 获取几个数据分页
		$slide = spClass('slide'); 
		$conditions = 'name like '.$slide->escape('%'.$filter.'%');		
        $result = $slide->spPager($this->spArgs('page', 1), $limit)->findAll($conditions,"id desc");  // 分页
		$this->result = $result; 
		$this->pager = $slide->spPager()->getPager(); // 声明分页
		
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_slide = spClass('slide'); 
        $e_result = $e_slide->findAll($conditions); 
		$this->id = $id; 
		$this->name = $e_result[0]['name'];
		$this->picurl = $e_result[0]['picurl']; 
		$this->url = $e_result[0]['url']; 

        // 图片上传
		$timestamp = time();
		$this->timestamp = $timestamp;
		$this->token = md5('unique_salt' . $timestamp);

		$this -> display('admin/form/slideform.html');
	}
	    
	// 添加数据
	function adddata(){
		$data = $this->spArgs(); 
		$newrow = array( 
		'name' => trim($data['name']),	
		'picurl' => trim($data['picurl']),
		'url'=>trim($data['url']),
		);
		$slide = spClass('slide'); 
		$json = json_encode($slide->spVerifier($newrow));
		if($json == 'false'){ $slide->create($newrow);} 
		echo($json);
	}
	
	// 编辑数据
	function editdata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		$row = array('name'=>trim($data['name']),'picurl'=>trim($data['picurl']),'url'=>trim($data['url']));
		$slide = spClass('slide');
		$json = json_encode($slide->spVerifier($row));
		if($json == 'false'){$slide->update($conditions, $row);} 
		echo($json);
	}
	
	// 删除数据
	function deldata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']);
        $slide = spClass('slide');
        $data['msg'] = $slide->delete($conditions);	
		echo ( $data['msg'] );
	}

	//不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('slide');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('slide');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
