<?php

class privateletterinfoform extends basis
{
	function index(){
		$filter = urldecode($this->spArgs("filter"));
		$classid = $this->spArgs("classid");
		$this->filter = $filter;  // 获取搜索关键字
		// 列出信息内容 且 修改分类名称
	    $user = spClass('privateletterinfo'); 
		if(empty($filter) && empty($classid))
		{ 
		  $conditions = "id >= '".date('Ymd',strtotime("-15 day"))."'";
		  $results = $user->findAll($conditions,'id DESC',NULL,"150"); 
		}
        $n=0;
		foreach ($results as $value) {
			$user = spClass('lib_user'); 
		    $conditions = array( 'uid' => $value['uid'] );
		    $result_user = $user->find($conditions);  // 分页
		    $results[$n]['uid'] = $result_user;

		    $user = spClass('lib_user'); 
		    $conditions = array( 'uid' => $value['replyid'] );
		    $result_user = $user->find($conditions);  // 分页
		    $results[$n]['replyid'] = $result_user;
		    $n++;
		}
		$this->results = $results; 
		//dump($this->results);
		// 列出信息内容	
		$this->id = $this->spArgs("id");
		$conditions = array( 'id' => $this->id );
		$e_user = spClass('privateletterinfo'); 
        $e_result = $e_user->findAll($conditions); 
		$this->content = $e_result[0]['content']; 
		$this -> display('admin/content/privateletter.html');
	}

	function add(){
		require('include/checking.php'); // 用户身份校验
		$this->replyid = $this->spArgs("replyid");

		$user = spClass('lib_user'); 
	    $conditions = array( 'uid' => $this->replyid );
	    $this->result_user = $user->find($conditions);  // 分页

		$this -> display('content/Clientfb/privateletter.html');
	}

	function addprivateletter(){
		$data = $this->spArgs(); // $data是提交上来的数据

		$user = spClass('lib_user'); 
	    $conditions = 'uname like '.$user->escape('%'.$data['replyid'].'%');
	    $result_user = $user->find($conditions);  // 分页
        if(!empty($result_user['uid']) && !empty($data['content'])){
	        $newrow = array( // PHP的数组
			'id'=>time(),
			'uid'=>$_SESSION["quser"]['uid'],
			'content'=>$data['content'],
			'replyid'=>$result_user['uid'],
			);
			$user = spClass('privateletterinfo');
			$newid = $user->create($newrow);
			$this->success("回复【".$data['replyid'].'】成功',spUrl("ucenter","UserPrivateletter"));
        }
        elseif(empty($data['content']))
        {
            $this->error("你没有填写内容哦！");
        }
        else
        {
        	$this->error("我们网站没有【".$data['replyid']."】这位好友哦！");
        }
    }

	function adddata(){
		$data = $this->spArgs(); // $data是提交上来的数据
        
		$user = spClass('lib_user'); 
	    $conditions = 'uname like '.$user->escape('%'.$data['replyid'].'%');
	    $result_user = $user->find($conditions);  // 分页
        if(!empty($result_user['uid']) && !empty($data['content'])){
	        $newrow = array( // PHP的数组
			'id'=>time(),
			'uid'=>$_SESSION["quser"]['uid'],
			'content'=>$data['content'],
			'replyid'=>$result_user['uid'],
			);
			$user = spClass('privateletterinfo');
			$newid = $user->create($newrow);
			echo("回复【".$data['replyid'].'】成功');
        }
        elseif(empty($data['content']))
        {
          echo("你没有填写内容哦！");
        }
        else
        {
          echo("我们网站没有【".$data['replyid']."】这位好友哦！");
        }
    }
	
	function showinfo(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id'],'uid'=>$data['uid'],'replyid'=>$data['replyid']); 
        $privateletter = spClass('privateletterinfo');
        $result_privateletter = $privateletter->find($conditions);
        if($_SESSION["quser"]['uid'] == $data['replyid'])
        {
	      $privateletter->update($conditions,array('mark'=>1)); 
        }
        
		$user = spClass('lib_user'); 
	    $conditions = array( 'uid' => $result_privateletter['uid'] );
	    $result_user = $user->find($conditions);  // 分页
	    $result_privateletter['uid'] = $result_user;

	    $user = spClass('lib_user'); 
	    $conditions = array( 'uid' => $result_privateletter['replyid'] );
	    $result_user = $user->find($conditions);  // 分页
	    $result_privateletter['replyid'] = $result_user;

		echo json_encode($result_privateletter);
	}

	function userajax(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$user = spClass('lib_user'); 
		$conditions = 'uname like '.$user->escape('%'.$data['name'].'%');
	    $result_user = $user->findAll($conditions);  // 分页
        if($result_user){
			foreach($result_user as $k=>$v){
				$str.=','.$v['uname'];
			}
			//生成表格
			echo $str="$str";
		}
	}

	function deldata(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$conditions = array('id'=>$data['id']); 
        $user = spClass('privateletterinfo');
        $data['msg'] = $user->delete($conditions);	
		echo( $data['msg'] );
	}

}
