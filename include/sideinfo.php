<?php
    // 人气排行
    if($this->info == "scenery")
    {
       $scenerydata = spClass('sceneryinfo');
    }
    else
    {
       $scenerydata = spClass('linesinfo');
    } 
    $conditions = array('id' => $this->id);
    $this->showscenery = $scenerydata->find($conditions);
    $conditionscity = array('cityid' => $this->showscenery['cityid']);
    $this->showsceneryAll = $scenerydata->findAll($conditionscity,'clicknumber DESC',NULL,"10"); // 好景点推荐
    // 哪玩达人
	$top_user = spClass('lib_user'); 
    $this->top_resultuser = $top_user->findAll(null,'integral DESC',NULL,"10"); 

    // 获取游记攻略排行
    $conditions = array( 'types' => $this->info,'linkid' => $this->id);
    $e_youji = spClass('youjiinfo'); 
    $this->out_youji = $e_youji->findAll($conditions,'id DESC',NULL,"8");

    // 获取论坛排行
	$conditions = array( 'types' => $this->info,'linkid' => $this->id );
	$e_answers = spClass('answersinfo'); 
    $this->out_answers = $e_answers->findAll($conditions);

    // 省
	$conditions_province = array( 'id' => $this->showscenery['provinceid'] );
	$t_province = spClass('lib_cityinfo'); 
	$this->province = $t_province->find($conditions_province); 
    // 市
	$conditions_city = array( 'id' => $this->showscenery['cityid'] );
	$t_city = spClass('lib_cityinfo'); 
	$this->city = $t_city->find($conditions_city);
    // 区
	$conditions_area = array( 'id' => $this->showscenery['areaid'] );
	$t_area = spClass('lib_cityinfo'); 
	$this->area = $t_area->find($conditions_area); 
?>