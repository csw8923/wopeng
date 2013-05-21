<?php
header("Content-Type:text/html;charset=utf-8");
require_once("email/class.mail.php");

class login extends spController
{
    // 登入首页
	function index(){
        $openid = $this->spArgs("openid");
        $nick = $this->spArgs("nick");
        $name = $this->spArgs("name");
		$conditions = array( 'qqlink' => $openid);
		//$conditions = "qqlink like '%".$openid."%'";
	    $rl_lib_user = spClass('lib_user'); 
	    $rl_result = $rl_lib_user->find($conditions); 
	    $_SESSION["quser"] = $rl_result;

	    if(!empty($openid)&&empty($_SESSION["quser"]))
	    {
	    	$this->jump("http://".$_SERVER['HTTP_HOST']."/index.php?c=login&a=register&type=qq&openid=".$_SESSION["openid"]."&nick=".$nick."&name=".$name."");
	    }

		$lib_user = spClass('lib_user'); 
        $result = $lib_user->findAll($conditions); 
		$this->result = $result; 
	    $this->url = $this->spArgs("url");
		if(!empty($_SESSION["quser"]["uid"]))
		{
		  $this->success("登录成功，欢迎您！", spUrl("ucenter","UserData"));
		}
		$this -> display('login.html');
	}
	
	// 生成校验码
	function _vcode() {
	$vcode = spClass('spVerifyCode');
	$vcode->display();
    }

    function register(){
		$lib_user = spClass('lib_user'); 
        $result = $lib_user->findAll($conditions); 
		$this->result = $result; 
		$id = $_SESSION["quser"]['uid'];
		$conditions = array( 'uid' => $id );
		$e_userinfo = spClass('lib_user');
        $e_result = $e_userinfo->findAll($conditions);
		$this->openid = $this->spArgs("openid");
        $this->nick = $this->spArgs("nick");
        $this->name = $this->spArgs("name");
		$this->id = $id; 
		$this->uname = $e_result[0]['uname']; 
		$this->integral = $e_result[0]["integral"];
		if($this->openid != '')
		{
		   $this->upass = mt_rand(111111111, 11111111);
        }
		$this->realname = $e_result[0]['realname'];
		$this->Phone = $e_result[0]['Phone']; 
		$this->QQ = $e_result[0]['QQ']; 
		$this->avatar = str_replace(".jpg","",$e_result[0]['avatar']);
		$this->Email = $e_result[0]['Email']; 
		$this->Address = $e_result[0]['Address']; 
	    $this->provinceid = $e_result[0]['provinceid']; 
		$this->cityid = $e_result[0]['cityid']; 
		$this->areaid = $e_result[0]['areaid']; 

		$cataObj = spClass("lib_cityinfo");
		$this->resultclass = $cataObj->getCatalogSub();

		$cataObj = spClass("lib_cityinfo");
		$this->resultsub = $cataObj->getCatalogList();
		$this -> display('user/UserRegister.html');
	}
	
