<?php
class lib_user extends spModel
{
	var $pk = "uid"; // 每个留言唯一的标志，可以称为主键
	var $table = "user"; // 数据表的名称
	
	var $verifier_login = array( // 验证登录信息，由于密码是加密的输入框生成的，所以不需要进行"格式验证"
		"rules" => array( // 规则
			'Email' => array(  // 这里是对uname的验证规则
				'notnull' => TRUE, // uname不能为空
				'minlength' => 1,  // uname长度不能小于1
				'maxlength' => 50  // uname长度不能大于12
			),
		),
		"messages" => array( // 提示信息
			'Email' => array(
				'notnull' => "邮箱地址不能为空",
				'minlength' => "邮箱地址不能少于1个字符",
				'maxlength' => "邮箱地址不能大于50个字符"
			),
		)
	);
	
	var $verifier_modify = array( // 验证登录信息，由于密码是加密的输入框生成的，所以不需要进行“格式验证”
		"rules" => array( // 规则
			'Email' => array(  // 这里是对uname的验证规则
				'notnull' => TRUE, // uname不能为空
				'minlength' => 1,  // uname长度不能小于1
				'maxlength' => 50  // uname长度不能大于12
			),
			'uname' => array(  // 这里是对uname的验证规则
				'notnull' => TRUE, // uname不能为空
				'minlength' => 1,  // uname长度不能小于1
				'maxlength' => 12  // uname长度不能大于12
			),
			'upass' => array(  // 这里是对uname的验证规则
				'notnull' => TRUE, // uname不能为空
				'minlength' => 1,  // uname长度不能小于1
				'maxlength' => 12  // uname长度不能大于12
			),
		),
		"messages" => array( // 提示信息
			'Email' => array(
				'notnull' => "Email不能为空",
				'minlength' => "Email不能少于1个字符",
				'maxlength' => "Email不能大于20个字符"
			),
			'uname' => array(  // 这里是对uname的验证规则
				'notnull' => "真实姓名不能为空",
				'minlength' => "真实姓名不能少于1个字符",
				'maxlength' => "真实姓名不能大于12个字符"
			),
			'upass' => array(  // 这里是对uname的验证规则
				'notnull' => "密码不能为空",
				'minlength' => "真实姓名不能少于1个字符",
				'maxlength' => "真实姓名不能大于12个字符"
			),
		)
	);
	
	//		 前台用户注册校验规则
	var $verifier_reg = array(
			"rules" => array( // 规则
					'uname' => array(   
							'notnull' => TRUE,
							'minlength' => 1, 
					),
					'upass' => array(  // 这里是对uname的验证规则
						'notnull' => TRUE, // uname不能为空
						'minlength' => 1,  // uname长度不能小于1
						'maxlength' => 12  // uname长度不能大于12
					), 
					'Email' => array(  
							'notnull' => TRUE,
							'email' => TRUE,  
							'minlength' => 1, 
					),
			),
			"messages" => array( // 提示信息
					'uname' => array(  
							'notnull' => "密码不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
					'upass' => array(  // 这里是对uname的验证规则
						'notnull' => "密码不能为空",
						'minlength' => "真实姓名不能少于1个字符",
						'maxlength' => "真实姓名不能大于12个字符"
					),
					'Email' => array(   
							'notnull' => "邮箱不能为空", 
							'email' => "邮箱不符合规则",   
							'minlength' => "字符不能少于1个字符", 
					),	
			)
	);
	
   // 后台普通用户带密码和资料修改
	var $verifier_ucmodify = array(
			"rules" => array( // 规则
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
			),
			"messages" => array( // 提示信息
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
			)
	);

  // 后台普通用户资料修改 规则
	var $verifier_nopass = array(
			"rules" => array( // 规则
					'uname' => array(  
							'notnull' => TRUE, 
							'minlength' => 1, 
					), 	
			),
			"messages" => array( // 提示信息
					'uname' => array(  
							'notnull' => "账号不能为空", 
							'minlength' => "字符不能少于1个字符", 
					),
			)
	);


	/**
	 * 这里我们建立一个成员函数来进行用户登录验证
	 *
	 * @param uname    用户名
	 * @param upass    密码，请注意，本例中使用了加密输入框，所以这里的$upass是经过MD5加密的字符串。
	 */
	public function userlogin($Email, $upass){ 
		$conditions = array(
			'Email' => $Email,
			'upass' => $upass, // 请注意，本例中使用了加密输入框，所以这里的$upass是经过MD5加密的字符串。
		);
		// dump($conditions);
		// 检查用户名/密码，由于$conditions是数组，所以SP会自动过滤SQL攻击字符以保证数据库安全。
		if( $result = $this->find($conditions) ){ 
			// 成功通过验证，下面开始对用户的权限进行会话设置，最后返回用户ID
			// 用户的角色存储在用户表的acl字段中
			spClass('spAcl')->set($result['acl']); // 通过spAcl类的set方法，将当前会话角色设置成该用户的角色
			$_SESSION["userinfo"] = $result; // 在SESSION中记录当前用户的信息
			return true;
		}else{
			// 找不到匹配记录，用户名或密码错误，返回false
			return false;
		}
	}
	/**
	 * 无权限提示及跳转
	 */
	public function acljump(){ 
		// 这里直接“借用”了spController.php的代码来进行无权限提示
		$url = spUrl("manage","login");
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"您没有权限进行此操作，请登录！\");history.go(-1);}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}
}