<?php
		if(empty($_SESSION["quser"]['uid']))
		{$this->error("你没有登录哦！请先登录...", spUrl("login","index")."?url=".$this->current_url);}
?>