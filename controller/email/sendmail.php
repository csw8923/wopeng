<?php
/**
* @desc   SMTP �ʼ�������
* @param  �������������ʼ���Ϣ
* @author �ų�ΰ
* @date   2010-1-10 22:15:01
* @contact QQ:462178176
*/
require_once("class.mail.php");
/**
*��������Ϣ
*/
$MailServer = 'smtp.126.com';      //SMTP ������
$MailPort   = '25';					 //SMTP�������˿ں� Ĭ��25
$MailId     = 'CSW8923@126.com';  //�����������ʺ�
$MailPw     = 'CSW520#Lss23';			     //��������������

/**
*�ͻ�����Ϣ
*/
$Title      = '�����ʼ�����';        //�ʼ�����
$Content    = '�����ʼ�����';        //�ʼ�����
$email      = '624986027@QQ.com';//����������
$smtp = new smtp($MailServer,$MailPort,true,$MailId,$MailPw);
$smtp->debug = false;
if($smtp->sendmail($email,$MailId, $Title, $Content, "HTML")){
	 echo '�ʼ����ͳɹ�';            //���ؽ��
} else {
	 echo '�ʼ�����ʧ��';            //$succeed = 0;
}
?>