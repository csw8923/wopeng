<?php

class siteseo extends basis
{
	
	function editdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('stitle'=>$data['stitle'],'skeyword'=>$data['skeyword'],'sdirections'=>$data['sdirections']);
		if($data['types'] == "lines")
		{ $user = spClass('lineseoinfo'); }
	    else
	    { $user = spClass('seoinfo'); }
		
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function youjidata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('youjiinfo');
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function youjilistdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('yjtitle'=>$data['yjtitle'],'yjkeyword'=>$data['yjkeyword'],'yjdirections'=>$data['yjdirections']);
		if($data['types'] == "lines")
		{ $user = spClass('lineseoinfo'); }
	    else
	    { $user = spClass('seoinfo'); }

		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function commentdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('commentinfo');
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function commentlistdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('cmtitle'=>$data['cmtitle'],'cmkeyword'=>$data['cmkeyword'],'cmdirections'=>$data['cmdirections']);
		if($data['types'] == "lines")
		{ $user = spClass('lineseoinfo'); }
	    else
	    { $user = spClass('seoinfo'); }
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function photolistdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('phtitle'=>$data['phtitle'],'phkeyword'=>$data['phkeyword'],'phdirections'=>$data['ph directions']);
		if($data['types'] == "lines")
		{ $user = spClass('lineseoinfo'); }
	    else
	    { $user = spClass('seoinfo'); }
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function visitsdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('visitsinfo');
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function visitslistdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('vstitle'=>$data['vstitle'],'vskeyword'=>$data['vskeyword'],'vsdirections'=>$data['vsdirections']);
		if($data['types'] == "lines")
		{ $user = spClass('lineseoinfo'); }
	    else
	    { $user = spClass('seoinfo'); }
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function answersdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('ctitle'=>$data['ctitle'],'ckeyword'=>$data['ckeyword'],'cdirections'=>$data['cdirections']);
		$user = spClass('answersinfo');
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}

	function answerslistdata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']);
		$row = array('astitle'=>$data['astitle'],'askeyword'=>$data['askeyword'],'asdirections'=>$data['asdirections']);
		if($data['types'] == "lines")
		{ $user = spClass('lineseoinfo'); }
	    else
	    { $user = spClass('seoinfo'); }
		$data['msg'] = $user->update($conditions, $row);
		echo($data['msg']);
	}
	
}
