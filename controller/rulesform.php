<?php

class rulesform extends basis
{

	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$limit = $this->spArgs("limit");
		$this->filter = $filter;  // 获取搜索关键字
		$this->limit = $limit;  // 获取几个数据分页
		//$conditions = "dockcompany like '%".$filter."%'"; // 搜索关键字查询
		$rulesinfo = spClass('rulesinfo'); 
		$conditions = 'rulename like '.$rulesinfo->escape('%'.$filter.'%');
        $result = $rulesinfo->spPager($this->spArgs('page', 1), $limit)->findAll($conditions);  // 分页
		$i=0;
		while( $i <= count($result)-1)
	    {		  
		  $conditions = array( 'id' => $result[$i]['part'] );
		  $c_name = spClass('lib_category'); 
		  $c_result = $c_name->find($conditions); 
		  $result[$i]['part'] = $c_result['cname'];
		  $i++;
		}
		$this->result = $result; 
		$this->pager = $rulesinfo->spPager()->getPager(); // 声明分页
		
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_rulesinfo = spClass('rulesinfo'); 
        $e_result = $e_rulesinfo->find($conditions); 
		$this->id = $id; 
		$this->rulename = $e_result['rulename'];
		$this->rulefile = $e_result['rulefile']; 
		$this->geturl = $e_result['geturl']; 
		$this->getlist = $e_result['getlist']; 
		$this->getlink = $e_result['getlink']; 
		$this->autocj = $e_result['autocj']; 
		$this->gethost = $e_result['gethost']; 
		$this->gettitle = $e_result['gettitle']; 
		$this->patterns = $e_result['patterns']; 
		$this->replacements = $e_result['replacements']; 
		$this->universal = $e_result['universal']; 
		$this->getdetailurl = $e_result['getdetailurl']; 
		$this->getrange = $e_result['getrange']; 
		$this->dpatterns = $e_result['dpatterns']; 
		$this->dreplacements = $e_result['dreplacements'];
		$this->part = $e_result['part']; 
		$this->compatible = $e_result['compatible']; 
		if(!empty($e_result['addtime'])){
		 $this->addtime = date('Y-m-d H:i:s',$e_result['addtime']);
		}
		
		$cataObj = spClass("lib_category");
		$this->resultclass = $cataObj->getCatalogList();
		
		$this -> display('admin/form/rulesform.html');
	}
	
	function referpage(){
		// 前台页面权限
        require('include/checking.php'); // 用户身份校验

		$conditions = array( 'userid' => $_SESSION["quser"]['id'] );
		$rulesinfo = spClass('rulesinfo'); 
        $result = $rulesinfo->spPager($this->spArgs('page', 1), 5)->findAll($conditions,'id DESC'); 
		$this->result = $result; 
		$this->pager = $rulesinfo->spPager()->getPager();
		
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_rulesinfo = spClass('rulesinfo'); 
        $e_result = $e_rulesinfo->findAll($conditions); 
		$this->id = $id;
		$this->rulename = $e_result[0]['rulename']; 
		$this->rulefile = $e_result[0]['rulefile']; 
		$this->part = $e_result[0]['part']; 
		if(!empty($e_result[0]['addtime'])){
		 $this->addtime = date('Y-m-d H:i:s',$e_result[0]['addtime']);
		}
		$this -> display('dockform.html');
	}
    
	// 添加数据
	function adddata(){
		$data = $this->spArgs(); 
		$newrow = array( 
		'rulename' => trim($data['rulename']),	
		'rulefile'=>trim($data['rulefile']),
		'part'=>trim($data['part']),
		'addtime'=>trim(time()),
		'geturl'=>$data['geturl'],
		'getlist'=>$data['getlist'],
		'getlink'=>$data['getlink'],
		'autocj'=>$data['autocj'],
		'gethost'=>$data['gethost'],
		'gettitle'=>$data['gettitle'],
		'patterns'=>$data['patterns'],
		'replacements'=>$data['replacements'],
		'universal'=>$data['universal'],
		'getdetailurl'=>$data['getdetailurl'],
		'getrange'=>$data['getrange'],
		'dpatterns'=>$data['dpatterns'],
		'compatible'=>$data['compatible'],
		'dreplacements'=>$data['dreplacements']
		);
		$rulesinfo = spClass('rulesinfo'); 
		$json = json_encode($rulesinfo->spVerifier($newrow));
		if($json == 'false'){ $rulesinfo->create($newrow);} 
		echo($json);
	}
	
	// 编辑数据
	function editdata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']);
		$row = array('rulename' => trim($data['rulename']),'rulefile'=>trim($data['rulefile']),'part'=>trim($data['part']),'addtime'=>trim(time()),'geturl'=>$data['geturl'],'getlist'=>$data['getlist'],'getlink'=>$data['getlink'],'autocj'=>$data['autocj'],'gethost'=>$data['gethost'],'gettitle'=>$data['gettitle'],'patterns'=>$data['patterns'],'replacements'=>$data['replacements'],'universal'=>$data['universal'],'getdetailurl'=>$data['getdetailurl'],'getrange'=>$data['getrange'],'dpatterns'=>$data['dpatterns'],'compatible'=>$data['compatible'],'dreplacements'=>$data['dreplacements']);
		$rulesinfo = spClass('rulesinfo');
		$json = json_encode($rulesinfo->spVerifier($row));
		if($json == 'false'){$rulesinfo->update($conditions, $row);} 
		echo($json);
	}
	
	// 删除数据
	function deldata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']);
        $rulesinfo = spClass('rulesinfo');
        $data['msg'] = $rulesinfo->delete($conditions);	
		echo ( $data['msg'] );
	}
	// 初始化审核
	function initialize(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		$row = array('approval'=>0,'reason'=>"");
		$rulesinfo = spClass('rulesinfo');
	    $data['msg'] = $rulesinfo->update($conditions, $row);
		echo ( $data['msg'] );
	}
	// 进行审核
	function approval(){
		$data = $this->spArgs();
		$realname = '【审核人：'.$_SESSION["userinfo"]['realname'].'】'; // 获取审核人
		$conditions = array('id'=>$data['id']); 
		
		$row = array('approval'=>1,'reason'=>$realname);
		$rulesinfo = spClass('rulesinfo');
	    $data['msg'] = $rulesinfo->update($conditions, $row);
		echo ( $data['msg'] );
	}
	//进行拒绝
	function refuse(){
		$data = $this->spArgs(); 
		$conditions = array('id'=>$data['id']); 
		$row = array('approval'=>0);
		$rulesinfo = spClass('rulesinfo');
	    $data['msg'] = $rulesinfo->update($conditions, $row);
		echo ( $data['msg'] );
	}
}
