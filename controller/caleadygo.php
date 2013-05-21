<?php

class caleadygo extends basis
{
	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据

        // $data['uid'] = 1;
        // $data['types'] = 'scenery';
        // $data['linkid'] = 2;
		$conditions = array('uid'=>$data['uid'],'types'=>$data['types'],'linkid'=>$data['linkid']);
	    $aleadygo = spClass('aleadygo');
	    $this->out_caleadygo = $aleadygo->find($conditions);

        if(empty($this->out_caleadygo['linkid'])){
			$newrow = array( // PHP的数组
			'uid'=>$data['uid'],
			'types'=>$data['types'],
		    'linkid'=>$data['linkid']
			);
			$user = spClass('aleadygo');
			$newid = $user->create($newrow);
			$data['msg'] = "已经将该景点录进你去过的地方了.";
     	}
     	else
     	{
     		$data['msg'] = "你去过的地方已经存在该景点.";
     	}

	    $conditions = array( 'types'=>$data['types'],'linkid'=>$data['linkid'] );
		$data['sum'] = $aleadygo->findCount($conditions);

		echo json_encode( $data );
	}

	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('uid'=>$_SESSION["quser"]['uid'],'types'=>$data['types'],'linkid'=>$data['linkid']); 
        $user = spClass('aleadygo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

}
