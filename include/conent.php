<?php
$conditions_youji = array('types'=>$this->info,'linkid' => $this->sid,'ispass' => 0);
	$s_youji = spClass('youjiinfo'); 
	$this->youji = $s_youji->findAll($conditions_youji,'clicknumber DESC',NULL,"4");
   
$conditions_answers = array('types'=>$this->info,'linkid' => $this->sid,'ispass' => 0);
	$s_answers = spClass('answersinfo'); 
	$this->answers = $s_answers->findAll($conditions_answers);
	//dump($this->answers);

$conditions_locals = array('types'=>$this->info,'linkid' => $this->sid,'ispass' => 0);
	$s_locals = spClass('localsinfo'); 
	$this->locals = $s_locals->findAll($conditions_locals); 

$conditions_visits = array( 'types' => $this->info,'linkid' => $this->sid,'ispass' => 0);
	$s_visits = spClass('visitsinfo'); 
	$this->visits = $s_visits->findAll($conditions_visits); 

$conditions_comment = array( 'types' => $this->info,'linkid' => $this->sid,'ispass' => 0);
	$s_comment = spClass('commentinfo'); 
	$this->comment = $s_comment->findAll($conditions_comment,'id DESC',NULL,"4");

$conditions = array( 'types' => $this->info,'linkid' => $this->sid);
	$picture = spClass('pictureinfo'); 
	$this->pictures = $picture->findAll($conditions,'id DESC',NULL,"4");
	$this->picturessum = $picture->findAll($conditions);
	$this->picturesnum = count($this->picturessum);
?>