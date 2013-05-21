<?php
//这里是我的一个数据库类
require("common.php");
error_reporting(E_ALL & ~E_NOTICE);
$sysfun = new sysfun('rulesinfo'); //实例化
if($_POST['compatible'] == 'on')
{
	$_POST['compatible'] = 1;
}
else
{
	$_POST['compatible'] = 0;
}

if($_POST['autocj'] == 'on')
{
	$_POST['autocj'] = 1;
}
else
{
	$_POST['autocj'] = 0;
}

if($_POST['universal'] == 'on')
{
	$_POST['universal'] = 1;
}
else
{
	$_POST['universal'] = 0;
}

if($_POST['code'] == 'on')
{
	$_POST['code'] = 1;
}
else
{
	$_POST['code'] = 0;
}
$addrow = array( 'rulename' => $_POST['rulename'],'rulefile' => $_POST['rulefile'],'compatible' => $_POST['compatible'],'geturl' => $_POST['geturl'],'getlist' => $_POST['getlist'],'getlink' => $_POST['getlink'],'autocj' => $_POST['autocj'],'gethost' => $_POST['gethost'],'gettitle' => $_POST['gettitle'],'patterns' => $_POST['patterns'],'universal' => $_POST['universal'],'getdetailurl' => $_POST['getdetailurl'],'getrange' => $_POST['getrange'],'dpatterns' => $_POST['dpatterns'],'dreplacements' => $_POST['dreplacements'],'addtime' => $_POST['addtime'], 'code' => $_POST['code']);
$find_demo = $sysfun->create($addrow);
header("Location:list.php");