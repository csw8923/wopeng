<?php
class manage extends basis
{
	// 首页
	public function index(){
		header("Content-Type:text/html;charset=utf-8");
		//检测后台浏览器版本
		if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 7.0"))
	    $this->success("我们检测到你使用IE7,请使用IE8以上浏览器！", spUrl("manage","login"));
		else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0"))
		$this->success("我们检测到你使用IE6,请使用IE8以上浏览器！", spUrl("manage","login"));

		$s_scenery = spClass('sceneryinfo'); 
		$s_lines = spClass('linesinfo'); 
		$s_activity = spClass('activityinfo'); 
		$s_listbd = spClass('listbdinfo'); 
		$s_youji = spClass('youjiinfo'); 
		$s_comment = spClass('commentinfo');
		$s_picture = spClass('pictureinfo');
		$s_visits = spClass('visitsinfo');
		$s_answers = spClass('answersinfo');
		$user = spClass('lib_user'); 
        $conditions = array( 'acl' => 'GBADMIN' );
		$result_statistical = $user->findAll($conditions,'integral DESC');

		$this->timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
        $this->timebefore7 = strtotime(date('Y-m-d 00:00:00',strtotime("-7 day")));
        $this->timebefore3 = strtotime(date('Y-m-d 00:00:00',strtotime("-3 day")));
		$this->timebefore = strtotime(date('Y-m-d 00:00:00',strtotime("-1 day")));
		$this->timecurrent = strtotime(date('Y-m-d 00:00:00'));
		$this->timeafter = strtotime(date("Y-m-d 0:0:0",strtotime("+1 day")));
		
		$n = 0;
		foreach ($result_statistical as $value) {
	       if(!empty($value['uid'])){

			    $conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_scenery->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_scenery->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_scenery->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_scenery->findCount($conditions_30day);
				$result_statistical[$n]['scenery'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);
                // 景点数据统计
				$conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_lines->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_lines->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_lines->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_lines->findCount($conditions_30day);
				$result_statistical[$n]['lines'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);

			    // 线路数据统计
			    $conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_activity->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_activity->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_activity->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_activity->findCount($conditions_30day);
				$result_statistical[$n]['activity'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);

			    // 活动数据统计

			    $conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_listbd->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_listbd->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_listbd->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_listbd->findCount($conditions_30day);
				$result_statistical[$n]['listbd'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);

			    // 榜单数据统计

			    $conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_youji->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_youji->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_youji->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_youji->findCount($conditions_30day);
				$result_statistical[$n]['youji'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);

			    // 游记数据统计

			    $conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_comment->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_comment->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_comment->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_comment->findCount($conditions_30day);
				$result_statistical[$n]['comment'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);

			    // 点评数据统计

			    $conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_picture->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_picture->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_picture->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_picture->findCount($conditions_30day);
				$result_statistical[$n]['picture'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);

			    // 图片数据统计

			    $conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_visits->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_visits->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_visits->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_visits->findCount($conditions_30day);
				$result_statistical[$n]['visits'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);

			    // 自由行数据统计

			    $conditions_today = "releasedate >=". $this->timecurrent ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_today = $s_answers->findCount($conditions_today);
				$conditions_3day = "releasedate >=". $this->timebefore3 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_3day = $s_answers->findCount($conditions_3day);
				$conditions_7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_7day = $s_answers->findCount($conditions_7day);
				$conditions_30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND userid = ".$value['uid'];
				$this->nums_30day = $s_answers->findCount($conditions_30day);
				$result_statistical[$n]['answers'] = array("scenerytoday" => $this->nums_today,"scenery3" => $this->nums_3day,"scenery7" => $this->nums_7day,"scenery30" => $this->nums_30day,);

			    // 论坛数据统计

	       }
	       $n++;

		}

		//dump($result_statistical);
		$this->result_statistical = $result_statistical; 

		$this -> display('admin/index.html');
	}
	
	// 退出登录
	public function logout(){
		// 这里是PHP.net关于删除SESSION的方法
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {setcookie(session_name(), '', time()-42000, '/');}
		spClass('spAcl')->set("");
		session_destroy();
		// 跳转回首页
		$this->success("已退出，返回首页！", spUrl("admin","index"));
	}
	
	function _vcode() {
	    $vcode = spClass('spVerifyCode');
		$vcode->display();
	}
	
	// 显示用户登录框以及验证用户登录情况
	public function login(){	
	
		$vcode = spClass('spVerifyCode');
			//通过验证
			$userObj = spClass("lib_user"); // 实例化lib_user类
			if( $Email = $this->spArgs("Email") ){ // 已经提交，这里开始进行登录验证
				//$upass = $this->spArgs("upass");
				// 使用spVerifier进行第一次检查
				$upass = md5($this->spArgs("upass"));
				$rows = array('Email' => $Email, 'upass' => $upass);
				$userObj->verifier = $userObj->verifier_login; // 切换校验规则
				$results = $userObj->spVerifier($rows);

				 if($vcode->verify($this->spArgs('code'))) {
					// 密码验证
					if( false == $results ){ // 当spVerifier返回false的时候，则是表示已经通过验证，数据是合格的
					
						// 使用lib_user类中我们新建的userlogin方法来验证用户名和密码
						if( false == $userObj->userlogin($Email, $upass) ){
							// 登录失败，提示后跳转回登录页面
							$this->error("用户名/密码错误，请重新输入！", spUrl("admin","index"));
							
						}else{
							// 成功登录，跳转。这里要进行判断一下：
							// 如果用户角色是GBADMIN（管理员）则跳转到admin/index的管理中心
							// 如果用户角色是GBUSER（普通会员）则跳转回首页
							$useracl = spClass("spAcl")->get(); // 通过acl的get可以获取到当前用户的角色标识
							if( "GBADMIN" == $useracl ){
								$this->success("登录成功，欢迎您，尊敬的管理员！", spUrl("manage","index"));
							}else{
								$this->success("登录成功，欢迎您，尊敬的会员！", spUrl("manage","index"));
							}
						}
					}else{
						// $results不是false，所以没有通过验证，错误信息是$results
						// dump($results);
						foreach($results as $item){ // 开始循环错误信息的规则，这里只有用户名
							// 每一个规则，都有可能返回多个错误信息，所以这里我们也循环$item来获取多个信息
							foreach($item as $msg){ 
								// 虽然我们使用了循环，但是这里我们只需要第一条出错信息就行。
								// 所以取到了第一条错误信息的时候，我们使用$this->error来提示并跳转
								$this->error($msg,spUrl("admin","index"));
							}
						}
					}
					// 密码验证
				 }
				 else
				 {
					//没有通过验证
					$this->error("验证码输入错误，请重新输入！", spUrl("admin	","index"));
				 }	
				
			}
			// 这里是还没有填入用户名，所以将自动显示main_login.html的登录表单		
	}
	
}	