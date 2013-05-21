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
	$conditions = array('id' => $this->sid);
	$this->showscenery = $scenerydata->find($conditions);
	$this->showsceneryAll = $scenerydata->findAll(NULL,'clicknumber DESC',NULL,"10"); // 好景点推荐
?>