<?php
class activecontentinfo extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "activecontentinfo"; // 数据表的名称
  
   // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'Applicant' => array( 
							'notnull' => TRUE,
					),
					'ApplicantDate' => array(  
							'notnull' => TRUE,
					),
					'ProjectName' => array(  
							'notnull' => TRUE,
					),
					'Construcition' => array(  
							'notnull' => TRUE, 
					),
					'BuilderName' => array(   
							'notnull' => TRUE, 
					),
					'calldata' => array(  
							'notnull' => TRUE, 
					),
					'ConstrucitionProgram' => array(  
							'notnull' => TRUE, 
					),
					'ConstrucitionNodulus' => array(  
							'notnull' => TRUE, 
					),
			),
			"messages" => array( // 提示信息
					'Applicant' => array(  
							'notnull' => "申请人不为空", 
					),
					'ApplicantDate' => array(   
							'notnull' => "申请时间不为空",
					),
					'ProjectName' => array(   
							'notnull' => "项目名称不为空",
					),
					'Construcition' => array(   
							'notnull' => "施工单位不为空",
					),
					'BuilderName' => array(   
							'notnull' => "建设单位不为空", 
					),
					'calldata' => array(   
							'notnull' => "施工船舶表不为空", 
					),
					'ConstrucitionProgram' => array(  
							'notnull' => "施工计划不为空",
					),
					'ConstrucitionNodulus' => array(   
							'notnull' => "施工小结不为空",
					),
			)
	);
}
