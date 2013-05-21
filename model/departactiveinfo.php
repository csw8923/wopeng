<?php
class departactiveinfo extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "departactiveinfo"; // 数据表的名称
  
   // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'ReportUnit' => array(  
							'notnull' => TRUE, 
					),
					'ReportDate' => array(  
							'notnull' => TRUE, 
					),
					'VesselName' => array(  
							'notnull' => TRUE, 
					),
					'Callsign' => array(  
							'notnull' => TRUE,
					),
					'Country' => array(  
							'notnull' => TRUE, 
					),
					'VesselType' => array(   
							'notnull' => TRUE,
					),
					'GoodsAndNum' => array(  
							'notnull' => TRUE,
					),
					'VesselLength' => array(  
							'notnull' => TRUE,
					),
					'MaxDraft' => array(   
							'notnull' => TRUE, 
					),
					'Berth' => array(   
							'notnull' => TRUE, 
					),
					'PredicSailDate' => array(   
							'notnull' => TRUE, 
					),
					'NextPort' => array(   
							'notnull' => TRUE, 
					),
					'CrossDate' => array(   
							'notnull' => TRUE, 
					),
					'IsPilot' => array(  
							'notnull' => TRUE, 
					),
			),
			"messages" => array( // 提示信息
		            'ReportUnit' => array(  
					        'notnull' => "报告单位不为空", 
					),
					'ReportDate' => array(   
							'notnull' => "报告时间不为空",
					),
					'VesselName' => array(   
							'notnull' => "船名不为空",
					),
					'Callsign' => array(  
							'notnull' => "船舶呼号不为空",
					),
					'Country' => array(   
							'notnull' => "国籍不为空",
					),
					'VesselType' => array(   
							'notnull' => "船舶种类不为空", 
					),
					'GoodsAndNum' => array(  
							'notnull' => "所载货物以及货量不为空", 
					),
					'VesselLength' => array(   
							'notnull' => "船长（米）不为空", 
					),
					'MaxDraft' => array(   
							'notnull' => "最大吃水（米）不为空",
					),
					'Berth' => array(   
							'notnull' => "锚地及起锚时间不为空",
					),
					'PredicSailDate' => array(   
							'notnull' => "预计通过18#浮时间不为空", 
					),
					'NextPort' => array(   
							'notnull' => "拟靠泊位不为空",
					),
					'CrossDate' => array(  
							'notnull' => "预计靠泊时间不为空", 
					),
					'IsPilot' => array(   // 这里是对email的验证规则
							'notnull' => "是否引航不为空", // email不能为空
					),
			)
	);
}
