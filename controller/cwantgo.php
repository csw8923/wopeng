<?php

class cwantgo extends basis
{
	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据

        // $data['uid'] = 1;
        // $data['types'] = 'scenery';
        // $data['linkid'] = 2;
		$conditions = array('uid'=>$data['uid'],'types'=>$data['types'],'linkid'=>$data['linkid']);
	    $wantgo = spClass('wantgo');
	    $this->out_wantgo = $wantgo->find($conditions);

        if(empty($this->out_wantgo['linkid'])){
			$newrow = array( // PHP的数组
			'uid'=>$data['uid'],
			'types'=>$data['types'],
		    'linkid'=>$data['linkid']
			);
			$user = spClass('wantgo');
			$newid = $user->create($newrow);
			$data['msg'] = "已经将该景点录进你的计划中了.";
     	}
     	else
     	{
     		$data['msg'] = "你的计划中已经存在该景点.";
     	}

	    $conditions = array( 'types'=>$data['types'],'linkid'=>$data['linkid'] );
		$data['sum'] = $wantgo->findCount($conditions);

		echo json_encode( $data );
	}

	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('uid'=>$_SESSION["quser"]['uid'],'types'=>$data['types'],'linkid'=>$data['linkid']); 
        $user = spClass('wantgo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

}
