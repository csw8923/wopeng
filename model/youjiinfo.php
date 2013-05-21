<?php
class youjiinfo extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "youjiinfo"; // 数据表的名称
  
     // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'username' => array(  
							'notnull' => TRUE, 
							'minlength' => 1,
					),
					'title' => array(  
							'notnull' => TRUE,
					),
					'releasedate' => array( 
							'notnull' => TRUE, 
							'minlength' => 1, 
					),
					'content' => array(  
							'notnull' => TRUE,
					),
					'provinceid' => array( 
							'notnull' => TRUE, 
					),
			),
			"messages" => array( // 提示信息
					'username' => array(
							'notnull' => "发布人不能为空",
							'minlength' => "输入内容不能小于1",  
					),
					'title' => array(
							'notnull' => "标题不能为空",
					),
					'releasedate' => array(
							'notnull' => "发布时间不能为空",
							'minlength' => "输入内容不能小于1", 
					),
					'content' => array(
							'notnull' => "内容不能为空", 
					),
					'provinceid' => array(
							'notnull' => "选择分类不能为空",
					),
			)
	);
	
}