	// 生成通过验证码 和 密码
	function regdata(){
	 $data = $this->spArgs(); 
	 $Email = strtolower($this->spArgs("Email"));
	 $uname = $this->spArgs("uname");
	 $upass = $this->spArgs("upass");
	 $qqlink = $this->spArgs("qqlink");
	 $vcode = spClass('spVerifyCode');

	 if($vcode->verify($this->spArgs('code')))
	{
	    $vcode = spClass('spVerifyCode');
		if(!empty($Email)) 
		{      //通过验证
			$conditions = array( 'Email' => $Email);
			$e_lib_user = spClass('lib_user'); 
			$e_result = $e_lib_user->find($conditions); 
			if($e_result['Email'] == $Email){
			   $this->error("你输入的Email已经注册过了，请换一个来注册！", spUrl("login","register"));
			}
			else
			{
			   $lib_user = spClass('lib_user'); 
	           $newrow = array( // PHP的数组
			   'upass' => $upass,
			   'uname' => $uname,
			   'Email' => $Email,
			   'qqlink' => $qqlink,
			   'avatar' => "../../tpl/images/pic14.jpg"
			   );
			   $lib_user->verifier = $lib_user->verifier_reg; // 切换验证规则
			   $json = json_encode($lib_user->spVerifier($newrow));
			   $xml = simplexml_load_file('rules/integral.xml'); // 积分配置
			   if(empty($result['Email'])){ 
				  $newrow['upass'] = md5($upass);
				  $uid = $lib_user->create($newrow); 

			  	  $json = json_encode($uid);
				  $Historyrow = array( // PHP的数组
				  'types'=>"user",
				  'title'=>$Email,
				  'linkid'=>$uid,
				  'userid'=>$uid,
				  'integral'=>(int)$xml->user,
				  'releasedate'=>time()
				  );
				  $syshistory = spClass('syshistory'); 
				  $syshistory->create($Historyrow);
				  $userhistory = spClass('userhistory'); 
				  $userhistory->create($Historyrow);

				  $user = spClass('lib_user');
				  $user_result = $user->find($conditions);
				  $conditions = array('uid'=>$_SESSION["quser"]['uid']); 
				  $row = array('integral'=>$user_result["integral"] + (int)$xml->user);
				  $user->update($conditions, $row);  // 进行新增操作

			   }  // 进行新增操作
               
              $conditions = array( 'uid' => $uid);
			  $rl_lib_user = spClass('lib_user'); 
			  $rl_result = $rl_lib_user->find($conditions); 
			  $_SESSION["quser"] = $rl_result;

	          // 获取邮箱配置信息
	          $emailhttp = spAccess('r','emailhttp');
	          $emailport = spAccess('r','emailport');
	          $emailid = spAccess('r','emailid');
	          $emailpassword = spAccess('r','emailpassword');
	          $MailServer = $emailhttp;      //SMTP 服务器
	          $MailPort   = $emailport;					 //SMTP服务器端口号 默认25
	          $MailId     = $emailid;  //服务器邮箱帐号
	          $MailPw     = $emailpassword;			     //服务器邮箱密码
	          /**
	          *客户端信息
	          */
	          $domain = spAccess('r','domain');
	          $Title      = '窝棚网欢迎你的加入';        //邮件标题
	          $Content    = '尊敬的用户欢迎您注册我们的网站';
	          $Content = $Content.'<br />尊敬的用户欢迎您注册我们的网站你的账号是'.$Email.'.<br />密码'.$upass.'为:<a href="http://'.$domain.'/login.html">登录</a>';//邮件内容
	          $email      = $Email;//接收者邮箱
	          $smtp = new smtp($MailServer,$MailPort,true,$MailId,$MailPw);
	          $smtp->debug = false;
	          if($smtp->sendmail($email,$MailId, $Title, $Content, "HTML")){
	            $this->success("恭喜你注册成功我们会发送一封邮件至你的".$Email."请尽快查收并请重新登录一次。", spUrl("ucenter","UserData"));
	          } else {
	            $this->error("抱歉发送失败，请重新再来一次！", spUrl("login","register"));
	          }	
			 }

		}
		else
		{
			$this->error("Email不能为空，请重新输入！", spUrl("login","register"));
		}
	}
	else
	{
	   $this->error("验证码错误，请重新输入！", spUrl("login","register"));
    }

		echo($msg);
	}


    // 生成通过验证码 和 密码
    function qqregdata(){
     $data = $this->spArgs(); 
     $email = strtolower($this->spArgs("email"));
     $uname = $this->spArgs("nick");
     $qqname = $this->spArgs("qqname");
     $qqlink = $this->spArgs("openid");
     if(empty($email))
     {
        $Random = mt_srand(mktime());
        $Random = mt_rand();
        $email = (int)$Random."@xx.com";
     }
     $upass = (int)$Random;
 
    $conditions = array( 'qqlink' => $qqlink);
    //$conditions = "qqlink like '%".$openid."%'";
    $rl_lib_user = spClass('lib_user'); 
    $rl_result = $rl_lib_user->find($conditions); 
    $_SESSION["quser"] = $rl_result;

    if(!empty($_SESSION["quser"]["uid"]))
    {
      $this->success("登录成功，欢迎您！", spUrl("ucenter","UserData"));
    }
    else
    {
        if(!empty($email)) 
        {      //通过验证
            $conditions = array( 'Email' => $email);
            $e_lib_user = spClass('lib_user'); 
            $e_result = $e_lib_user->find($conditions); 
            if($e_result['Email'] == $email){
               $this->error("你输入的QQ已经注册过了，请换一个来注册！", spUrl("login","register"));
            }
            else
            {
               $lib_user = spClass('lib_user'); 
               $newrow = array( // PHP的数组
               'upass' => $upass,
               'uname' => $uname,
               'Email' => $email,
               'qqlink' => $qqlink,
               'avatar' => "../../tpl/images/pic14.jpg"
               );
               $lib_user->verifier = $lib_user->verifier_reg; // 切换验证规则
               $json = json_encode($lib_user->spVerifier($newrow));
               $xml = simplexml_load_file('rules/integral.xml'); // 积分配置
               if(empty($result['Email'])){ 
                  $newrow['upass'] = md5($upass);
                  $uid = $lib_user->create($newrow); 

                  $json = json_encode($uid);
                  $Historyrow = array( // PHP的数组
                  'types'=>"user",
                  'title'=>$email,
                  'linkid'=>$uid,
                  'userid'=>$uid,
                  'integral'=>(int)$xml->user,
                  'releasedate'=>time()
                  );
                  $syshistory = spClass('syshistory'); 
                  $syshistory->create($Historyrow);
                  $userhistory = spClass('userhistory'); 
                  $userhistory->create($Historyrow);

                  $user = spClass('lib_user');
                  $user_result = $user->find($conditions);
                  $conditions = array('uid'=>$_SESSION["quser"]['uid']); 
                  $row = array('integral'=>$user_result["integral"] + (int)$xml->user);
                  $user->update($conditions, $row);  // 进行新增操作

               }  // 进行新增操作
               
              $conditions = array( 'uid' => $uid);
              $rl_lib_user = spClass('lib_user'); 
              $rl_result = $rl_lib_user->find($conditions); 
              $_SESSION["quser"] = $rl_result;
                $this->success("恭喜你注册成功。", spUrl("ucenter","UserData"));
             }

        }
        else
        {
            $this->error("Email不能为空，请重新输入！", spUrl("login","register"));
        }
      }
    }

