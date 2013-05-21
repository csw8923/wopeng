<?php
/*
** 程序版本:和其数据备份系统v1.1
** 调试环境:php+mysql
** 制作日期:2009-07-15
** 程序设计:℡嗄沬°| (网址:http://www.he-qi.com  邮箱:ye3312#163.com  QQ:280708784)
** 如您使用本程序，请保留以上信息。
*/
error_reporting(E_ERROR | E_PARSE);
define('D_P',__FILE__ ? getdirname(__FILE__).'/' : './');
define('R_P',D_P."include/");
include D_P."/inc.config.php";

$admin_file = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
 
require_once(R_P."/admin.php");
require_once(R_P."/bakup.php");
 
function SafeFunc(){
	//Safe The Admin
}
function getdirname($path){
	if(strpos($path,'\\')!==false){
		return substr($path,0,strrpos($path,'\\'));
	}elseif(strpos($path,'/')!==false){
		return substr($path,0,strrpos($path,'/'));
	}else{
		return '/';
	}
}
?>