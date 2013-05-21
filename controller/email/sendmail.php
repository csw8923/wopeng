<?php
/**
* @desc   SMTP 邮件服务器
* @param  服务器参数和邮件信息
* @author 张长伟
* @date   2010-1-10 22:15:01
* @contact QQ:462178176
*/
require_once("class.mail.php");
/**
*服务器信息
*/
$MailServer = 'smtp.126.com';      //SMTP 服务器
$MailPort   = '25';					 //SMTP服务器端口号 默认25
$MailId     = 'CSW8923@126.com';  //服务器邮箱帐号
$MailPw     = 'CSW520#Lss23';			     //服务器邮箱密码

/**
*客户端信息
*/
$Title      = '测试邮件标题';        //邮件标题
$Content    = '测试邮件内容';        //邮件内容
$email      = '624986027@QQ.com';//接收者邮箱
$smtp = new smtp($MailServer,$MailPort,true,$MailId,$MailPw);
$smtp->debug = false;
if($smtp->sendmail($email,$MailId, $Title, $Content, "HTML")){
	 echo '邮件发送成功';            //返回结果
} else {
	 echo '邮件发送失败';            //$succeed = 0;
}
?>