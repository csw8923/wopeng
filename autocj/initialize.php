<?php
require("common.php");
error_reporting(E_ALL & ~E_NOTICE);
$sysfun = new sysfun('rulesinfo'); //实例化
$conditions = array( 'id' => $_POST['id'] );
$updatarow = array('approval'=>0);
$find_demo = $sysfun->update($conditions,$updatarow);
if ($find_demo != false) 
{
  echo '1';
}
else
{
  echo '0';
}
