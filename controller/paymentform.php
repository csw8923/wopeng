<?php

class paymentform extends basis
{
	function index(){
		$linkid = $this->spArgs("linkid");
		$types = $this->spArgs("types");

	    if($types == 'activity')
    	{
    		$conditions = array( 'id' => $linkid);
			$s_activity = spClass('activityinfo'); 
			$activity = $s_activity->find($conditions);
            $this->activity = $activity;
    	}
	    elseif($types == 'lines')
    	{
    		$conditions = array( 'id' => $linkid);
			$s_activity = spClass('linesinfo'); 
			$activity = $s_activity->find($conditions);
            $this->activity = $activity;
    	}

		// 列出信息内容 且 修改分类名称
	    $user = spClass('payment'); 
		if(empty($filter) && empty($classid))
		{ 
		  $conditions = array( 'linkid' => $linkid,'types' => $types);
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

		if($this->results[0][userid] != $_SESSION["quser"]['uid'] || $_SESSION["quser"]['acl'] != 'GBADMIN')
		{
           $this->error("抱歉你没有权限查看该信息！");
		}
		// 列出信息内容
		$this -> display('admin/content/payment.html');
	}

	function adddata(){

		$departuretime = strtotime($data['departuretime']);

		$data = $this->spArgs(); // $data是提交上来的数据
		$newrow = array( // PHP的数组
		'realname'=>$data['realname'],
		'largenumber'=>$data['largenumber'],
		'childnumber'=>$data['childnumber'],
	    'departuretime'=>$departuretime,
	    'instation'=>$data['instation'],
		'phone'=>$data['phone'],
		'email'=>$data['email'],
		'qq'=>$data['qq'],
	    'sum'=>$data['sum'],
		'types'=>$data['types'],
		'linkid'=>$data['linkid'],
		'userid'=>$_SESSION["quser"]['uid']
		);
		$user = spClass('payment'); 
		$newid = $user->create($newrow);
		// 进行新增操作

		echo($newid);
	}

}
