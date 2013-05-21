<?php

class chainform extends basis
{

	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$limit = $this->spArgs("limit");
		$this->filter = $filter;  // 获取搜索关键字
		$this->limit = $limit;  // 获取几个数据分页
		//$conditions = "dockcompany like '%".$filter."%'"; // 搜索关键字查询
		$rulesinfo = spClass('chain'); 
		$conditions = 'keywords like '.$rulesinfo->escape('%'.$filter.'%');
    $result = $rulesinfo->spPager($this->spArgs('page', 1), $limit)->findAll($conditions,'seq ASC');  // 分页
		$this->result = $result; 
		$this->pager = $rulesinfo->spPager()->getPager(); // 声明分页
		
		$id = $this->spArgs("id");
		$conditions = array( 'id' => $id );
		$e_rulesinfo = spClass('chain'); 
        $e_result = $e_rulesinfo->find($conditions); 
		$this->id = $id; 
		$this->keywords = $e_result['keywords'];
		$this->links = $e_result['links']; 
		$this->types = $e_result['types']; 
		$this->seq = $e_result['seq']; 
		$this->sort = $e_result['sort']; 
		$this->useful = $e_result['useful']; 
		$this->used = $e_result['used'];
        $this->istop = $e_result['istop'];
        $this->ctitle = $e_result['ctitle'];
        $this->ckeyword = $e_result['ckeyword'];
        $this->cdirections = $e_result['cdirections'];
        $this -> display('admin/form/chainform.html');
	}
	
	function sword(){
		$name = urldecode($this->spArgs("name"));
		$rulesinfo = spClass('chain'); 
		$conditions = 'keywords like '.$rulesinfo->escape('%'.$name.'%');
        $result = $rulesinfo->findAll($conditions);  // 分页
        if($result){
			foreach($result as $k=>$v){
				$str.='<tr><td>'.$v['keywords'].'</td></tr>';
			}
			//生成表格
			echo $str="<table>$str</table>";
		}
	}

	function referpage(){
		// 前台页面权限
        require('include/checking.php'); // 用户身份校验

		$conditions = array( 'userid' => $_SESSION["quser"]['id'] );
		$rulesinfo = spClass('chain'); 
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
		$this->used = $e_result[0]['used']; 
		if(!empty($e_result[0]['addtime'])){
		 $this->addtime = date('Y-m-d H:i:s',$e_result[0]['addtime']);
		}
		$this -> display('dockform.html');
	}
    
	// 添加数据
	function adddata(){
		$data = $this->spArgs(); 
		$newrow = array( 
		'keywords' => trim($data['keywords']),	
		'links'=>trim($data['links']),
		'types'=>trim($data['types']),
		'seq'=>trim($data['seq']),
		'sort'=>trim($data['sort']),
		'useful'=>trim($data['useful']),
		'used'=>trim($data['used'])
		);
		$rulesinfo = spClass('chain'); 
		$json = json_encode($rulesinfo->spVerifier($newrow));
		if($json == 'false'){ $rulesinfo->create($newrow);} 
		echo($json);
	}
	
	// 编辑数据
	function editdata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']);
		$row = array('keywords' => trim($data['keywords']),'links'=>trim($data['links']),'types'=>trim($data['types']),'useful'=>trim($data['useful']),'seq'=>trim($data['seq']),'sort'=>trim($data['sort']),'used'=>trim($data['used']),'ctitle'=>trim($data['ctitle']),'ckeywords'=>trim($data['ckeywords']),'cdirections'=>trim($data['cdirections']));
		$rulesinfo = spClass('chain');
		$json = json_encode($rulesinfo->spVerifier($row));
		if($json == 'false'){$rulesinfo->update($conditions, $row);} 
		echo($json);
	}
	
	// 删除数据
	function deldata(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']);
        $rulesinfo = spClass('chain');
        $data['msg'] = $rulesinfo->delete($conditions);	
		echo ( $data['msg'] );
	}
	// 初始化审核
	function initialize(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		$row = array('useful'=>0,'reason'=>"");
		$rulesinfo = spClass('chain');
	    $data['msg'] = $rulesinfo->update($conditions, $row);
		echo ( $data['msg'] );
	}
	// 进行审核
	function approval(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		
		$row = array('useful'=>1);
		$rulesinfo = spClass('chain');
	    $data['msg'] = $rulesinfo->update($conditions, $row);
		echo ( $data['msg'] );
	}
	
		// 初始化审核
	function tjinitialize(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		$row = array('used'=>0,'reason'=>"");
		$rulesinfo = spClass('chain');
	    $data['msg'] = $rulesinfo->update($conditions, $row);
		echo ( $data['msg'] );
	}
	// 进行审核
	function tjapproval(){
		$data = $this->spArgs();
		$conditions = array('id'=>$data['id']); 
		
		$row = array('used'=>1);
		$rulesinfo = spClass('chain');
	    $data['msg'] = $rulesinfo->update($conditions, $row);
		echo ( $data['msg'] );
	}

	//进行拒绝
	function refuse(){
		$data = $this->spArgs(); 
		$conditions = array('id'=>$data['id']); 
		$row = array('useful'=>0);
		$rulesinfo = spClass('chain');
	    $data['msg'] = $rulesinfo->update($conditions, $row);
		echo ( $data['msg'] );
	}
    //不进行二级推荐
    function notop(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('istop'=>0);
        $rulesinfo = spClass('chain');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yestop(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('istop'=>1);
        $rulesinfo = spClass('chain');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //不进行二级推荐
    function nochoose(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('choose'=>0);
        $rulesinfo = spClass('chain');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
    //进行二级推荐
    function yeschoose(){
        $data = $this->spArgs();
        $conditions = array('id'=>$data['id']);
        $row = array('choose'=>1);
        $rulesinfo = spClass('chain');
        $data['msg'] = $rulesinfo->update($conditions, $row);
        echo ( $data['msg'] );
    }
}
