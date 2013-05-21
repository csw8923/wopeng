<?php

class aclform extends basis
{

	function index(){
		$acl = spClass('acl'); // 权限分配数据库连接
        $result = $acl->findAll($conditions); 
		$this->result = $result;
		
		// 后台编辑数据取出
		$aclid = $this->spArgs("aclid");
		$conditions = array( 'aclid' => $aclid );
		$e_acl = spClass('acl');
        $e_result = $e_acl->findAll($conditions); // 查找
		$this->aclid = $aclid; 
		$this->controller = $e_result[0]['controller']; 
		$this->action = $e_result[0]['action']; 
		$this->acl_name = $e_result[0]['acl_name']; 
		
		// 控制器数据 xml 格式
		$xml = simplexml_load_file('rules/controller.xml');
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
		
		// 动作数据 xml 格式
		$xml = simplexml_load_file('rules/action.xml');
		$n = count($xml);
		$i=0;
		$infoaction=array();
		while($i<=$n-1)
		{
		  $name  = $xml-> note[$i] -> name;
		  $event  = $xml-> note[$i] -> event;
		  $infoaction[] = array('name'=>$name,'event'=>$event);
		  $i++;
	    }
	    $this->infoaction = $infoaction; 
		
		$this -> display('admin/user/aclform.html'); // 动作发送模板
	}

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据

		$newrow = array(
		'controller'=>$data['controller'],
		'action'=>$data['action'],
		'acl_name'=>$data['acl_name']
		);
		$acl = spClass('acl'); // 权限分配数据库连接
		$json = json_encode($acl->spVerifier($newrow));
		if($json == 'false'){ $acl->create($newrow);}  // 进行新增操作
		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('aclid'=>$data['aclid']);
		$row = array('controller'=>$data['controller'],'sweepdate'=>$data['sweepdate'],'action'=>$data['action'],'acl_name'=>$data['acl_name']);
		$acl = spClass('acl');
		$json = json_encode($acl->spVerifier($row));
		if($json == 'false'){ $acl->update($conditions,$row);}  // 进行数据编辑
		echo($json);
	}
	
	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('aclid'=>$data['aclid']); // 构造条件
        $acl = spClass('acl');
        $data['msg'] = $acl->delete($conditions);	
		echo( $data['msg'] ); 
	}
}
