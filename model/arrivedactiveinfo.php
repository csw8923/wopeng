<?php
class arrivedactiveinfo extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "arrivedactiveinfo"; // 数据表的名称
  
     // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'reportunit' => array(  
							'notnull' => TRUE, 
					),
					'reportdate' => array(   
							'notnull' => TRUE, 
					),
					'vesselname' => array(  
							'notnull' => TRUE, 
					),
					'callsign' => array(   
							'notnull' => TRUE, 
					),
					'country' => array(  
							'notnull' => TRUE, 
					),
					'vesselType' => array(  
							'notnull' => TRUE, 
					),
					'goodsAndNum' => array(   
							'notnull' => TRUE, 
					),
					'anchorandanchordate' => array(  
							'notnull' => TRUE, 
					),
					'crossdate' => array(  
							'notnull' => TRUE,
					),
					'quasibyberth' => array(  
							'notnull' => TRUE, 
					),
					'predictberthdate' => array(   
							'notnull' => TRUE,
					),
					'ispilot' => array(   
							'notnull' => TRUE, 
					),
			),
			"messages" => array( // 提示信息
					'reportunit' => array(  
							'notnull' => "报告单位不为空",
					),
					'reportdate' => array(   
							'notnull' => "报告时间不为空", 
					),
					'vesselname' => array(   
							'notnull' => "船名不为空", 
					),
					'callsign' => array(  
							'notnull' => "船舶呼号不为空", 
					),
					'country' => array(   
							'notnull' => "国籍不为空", 
					),
					'vesselType' => array(  
							'notnull' => "船舶种类不为空", 
					),
					'goodsAndNum' => array(   
							'notnull' => "所载货物以及货量不为空",
					),
					'anchorandanchordate' => array(   
							'notnull' => "锚地及起锚时间不为空", 
					),
					'crossdate' => array(   
							'notnull' => "预计通过18#浮时间不为空", 
					),
					'quasibyberth' => array(  
							'notnull' => "拟靠泊位不为空",
					),
					'predictberthdate' => array(   
							'notnull' => "预计靠泊时间不为空", 
					),
					'ispilot' => array(   
							'notnull' => "是否引航不为空",
					),
			)
	);
	
}