    // QQ链接
	function qqlink(){
		$openid = $this->spArgs("openid");
		$uid = $this->spArgs("uid");
		$conditions = array( 'uid' => $uid);
		$row = array('qqlink'=>$openid);
		$user = spClass('lib_user');
		$msg = $user->update($conditions, $row);  // 进行新增操作
		if($msg == 1)
		{
		    $this->success("恭喜你QQ关联成功", spUrl("ucenter","Index"));
	    }
	    else
	    { 
	    	$this->success("对不起QQ关联失败！请联系管理员", spUrl("ucenter","Index"));
	    }
	}
	// 生成通过验证码 和 密码
	function checkdata(){
		$data = $this->spArgs(); 
		$vcode = spClass('spVerifyCode');
		if($vcode->verify($this->spArgs('code'))) {
			//通过验证
			$conditions = array( 'Email' => strtolower($data['Email']));
			$e_userinfo = spClass('lib_user'); 
			$e_result = $e_userinfo->findAll($conditions); 
			$Password = md5($data['upass']);
			
			if($e_result[0]['upass'] == $Password)
			{
			  $_SESSION["quser"] = $e_result[0]; // 成功了才记录用户信息
			  if($data['url'] == null)
			  {
				   $this->success("登录成功，欢迎您！", spUrl("ucenter","Index"));
			  }
			  else
			  {
			  	   $this->success("登录成功，欢迎您！正在引导你刚才打开页面...",$data['url']);
			  }
			}
			else
			{
			  $this->error("用户名/密码错误，请重新输入！", spUrl("login","index"));
			}
		}else{
			$this->error("验证码错误，请重新输入！", spUrl("login","index"));
		}
	}
	
	// 找回密码
	function forgotpass(){
		$this -> display('forgotpass.html');
	}
	
	// 找回密码处理
	function checkpass(){
		$data = $this->spArgs(); 
		$vcode = spClass('spVerifyCode');
		if($vcode->verify($this->spArgs('code'))) {
			   //通过验证
				$Email = $this->spArgs("Email");
				$conditions = array( 'Email' => $Email);
				$e_lib_user = spClass('lib_user'); 
				$e_result = $e_lib_user->find($conditions); 
				if($e_result['Email'] != $Email){
					$this->error("输入Email不对，请重新输入！", spUrl("login","forgotpass"));
				}
				else
				{
					$Random = mt_srand(mktime());
					$Random = mt_rand();
					$pass_conditions = array('Email' => $Email); 
		            $row = array('upass' => md5((int)$Random));
					$lib_user = spClass('lib_user');
					$lib_user->update($pass_conditions,$row);
				    /**
					*服务器信息
					*/
					
					// 获取邮箱配置信息
					$emailhttp = spAccess('r','emailhttp');
					$emailport = spAccess('r','emailport');
					$emailid = spAccess('r','emailid');
					$emailpassword = spAccess('r','emailpassword');
					$MailServer = $emailhttp;      //SMTP 服务器
					$MailPort   = $emailport;					 //SMTP服务器端口号 默认25
					$MailId     = $emailid;  //服务器邮箱帐号
					$MailPw     = $emailpassword;			     //服务器邮箱密码
					/**
					*客户端信息
					*/
					$Title      = '窝棚网密码重置';        //邮件标题
					$Content    = '尊敬的用户我们已经将你的密码重置为：'.$Random."<br />请你尽快使用以上密码登入网站并修改新密码.<br />登入地址为:http://".$domain."/login.html";        //邮件内容
					$email      = $Email;//接收者邮箱
					$smtp = new smtp($MailServer,$MailPort,true,$MailId,$MailPw);
					$smtp->debug = false;
					if($smtp->sendmail($email,$MailId, $Title, $Content, "HTML")){
						 $this->success("恭喜你确认邮件已经发送至".$Email."请尽快查收！", spUrl("login","index"));
					} else {
						 $this->error("抱歉发送失败，请重新再来一次！", spUrl("login","forgotpass"));
					}	
				  }
		         // 执行密码找回
		}else{
			$this->error("验证码错误，请重新输入！", spUrl("login","forgotpass"));
		}
	}

}
