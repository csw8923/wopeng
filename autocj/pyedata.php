<?php
//这里是我的一个数据库类
require("common.php");
error_reporting(E_ALL & ~E_NOTICE);
$sysfun = new sysfun('rulesinfo'); //实例化
$data = $_POST;
$conditions = array('id'=>$data['id']);
$row = array('geturl'=>$data['geturl'],'getlist'=>$data['getlist'],'getlink'=>$data['getlink'],'gethost'=>$data['gethost'],'getdetailurl'=>$data['getdetailurl'],'article'=>$data['article'],'ispage'=>$data['ispage'],'articlepageurl'=>$data['articlepageurl'],'getrange'=>$data['getrange'],'articlepage'=>$data['articlepage'],'dpatterns'=>$data['dpatterns'],'dreplacements'=>$data['dreplacements'],'code'=>$data['code']);
$data['msg'] = $sysfun->update($conditions, $row);
$data['msg'] = 1;
echo ( $data['msg'] );