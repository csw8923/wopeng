<?php 
/*
** 程序版本:和其数据备份系统v1.1
** 调试环境:php+mysql
** 制作日期:2009-07-15
** 程序设计:℡嗄沬°| (网址:http://www.he-qi.com  邮箱:ye3312#163.com  QQ:280708784)
** 如您使用本程序，请保留以上信息。
*/
$dbhostname = 'localhost';		//数据库主机
$dbusername = 'root';			//数据库用户
$dbpassword = 'xylei85cs';			//数据库密码
$dbdataname = 'home';		//数据库名称

$dbconntype = 0;				//连接方式，1为持续连接,0为一般链接(虚拟主机用户推荐)

$app_name="heqi_";

$dbtablepre = "vn_";

date_default_timezone_set("PRC");
$charset='utf8';
?>