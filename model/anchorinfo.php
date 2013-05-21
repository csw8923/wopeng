<?php
class anchorinfo extends spModel
{
  var $pk = "id"; // 数据表的主键
  var $table = "anchorinfo"; // 数据表的名称
  
   // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'application' => array(  
							'notnull' => TRUE, 
					),
					'applicationdate' => array(  
							'notnull' => TRUE, 
					),
					'vesselname' => array(   
							'notnull' => TRUE, 
					),
					'port' => array(  
							'notnull' => TRUE, 
					),
					'totaltonnage' => array(   
							'notnull' => TRUE, 
					),
					'loadtonnage' => array( 
							'notnull' => TRUE, 
					),
					'vessellength' => array(  
							'notnull' => TRUE, 
					),
					'vesseltype' => array(  
							'notnull' => TRUE,
					),
					'tonnagestatus' => array(  
							'notnull' => TRUE, 
					),
					'expectedarrivaltime' => array(   
							'notnull' => TRUE, 
					),
					'anchordate' => array(  
							'notnull' => TRUE, 
					),
					'anchorliftingdate' => array(   
							'notnull' => TRUE,
					),
					'vesselcontact' => array(  
							'notnull' => TRUE,
					),
					'vesselcontactphone' => array(
							'notnull' => TRUE, 
					),
					'companyname' => array(  
							'notnull' => TRUE, 
					),
					'companycontact' => array( 
							'notnull' => TRUE,
					),
					'companycontactphone' => array(  
							'notnull' => TRUE, 
					),
					'companycontactfax' => array(  
							'notnull' => TRUE,
					),
					'anchorageselection' => array(  
							'notnull' => TRUE, 
					),
					'reasonandmeasure' => array( 
							'notnull' => TRUE, 
					),
			),
			
			"messages" => array( // 提示信息
					'application' => array(  
							'notnull' => "姓名不能为空",
					),
					'applicationdate' => array(   
							'notnull' => "双击输入时间", 
					),
					'vesselname' => array(   
							'notnull' => "请输入船名", 
					),
					'port' => array(   
							'notnull' => "请输入船港",
					),
					'totaltonnage' => array(   
							'notnull' => "请输入总吨", 
					),
					'loadtonnage' => array(   
							'notnull' => "请输入载重吨",
					),
					'vessellength' => array(   
							'notnull' => "请输入船长",
					),
					'vesseltype' => array(   
							'notnull' => "请输入船舶类型", 
					),
					'tonnagestatus' => array( 
							'notnull' => "请输入载货情况", 
					),
					'expectedarrivaltime' => array(  
							'notnull' => "请输入抵港时间", 
					),
					'anchordate' => array(   
							'notnull' => "请输入抛锚时间", 
					),
					'anchorliftingdate' => array(   
							'notnull' => "请输入起锚时间", 
					),
					'vesselcontact' => array(  
							'notnull' => "请输入船舶联系人", 
					),
					'vesselcontactphone' => array(   
							'notnull' => "请输入联系人电话", 
					),
					'companyname' => array(  
							'notnull' => "请输入公司名", 
					),
					'companycontact' => array(  
							'notnull' => "请输入公司联系人", 
					),
					'companycontactphone' => array(  
							'notnull' => "请输公司联系人电话", 
					),
					'companycontactfax' => array(   
							'notnull' => "请输入传真", 
					),
					'anchorageselection' => array(  
							'notnull' => "请输入锚地选择", 
					),
					'reasonandmeasure' => array(  
							'notnull' => "请输入申请锚泊原因", 
					),
		)
	);
	
}
