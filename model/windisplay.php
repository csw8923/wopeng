<?php
class windisplay extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "windisplay"; // 数据表的名称	
  
      // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'name' => array(  
							'notnull' => TRUE, 
							'minlength' => 2,
					)

			),
			"messages" => array( // 提示信息
					'name' => array(  
							'notnull' => "名称不为空", 
							'minlength' => "不少于2字符", 
					),
			)
	);

}
