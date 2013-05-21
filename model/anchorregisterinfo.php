<?php
class anchorregisterinfo extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "anchorregisterinfo"; // 数据表的名称
  
   // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'VesselName' => array(  
							'notnull' => TRUE,
					),
					'ReceiptDtae' => array(  
							'notnull' => TRUE,
					),
					'ApplicationAnchorDate' => array(  
							'notnull' => TRUE,
					),
					'Phone' => array(   
							'notnull' => TRUE, 
					),
			),
			"messages" => array( // 提示信息
					'VesselName' => array(  
							'notnull' => "船名不为空", 
					),
					'ReceiptDtae' => array(   
							'notnull' => "接收日期不为空", 
					),
					'ApplicationAnchorDate' => array(   
							'notnull' => "申请锚泊时间不为空", 
					),
					'Phone' => array(  
							'notnull' => "联系电话不为空", 
					),
			)
	);
	
}
