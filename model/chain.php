<?php
class chain extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "chain"; // 数据表的名称
  
    // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'keywords' => array(  
							'notnull' => TRUE, 
							'minlength' => 1,
					),
					// 'links' => array(  
					// 		'notnull' => TRUE,
					// 		'minlength' => 6,
					// ),
			),
			"messages" => array( // 提示信息
					'keywords' => array(  
							'notnull' => "关键词不为空", 
							'minlength' => "不少于1字符", 
					),
					// 'links' => array(   
					// 		'notnull' => "链接不为空",
					// 		'minlength' => "不少于6字符", 
					// ),
			)
	);
	
}
