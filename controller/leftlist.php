<?php
class leftlist extends spController
{
	// 首页
	public function index(){
		header("Content-Type:text/html;charset=utf-8");
	    $this -> display('leftlist.html');   
	}
}	