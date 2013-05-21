<?php

class modifyform extends basis
{
	function index(){
		$user = spClass('lib_user'); 

		$uid = $_SESSION["userinfo"]["uid"];
		$conditions = array( 'uid' => $uid );
		$e_user = spClass('lib_user');
        $e_result = $e_user->findAll($conditions); 
		$this->uid = $uid; 
		$this->uname = $e_result[0]['uname']; 
		$this->upass = $e_result[0]['upass'];
		$this->realname = $e_result[0]['realname'];
		$this->acl = $e_result[0]['acl']; 
		$this -> display('admin/user/modifyform.html');
	}

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$newrow = array( // PHP的数组
		'uname'=>$data['uname'],
		'upass'=>$data['upass'],
		'realname'=>$data['realname'],
		'acl'=>$data['acl'],
		);
		$user = spClass('lib_user'); 
		$user->create($newrow);  
		$this->data = $data; 
	}
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$uid = $_SESSION["userinfo"]["uid"];
		$conditions = array('uid'=>$uid);
		
		$oldupass = md5($data['oldupass']);
		$check = $data['check'];
		
		if($oldupass == $check){
			$upass = md5($data['upass']);
			$row = array('uname'=>$data['uname'],'upass'=>$upass,'realname'=>$data['realname']);
			$user = spClass('lib_user');
			$user->update($conditions, $row);
			$data['msg'] = '修改成功';
		}
		else
		{
			$data['msg'] = '原密码错误';
		}
		echo $data['msg']; // 发送到模板
	}
	
	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('uid'=>$data['uid']); 
        $user = spClass('lib_user');
        $user->delete($conditions);	
	}
}
