<?php

class windisplayform extends basis
{

	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$limit = $this->spArgs("limit");
		$this->opclass = $this->spArgs("opclass");
		$this->page = $this->spArgs("page");
		if($this->page == 0)
		{
		  $this->page = 1;
		}

		$this->filter = $filter;  // 获取搜索关键字
		$this->limit = $limit;  // 获取几个数据分页
		$windisplay = spClass('windisplay');
		if($this->opclass == '')
		{	  
		   $conditions = 'name like '.$windisplay->escape('%'.$filter.'%');	
        }
	    else
		{
		   $conditions = 'class = "'.$this->opclass.'" and name like '.$windisplay->escape('%'.$filter.'%');	
		}	
        $result = $windisplay->spPager($this->spArgs('page', 1), $limit)->findAll($conditions,"seq desc,id desc");  // 分页
		$this->result = $result; 
		//dump($this->result);
		$this->pager = $windisplay->spPager()->getPager(); // 声明分页
		
		$this->id = $this->spArgs("id");
		$conditions = array( 'id' => $this->id );
		$e_windisplay = spClass('windisplay'); 
        $e_result = $e_windisplay->findAll($conditions); 
		$this->name = $e_result[0]['name'];
		$this->picurl = $e_result[0]['picurl']; 
		$this->minipic = $e_result[0]['minipic']; 
		$this->info = $e_result[0]['info']; 
		$this->class = $e_result[0]['class'];
		$this->seq = $e_result[0]['seq'];
		$this->url = $e_result[0]['url'];

        // 图片上传
		$timestamp = time();
		$this->timestamp = $timestamp;
		$this->token = md5('unique_salt' . $timestamp);

		// 控制器数据 xml 格式
		$xml = simplexml_load_file('rules/windisplay.xml');
		$num = count($xml);
		$i=0;
		$infocontroller=array();
		while($i<=$num-1)
		{
		  $name  = $xml-> note[$i] -> name; 
		  $event  = $xml-> note[$i] -> event; 
		  $infocontroller[] = array('name'=>$name,'event'=>$event);
		  $i++;
	    }
	    $this->infocontroller = $infocontroller; // 权限分配数据库连接

		$this -> display('admin/form/windisplayform.html');
	}
	    
	// 添加数据
	function adddata(){
		$data = $this->spArgs(); 
		$newrow = array( 
		'name' => trim($data['name']),	
		'picurl' => trim($data['picurl']),
		'minipic' => trim($data['minipic']),
		'info' => trim($data['info']),
		'class' => trim($data['class']),
		'seq' => trim($data['seq']),
		'url'=>trim($data['url']),
		);
		$windisplay = spClass('windisplay'); 
		$json = json_encode($windisplay->spVerifier($newrow));
		if($json == 'false'){ $windisplay->create($newrow);} 
		echo($json);
	}
	
	// 编辑数据
	function editdata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		$row = array('name'=>trim($data['name']),'picurl'=>trim($data['picurl']),'minipic'=>trim($data['minipic']),'info'=>trim($data['info']),'class' => trim($data['class']),'seq' => trim($data['seq']),'url'=>trim($data['url']));
		$windisplay = spClass('windisplay');
		$json = json_encode($windisplay->spVerifier($row));
		if($json == 'false'){$windisplay->update($conditions, $row);} 
		echo($json);
	}
	
	// 删除数据
	function deldata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']);
        $windisplay = spClass('windisplay');
        $data['msg'] = $windisplay->delete($conditions);	
		echo ( $data['msg'] );
	}

	//不进行二级推荐
    function noapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>0);
        $rulesinfo = spClass('windisplay');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yesapproval(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('ispass'=>1);
        $rulesinfo = spClass('windisplay');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
