<?php
session_start();
// 保存一天
$lifeTime = 24 * 3600;
setcookie(session_name(), session_id(), time() + $lifeTime, "/");
?>
<?php	
// top继承于spController，从而代替spController的作用
class basis extends spController
{
	// 构造函数，进行全局操作的位置
	function __construct(){
		// 必须加入启动父类构造函数的操作
		parent::__construct();
		$this->t_curl = $this->spArgs('c'); 
		$this->t_type = $this->spArgs('t'); 
		$this->quser = $_SESSION["quser"]['uid']; 
		//dump($_SESSION["userinfo"]['id']); // 获取全局真实姓名
		$cataObj = spClass("lib_category");
		$this->resultclass = $cataObj->getcatemenuList();	

		$conditions = array( 'uid' => $_SESSION["quser"]['uid'] );
		$userinfo = spClass('lib_user'); 
        $this->userinfo = $userinfo->find($conditions);
        //dump($this->userinfo);
        // 用户信息

        // 获取公告及邮箱参数 配置文件
		$xml = simplexml_load_file('rules/deploy.xml');
        $n = count($xml);
		$i=0;
		$infoaction=array();
		while($i<=$n-1)
		{
		  $name  = $xml-> article[$i] -> name;
		  $event  = $xml-> article[$i] -> event;
		  $infoaction[] = array('name'=>$name,'event'=>$event);
		  $i++;
	    }
	    $this->infoaction=$infoaction;
		spAccess('w','domain',(string)$infoaction[0]['event'], 9600);
		$this->domain = 'http://'.(string)$infoaction[0]['event'];
		$this->home_title = (string)$infoaction[6]['event'];
		$this->home_keywords = (string)$infoaction[7]['event'];
		$this->home_description = (string)$infoaction[8]['event'];
		spAccess('w','newsid',(int)$infoaction[1]['event'], 9600);
		spAccess('w','emailhttp',(string)$infoaction[2]['event'], 9600);
		spAccess('w','emailport',(int)$infoaction[3]['event'], 9600);
		spAccess('w','emailid',(string)$infoaction[4]['event'], 9600);
		spAccess('w','emailpassword',(string)$infoaction[5]['event'], 9600);
		
		// 友情链接表
		$conditions_links = spClass('links'); 
		$result_links = $conditions_links->findAll(); // 查找
		$this->result_links = $result_links;
        
        if(!empty($_SESSION["quser"]['uid']))
        {
		    $privateletterinfo = spClass('privateletterinfo'); 
		    $conditions = 'mark = 0 and replyid = '.$_SESSION["quser"]['uid'].'';
		    $this->privateletterinfo_msg = $privateletterinfo->findCount($conditions,'id DESC',NULL,"150");
	    }
	    //dump($privateletterinfo_msg);

	    if(!empty($_SESSION["quser"]['uid']))
        {
		    $remindinfo = spClass('remindinfo'); 
		    $conditions = 'mark = 0 and replyid = '.$_SESSION["quser"]['uid'].'';
		    $this->remindinfo_msg = $remindinfo->findCount($conditions,'id DESC',NULL,"150");
	    }
	    //dump($this->remindinfo_msg);  

        //require('include/checking.php'); // 用户身份校验

        $conditions = array( 'ispass' => 1 );
        $scenery_search = spClass('sceneryinfo');
        $this->scenery_search=$scenery_search->findAll($conditions,"rand(), clicknumber DESC","title","0,4");
        //dump($this->scenery_search);
        $debuginfo = spClass('debuginfo'); 
        $conditions = array( 'ispass' => 0 );
		$this->debuginfonum = $debuginfo->findCount($conditions); 
		//dump($debuginfonum);
	
	    $this->current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
}
