<?php
class acl extends spModel
{
  var $pk = "aclid"; // 数据表的主键
  var $table = "acl"; // 数据表的名称
  
   // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'controller' => array(  // 这里是对username的验证规则
							'notnull' => TRUE, // username不能为空
					),
			),
			"messages" => array( // 提示信息
					'controller' => array(
							'notnull' => "控制器不能为空",
					),
			)
	);
	
}
