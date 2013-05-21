<?php
class lib_category extends spModel
{
    var $pk = "id"; // 数据表的主键
    var $table = "category"; // 数据表的名称
	
	  // 定义验证规则
	var $verifier = array(
			"rules" => array( // 规则
					'pid' => array(  
							'notnull' => TRUE, 
					),
					'cname' => array(   
							'notnull' => TRUE, 
					),
					'seq' => array(   
							'notnull' => TRUE, 
					),
					'ismenu' => array(   
							'notnull' => TRUE,
					),
					'part' => array(   
							'notnull' => TRUE, 
					),
					'another' => array(  
							'notnull' => TRUE, 
					),
					'title' => array(  
							'notnull' => TRUE, 
					),
					'keywords' => array(  
							'notnull' => TRUE, 
					),
					'description' => array(  
							'notnull' => TRUE, 
					),
			),
			"messages" => array( // 提示信息
					'pid' => array(  
							'notnull' => TRUE, 
					),
					'cname' => array(   
							'notnull' => "分类名不能为空", 
					),
					'seq' => array(   
							'notnull' => "排序不能为空", 
					),
					'ismenu' => array(   
							'notnull' => "显示菜单不能为空", 
					),
					'part' => array(   
							'notnull' => "不能为空", 
					),
					'another' => array(  
							'notnull' => "不能为空", 
					),
					'title' => array(  
							'notnull' => "标题不能为空", 
					),
					'keywords' => array(  
							'notnull' => "关键字不能为空", 
					),
					'description' => array(  
							'notnull' => "说明不能为空", 
					),
			)
	);
	
    public function getCatalogList($id=0,$level=0)
    {
        $conditions = array('pid'=>$id);
        $results = $this->findAll($conditions,'seq ASC');
        $catlist = array();
        for($i=0;$i < $level *2 - 1;$i++){
            //$str .="　";
			$str .="";
        }
        if($level++){$str .= "┝";};
		//if($level++){$str .= "";};
        foreach ($results as $cat){
            $id = $cat['id'];
			      $pid = $cat['pid'];
            $name = $str.$cat['cname'];
			      $seq = $cat['seq'];
			      $ismenu = $cat['ismenu'];
			      $part = $cat['part'];
			      $another = $cat['another'];
            $value = array('id'=>$id,'pid'=>$pid,'cname'=>$name,'seq'=>$seq,'ismenu'=>$ismenu,'part'=>$part,'another'=>$another);
            $catlist[] = $value;
            $catlist = array_merge($catlist,$this->getCatalogList($id,$level));
        }
        return $catlist;
    }
	
	// 首页菜单 数据筛选
	public function getcatemenuList($id=0,$level=0)
    {
        $conditions = array('pid'=>$id);
		$conditions = "pid = ".$id." and ismenu = 1 ";
        $results = $this->findAll($conditions,'seq ASC');
        $catlist = array();
        for($i=0;$i < $level *2 - 1;$i++){
            //$str .="　";
			$str .="";
        }
        if($level++){$str .= "┝";};
		//if($level++){$str .= "";};
        foreach ($results as $cat){
            $id = $cat['id'];
			$pid = $cat['pid'];
            $name = $str.$cat['cname'];
			$seq = $cat['seq'];
			$ismenu = $cat['ismenu'];
			$part = $cat['part'];
			$another = $cat['another'];
            $value = array('id'=>$id,'pid'=>$pid,'cname'=>$name,'seq'=>$seq,'ismenu'=>$ismenu,'part'=>$part,'another'=>$another);
            $catlist[] = $value;
            $catlist = array_merge($catlist,$this->getcatemenuList($id,$level));
        }
        return $catlist;
    }
	
	// 首页栏目 数据筛选
	public function getcatepartList($id=0,$level=0)
    {
        $conditions = array('pid'=>$id);
		$conditions = "pid = ".$id." and part = 1 ";
        $results = $this->findAll($conditions,'seq ASC');
        $catlist = array();
        for($i=0;$i < $level *2 - 1;$i++){
            //$str .="　";
			$str .="";
        }
        if($level++){$str .= "┝";};
		//if($level++){$str .= "";};
        foreach ($results as $cat){
            $id = $cat['id'];
			$pid = $cat['pid'];
            $name = $str.$cat['cname'];
			$seq = $cat['seq'];
			$ismenu = $cat['ismenu'];
			$part = $cat['part'];
			$another = $cat['another'];
            $value = array('id'=>$id,'pid'=>$pid,'cname'=>$name,'seq'=>$seq,'ismenu'=>$ismenu,'part'=>$part,'another'=>$another);
            $catlist[] = $value;
            $catlist = array_merge($catlist,$this->getcatepartList($id,$level));
        }
        return $catlist;
    }

	
}
