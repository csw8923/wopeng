<?php
//这里是我的一个数据库类
require("common.php");
error_reporting(E_ALL & ~E_NOTICE);
$sysfun = new sysfun('rulesinfo'); //实例化
$conditions = array( 'id' => $_POST['id'] );
$updatarow = array( 'rulename' => $_POST['rulename'],'rulefile' => $_POST['rulefile'],'compatible' => $_POST['compatible'],'geturl' => $_POST['geturl'],'getlist' => $_POST['getlist'],'getlink' => $_POST['getlink'],'autocj' => $_POST['autocj'],'gethost' => $_POST['gethost'],'gettitle' => $_POST['gettitle'],'patterns' => $_POST['patterns'],'universal' => $_POST['universal'],'getdetailurl' => $_POST['getdetailurl'],'getrange' => $_POST['getrange'],'dpatterns' => $_POST['dpatterns'],'dreplacements' => $_POST['dreplacements'],'addtime' => $_POST['addtime'], 'code' => $_POST['code']);
$find_demo = $sysfun->update($conditions,$updatarow);
header("Location:index.php");