<?php

class city extends basis
{
	function index(){
		$this->id = $this->spArgs("id");
		$type = $this->spArgs("type");
        $conditions = array( 'used' => 1 );
        $t_chain = spClass('chain');
        $this->keywords = $t_chain->findAll($conditions);

        //城市
        $cityinfo = spClass('lib_cityinfo');
        $this->cityinfo =$cityinfo->findAll(array("pid"=>1));
		if($type == 'scenery'){
		   $citypage = spClass('lib_cityinfo');
           $this->citypage =$citypage->find(array("id"=>$this->id));
		   $conditions = array( 'cityid' => $this->id ,'ispass' => 1);
		   $user = spClass('sceneryinfo'); 
	       //$this->results = $user->findAll($conditions,"id desc");  // 分页

	       $this->results = $user->spPager($this->spArgs('page', 1), 15)->findAll($conditions,'id DESC');
           $this->pager = $user->spPager()->getPager();

	       if(empty($this->results[0]['id']))
	       {
	       	   $conditions = array( 'areaid' => $this->id ,'ispass' => 1);
		       $user = spClass('sceneryinfo'); 
	           $this->results = $user->findAll($conditions,"id desc");  // 分页
	       }

	        $citys = array();
	        $areaids = array();
			foreach ($this->results as $value)
			{
			  	//dump($value['cityid'].'-'.$value['areaid']);
			  	$conditions_city = array( 'id' => $value['cityid'] );
				$t_city = spClass('lib_cityinfo'); 
				$citys[] = $t_city->find($conditions_city);

				$conditions_area = array( 'id' => $value['areaid'] );
				$t_area = spClass('lib_cityinfo'); 
				$areaids[] = $t_area->find($conditions_area); 
			}
			$this->citys = $citys;
			$this->areaids = $areaids;

	       $this -> display('city_scenery.html'); // 城市列出
	    }
	    elseif($type == 'lines')
	    {

	       $citypage = spClass('lib_cityinfo');
           $this->citypage =$citypage->find(array("id"=>$this->id));
		   $conditions = array( 'cityid' => $this->id ,'ispass' => 1);
		   $user = spClass('linesinfo'); 
	       //$results = $user->findAll($conditions,"id desc");  // 分页
	       $results = $user->spPager($this->spArgs('page', 1), 1)->findAll($conditions,'id desc');
           $this->pager = $user->spPager()->getPager();

	       $n=0;
	       foreach ($results as $sdvalue) {
		       $conditionssd = array( 'linesid' => $sdvalue['id'] );
			   $soldoutinfo = spClass('soldoutinfo'); 
		       $showsoldoutinfo = $soldoutinfo->find($conditionssd); 
		       if(!empty($showsoldoutinfo['title']))
		       {
		          $results[$n]['title'] = $showsoldoutinfo['title'];
		       }
		        $n++;
	       }

	       if(empty($results[0]['id']))
	       {
	       	   $conditions = array( 'areaid' => $this->id ,'ispass' => 1);
		       $user = spClass('linesinfo'); 
	           $this->results = $user->findAll($conditions,"id desc");  // 分页
	       }

	        $citys = array();
	        $areaids = array();
			foreach ($results as $value)
			{
			  	//dump($value['cityid'].'-'.$value['areaid']);
			  	$conditions_city = array( 'id' => $value['cityid'] );
				$t_city = spClass('lib_cityinfo'); 
				$citys[] = $t_city->find($conditions_city);

				$conditions_area = array( 'id' => $value['areaid'] );
				$t_area = spClass('lib_cityinfo'); 
				$areaids[] = $t_area->find($conditions_area); 
			}
			$this->citys = $citys;
			$this->results = $results;
			$this->areaids = $areaids;
			$this->cityabb = "xm";
	       $this -> display('city_lines.html'); // 城市列出
	    }
	}

	function xm(){
	  require('include/sideinfo.php'); // 侧边信息
      require('include/xiamen.php');
	}
	
}
