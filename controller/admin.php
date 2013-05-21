<?php
class admin extends basis
{
	// 首页
	public function index(){
		
	   $useracl = spClass("spAcl")->get(); // 通过acl的get可以获取到当前用户的角色标识
		if( "GBADMIN" == $useracl ){
			$this->success("登录成功，欢迎您，管理员！", spUrl("manage","index"));
		}elseif("GBUSER" == $useracl ){
			$this->success("登录成功，欢迎您，尊敬的会员！", spUrl("manage","index"));}
							
		$this -> display('admin/login.html');
	}
}	