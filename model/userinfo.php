<?php
class userinfo extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "userinfo"; // 数据表的名称
  	
	// 前台用户注册校验规则
	var $verifier_reg = array(
			"rules" => array( // 规则
					'Account' => array(  
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'already' => array( 
							'notnull' => false, 
					),
					'Password' => array(   
							'notnull' => TRUE,
							'minlength' => 1, 
					),
                    'checkPass' => array( 
					        'notnull' => TRUE, 
                            'equalto' => 'Password',
                    ),  
					'realname' => array(  
							'notnull' => TRUE,
					),
					'Phone' => array(   
							'notnull' => TRUE,
							'minlength' => 1, 
					),
					'QQ' => array(   
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'Email' => array(  
							'notnull' => TRUE,
							'email' => TRUE,  
							'minlength' => 1, 
					),
					'Address' => array(  
							'notnull' => TRUE,
							'minlength' => 1, 
					),	
			),
			"messages" => array( // 提示信息
					'Account' => array(  
							'notnull' => "账号不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
					'already' => array(  
							'notnull' => "这个账号被注册过了,或者你已经用真实名称注册了", 
					),
					'Password' => array(  
							'notnull' => "密码不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
                    'checkPass' => array(  // 这里是对第二次输入的密码的验证规则
					        'notnull' => "第二次密码校验不能为空",
                            'equalto' => "第二次输入密码有错误",
                    ),  
					'realname' => array(   
							'notnull' => "真实名称不能为空", 
					),
					'Phone' => array(   
							'notnull' => "电话号码不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
					'QQ' => array(  
							'notnull' => "QQ不能为空",
							'minlength' => "字符不能少于1个字符",  
					),
					'Email' => array(   
							'notnull' => "邮箱不能为空", 
							'email' => "邮箱不符合规则",   
							'minlength' => "字符不能少于1个字符", 
					),
					'Address' => array(   
							'notnull' => "地址不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),	
			)
	);
	
   // 后台普通用户带密码和资料修改
	var $verifier_modify = array(
			"rules" => array( // 规则
					'Account' => array(  
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'Password' => array(  
							'notnull' => TRUE,
							'minlength' => 1,
					),
                    'checkPass' => array(  // 这里是对第二次输入的密码的验证规则
					        'notnull' => TRUE, 
                            'minlength' => 1, 
                    ),  
					'OldPass' => array(  // 这里是对第二次输入的密码的验证规则
					        'notnull' => TRUE, 
                            'equalto' => 'checkPass', // 要等于'password'，也就是要与上面的密码相等
                    ),  
					'realname' => array(  
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'Phone' => array(   
							'notnull' => TRUE, 
							'minlength' => 1,  
					),
					'QQ' => array(   
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'Email' => array(   
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'Address' => array(   
							'notnull' => TRUE, 
							'minlength' => 1, 
					),	
			),
			"messages" => array( // 提示信息
					'Account' => array(  
							'notnull' => "账号不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
					'Password' => array(  
							'notnull' => "密码不能为空", 
							'minlength' => "字符不能少于1个字符",  
					),
                    'checkPass' => array(  // 这里是对第二次输入的密码的验证规则
					        'notnull' => "校验密码不能为空", 
                            'minlength' => "字符不能少于1个字符",  
                    ),  
					'OldPass' => array(  // 这里是对第二次输入的密码的验证规则
					        'notnull' => "数据库查询失败", 
                            'equalto' => '你没有通过原密码验证', // 要等于'password'，也就是要与上面的密码相等
                    ),   
					'realname' => array(   
							'notnull' => "真实名称不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
					'Phone' => array(  
							'notnull' => "电话号码不能为空", 
							'minlength' => "字符不能少于1个字符",  
					),
					'QQ' => array(   
							'notnull' => "QQ不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
					'Email' => array(   
							'notnull' => "邮箱不能为空",
							'minlength' => "字符不能少于1个字符", 
					),
					'Address' => array(   
							'notnull' => "地址不能为空",
							'minlength' => "字符不能少于1个字符", 
					),	
			)
	);

  // 后台普通用户资料修改 规则
	var $verifier_nopass = array(
			"rules" => array( // 规则
					'Account' => array(  
							'notnull' => TRUE, 
							'minlength' => 1, 
					), 
					'realname' => array( 
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'Phone' => array(   
							'notnull' => TRUE, 
							'minlength' => 1,  
					),
					'QQ' => array(   
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'Email' => array(  
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'Address' => array(  
							'notnull' => TRUE, 
							'minlength' => 1,  
					),	
			),
			"messages" => array( // 提示信息
					'Account' => array(  
							'notnull' => "账号不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
					'realname' => array(   
							'notnull' => "真实名称不能为空", 
							'minlength' => "字符不能少于1个字符",  
					),
					'Phone' => array(   
							'notnull' => "电话号码不能为空",
							'minlength' => "字符不能少于1个字符", 
					),
					'QQ' => array(   
							'notnull' => "QQ不能为空",
							'minlength' => "字符不能少于1个字符", 
					),
					'Email' => array(  
							'notnull' => "邮箱不能为空", 
							'minlength' => "字符不能少于1个字符",
					),
					'Address' => array(   
							'notnull' => "地址不能为空",
							'minlength' => "字符不能少于1个字符", 
					),	
			)
	);
	


		
}
