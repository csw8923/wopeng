<?php
//这里是我的一个数据库类
require("common.php");
error_reporting(E_ALL & ~E_NOTICE);

$sysfun = new sysfun('rulesinfo'); //实例化
$delrow = array( 'id' => $_REQUEST["id"] );
$find_demo = $sysfun->delete($delrow);

header("Location:list.php");