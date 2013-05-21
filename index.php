<?php
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",dirname(__FILE__).'/SpeedPHP');
$spConfig = array(
   //'mode' => 'release', // 部署模式
   "db" => array(
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'xylei85cs',
		'database' => 'xmqnly',
	),
  'dispatcher_error' => "import(APP_PATH.'/tpl/404.php');exit();",
	'view' => array(
		'enabled' => TRUE,
		'config' =>array(
			'template_dir' => APP_PATH.'/tpl',
			'compile_dir' => APP_PATH.'/tmp',
			'cache_dir' => APP_PATH.'/tmp',
			'left_delimiter' => '<{',
			'right_delimiter' => '}>',
		),
		'auto_display' => TRUE, // 使用自动输出模板功能
		'auto_display_sep' => "_", // 自动输出模板的拼装模式，/为按目录方式拼装，_为按下划线方式，这里用下划线
	    'auto_display_suffix' => '.html' // 自动输出模板的后缀名
	),
	'launch' => array( 
		 'router_prefilter' => array( 
			array('spAcl','mincheck'), // 开启有限的权限控制
		    //array('spAcl','maxcheck'), // 开启强制的权限控制
			array('spUrlRewrite', 'setReWrite')  // 对路由进行挂靠，处理转向地址
		 ),
		 'function_url' => array(
			array("spUrlRewrite", "getReWrite"),  // 对spUrl进行挂靠，让spUrl可以进行Url_ReWrite地址的生成
		  ),
	 ),
	 'ext' => array( // 扩展设置
	 	'spAcl' => array( // acl扩展设置 
	 		// 在acl中，设置无权限执行将lib_user类的acljump函数
	 		'prompt' => array("lib_user", "acljump"),
	 	), 
	// 以下是Url_ReWrite的设置
	'spUrlRewrite' => array(
		'hide_default' => false, // 隐藏默认的main/index名称，但这前提是需要隐藏的默认动作是无GET参数的
		'args_path_info' => false, // 地址参数是否使用path_info的方式，默认否
		'suffix' => '.html', // 生成地址的结尾符
		'sep' => '/',
		'map' => array(  // 网址映射
		   'article' => 'main@article',
		   'category' => 'main@category',
		   'scenery' => 'sceneryinfoform@show',
		   'lines' => 'linesinfoform@show',
		   'activity' => 'activityinfoform@show',
		   'bdlist' => 'listbdinfoform@show',
		   'lblines' => 'linesinfoform@show',
		   'city' => 'city@index',
		   'xm' => 'city@xm',
		   'youji' => 'youjiinfoform@detail',
		   'ask' => 'answersinfoform@detail',
		   'locals' => 'localsinfoform@detail',
		   'visits' => 'visitsinfoform@detail',
		   'comment' => 'commentinfoform@detail',
		   'pic' => 'albuminfoform@detailedimage',
		   'reply' => 'replyinfoform@detail',
		   'album' => 'albuminfoform@detail',
		   'photo' => 'albuminfoform@photo',
		   'cover' => 'albuminfoform@cover',
		   'photoalbum' => 'albuminfoform@photoalbum',
		   'coveralbum' => 'albuminfoform@coveralbum',
		   'search' => 'main@search',
		   '@' => 'main@no'   
		),
		'args' => array(
		   'article' => array('sid'),
		   'category' => array('another'),
		   'scenery' => array('id'),
		   'lines' => array('city','id'),
		   'activity' => array('city','id'),
		   'bdlist' => array('city','id'),
		   'lblines' => array('id'),
		   'xm' => array('tag','id'),
		   'city' => array('type','id'),
		   'youji' => array('info','id'),
		   'ask' => array('info','id'),
		   'locals' => array('info','id'),
		   'visits' => array('info','id'),
		   'comment' => array('info','id'),
		   'pic' => array('id'),
		   'reply' => array('info','id'),
		   'album' => array('info','id'),
		   'photo' => array('id'),
		   'cover' => array('id'),
		   'photoalbum' => array('info','id'),
		   'coveralbum' => array('info','id'),
		   'search' => array('keyword'),
	    ),
		 ),
	 )
);
require(SP_PATH."/SpeedPHP.php");
// 这里是入口文件全局位置
import(APP_PATH.'/controller/basis.php'); // 需要先载入top控制器父类
spRun();