<?php

class usersform extends basis
{
	function index(){
		$user = spClass('lib_user'); 
        $result = $user->findAll($conditions);
		$this->result = $result; 
	
		$uid = $this->spArgs("uid");
		$conditions = array( 'uid' => $uid );
		$e_user = spClass('lib_user'); 
        $e_result = $e_user->findAll($conditions); 
		$this->uid = $uid; 
		$this->uname = $e_result[0]['uname'];
		$this->realname = $e_result[0]['realname'];
		$this->Phone = $e_result[0]['Phone'];
		$this->QQ = $e_result[0]['QQ']; 
		$this->Email = $e_result[0]['Email']; 
		$this->Address = $e_result[0]['Address']; 
		$this->acl = $e_result[0]['acl'];

		$this -> display('admin/user/usersform.html');
	}

	function adddata(){
		$data = $this->spArgs(); 
		$upass = md5($data['upass']);
		$newrow = array( // PHP的数组
		'uname'=>$data['uname'],
		'upass'=>$upass,
		'realname'=>$data['realname'],
		'Phone'=>$data['Phone'],
		'QQ'=>$data['QQ'],
		'Email'=>$data['Email'],
        'Address'=>$data['Address'],
		'acl'=>$data['acl']
		);

		$user = spClass('lib_user');
		$user->verifier = $user->verifier_modify; // 切换验证规则
		$json = json_encode($user->spVerifier($newrow));
		if($json == 'false'){ $user->create($newrow);}  
		echo($json);
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('uid'=>$data['uid']); 
		$Password = md5($data['Password']);
		if(!empty($data['Password'])){
			$row = array('uname'=>$data['uname'],'Password'=>$upass,'acl'=>$data['acl'],'realname'=>$data['realname'],'Phone'=>$data['Phone'],'QQ'=>$data['QQ'],'Email'=>$data['Email'],'Address'=>$data['Address']);
			$user = spClass('lib_user');
			$user->verifier = $user->verifier_modify; // 切换验证规则
			$json = json_encode($user->spVerifier($row));
			if($json == 'false'){$user->update($conditions, $row);}  // 进行新增操作
			echo($json);
		}
		else
		{
			$row = array('uname'=>$data['uname'],'acl'=>$data['acl'],'realname'=>$data['realname'],'Phone'=>$data['Phone'],'QQ'=>$data['QQ'],'Email'=>$data['Email'],'Address'=>$data['Address']);
			$user = spClass('lib_user');
			$user->verifier = $user->verifier_nopass; // 切换验证规则
			$json = json_encode($user->spVerifier($row));
			if($json == 'false'){$user->update($conditions, $row);}  // 进行新增操作
			echo($json);
		}
	}
	
	function updata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('uid'=>$data['uid']); 
		$row = array('realname'=>$data['realname'],'Phone'=>$data['Phone']);
		$user = spClass('lib_user');
		$json = $user->update($conditions, $row); // 进行新增操作
		echo($json);
	}

	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('uid'=>$data['uid']); // 构造条件
        $user = spClass('lib_user');
        $data['msg']  = $user->delete($conditions);	
		echo ($data['msg']);
	}
}
