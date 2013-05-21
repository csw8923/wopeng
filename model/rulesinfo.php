<?php
class rulesinfo extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "rulesinfo"; // 数据表的名称
  
    // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'rulename' => array(  
							'notnull' => TRUE, 
							'minlength' => 6,
					),
					'rulefile' => array(  
							'notnull' => TRUE,
							'minlength' => 6,
					),
					'addtime' => array(  
							'notnull' => TRUE,
					),

			),
			"messages" => array( // 提示信息
					'rulename' => array(  
							'notnull' => "名称不为空", 
							'minlength' => "不少于6字符", 
					),
					'rulefile' => array(   
							'notnull' => "名称不为空",
							'minlength' => "不少于6字符", 
					),
					'addtime' => array(   
							'notnull' => "时间不为空", 
					),
			)
	);
	
}
