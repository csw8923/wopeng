<?php
class main extends basis
{	
	// 首页
	public function index(){ 
	  require('include/xiamen.php'); // 厦门城市
	}
	
	// 分类列表
	public function category(){
	  $another = $this->spArgs("another");
	  $conditions = array( 'another' => $another );
	  $cateinfo = spClass('lib_category'); 
	  $cate_info = $cateinfo->find($conditions); 
	  $conditions = array( 'classid' => $cate_info['id']);
	  $messageinfo = spClass('messageinfo'); 
	  $m_result = $messageinfo->spPager($this->spArgs('page', 1), 15)->findAll($conditions,'id DESC'); // 查找				  
	  $this->messagedata = $m_result; 
	  $this->pager = $messageinfo->spPager()->getPager();
	  
	  $conditions = array( 'classid' => $cate_info['id']);
	  $messagetop = spClass('messageinfo'); 
	  $top_result = $messagetop->findAll($conditions,'traffic DESC',NULL,"12"); // 查找				  
	  $this->topdata = $top_result; 
	  
	  $conditions = "classid = ".$cate_info['id']." and picurl != ''";
	  $toppic = spClass('messageinfo'); 
	  $top_pic = $toppic->findAll($conditions,'traffic DESC',NULL,"9"); // 查找				  
	  $this->top_pic = $top_pic; 
	  
	   // 子菜单数据
	  $cataObj = spClass("lib_category");
	  $this->subclass = $cataObj->getCatalogList($cate_info['id'],0);
	  $this->currentcid = $cate_info['id']; 
	  
	  // 当前菜单信息
      $conditions = array( 'id' => $cate_info['id']);
	  $category = spClass('lib_category'); 
	  $cate_result = $category->find($conditions); 
	  $this->cname = $cate_result['cname'];
	  $this->id = $cate_result['id'];
	  $this->another = $cate_result['another'];
	  $this->title = $cate_result['title'];
	  $this->keywords = $cate_result['keywords'];
	  $this->description = $cate_result['description'];
	  

	  $rulesinfo = spClass('rulesinfo'); 
	  $conditions = array( 'part' => $this->id ,'approval' => 1 );
	  $rulescollect = $rulesinfo->findAll($conditions,'rand(),id DESC',NULL,"5");  // 分页
      $newrules=array();
	  foreach ($rulescollect as $value)
	  {
		 $md5file = md5_file($value['geturl']);
	     //echo $md5file . "<br />";
		 $conditionsmd5 = array('id'=>$this->id);
		 $rowmd5 = array('filemd5'=>$md5file);
		 $rulesinfomd5 = spClass('rulesinfo');
		 $rulesinfomd5->update($conditionsmd5, $rowmd5); 
		 $newrules[] = array('id'=>$value['id'],'part'=>$value['part']);
	  }
	  $this->rulescollect = $newrules;
	  $this -> display('category.html');   
	}
	
	// 内容页面
	public function article(){
      $sid = $this->spArgs("sid");
	  $conditions = array('id'=> $sid );
	  $messageinfo = spClass('messageinfo'); 
	  $msg_result = $messageinfo->find($conditions); 
	  $this->username = $msg_result['username'];
	  $this->releasedate = $msg_result['releasedate'];
	  $this->content = $msg_result['content'];
	  $this->cjurls = $msg_result['cjurls'];
	  $this->cjid = $msg_result['cjid'];
	  $this->classid = $msg_result['classid'];
	  $this->traffic = $msg_result['traffic'];
	  //dump($this->cjurls);
	  
	  $conditions = array('id'=> $sid+1 );
	  $nextinfo = spClass('messageinfo'); 
	  $msg_next = $nextinfo->find($conditions);
	  $this->nid = $msg_next['id']; 
	  $this->ntitle = $msg_next['title']; 
	  
	  $conditions = array('id'=> $sid-1 );
	  $lastinfo = spClass('messageinfo'); 
	  $msg_last = $lastinfo->find($conditions); 
	  $this->lid = $msg_last['id'];
	  $this->ltitle = $msg_last['title']; 

	  $conditions = array( 'classid' => $this->classid);
	  $messagetop = spClass('messageinfo'); 
	  $top_result = $messagetop->findAll($conditions,'traffic DESC',NULL,"12"); // 查找				  
	  $this->topdata = $top_result; 
	  
	  $conditions = array( 'id' => $this->classid );
	  $cateinfo = spClass('lib_category'); 
	  $cate_info = $cateinfo->find($conditions); 
	  $this->title = $msg_result['title'].' - '.$cate_info['title'].' - '.$this->title;
	  //$this->keywords = "ddd";
	  $this->wztitle = $msg_result['title'];
	  
	  if($this->cjurls != '')
	  {
		  $rulesinfo = spClass('rulesinfo'); 
		  $conditions = array( 'id' => $this->cjid );
		  $result = $rulesinfo->find($conditions);  // 分页
		  // 实际代入参数
		  $getdetailurl = $this->cjurls;
		  $getrange = $result['getrange'];
		  $dpatterns = $result['dpatterns'];
		  $dreplacements = $result['dreplacements'];
		  $compatible = $result['compatible'];
		  
		  if(file_exists("article/".$sid.".txt") != 1 || filesize("article/".$sid.".txt") == 0)
		  {
            // 实际代入参数
            if($compatible != "1")
            {
              $ch = curl_init();  
              $data = array('name' => '262055400');
              curl_setopt($ch, CURLOPT_URL, $getdetailurl);  
              //curl_setopt($ch, CURLOPT_HEADER,false);  
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出
              curl_setopt($ch, CURLOPT_POST, 1);  
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
              curl_setopt($ch, CURLOPT_HEADER, 0);  
              $result=curl_exec($ch);  
              curl_close($ch);
             }
             else
             {
              $result = file_get_contents($getdetailurl);
             }
            // 编码转换
            $coding = mb_detect_encoding($result, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
            if ($coding == 'UTF-8')
            {}
            else
            { $result = mb_convert_encoding($result, "UTF-8", "GBK"); }
            $getdetailurl = $getrange;
            $getdetailurl = str_replace("[detail]","(.*?)",$getdetailurl);
            $getdetailurl = str_replace("/","\/",$getdetailurl);
            preg_match('/'.$getdetailurl.'/s', $result, $matches);
            // 自定义 替换
             // ==== 寻找内容 ====
            $patterns = array();
            $patterns_temp = $dpatterns;
            $patterns = explode(",",$patterns_temp);
            $i=0;
            foreach ($patterns as $value)
            {
             $patterns[$i] = "/".$value."/s";
             $i++;
            }
            // ==== 替换内容 ====
            $replacements = array();
            $replacements_temp = $dreplacements;
            $replacements = explode(",",$replacements_temp);
            // ==== 执行替换 ====
            $detail = preg_replace($patterns, $replacements,$matches[1]);
            
            $chaininfo = spClass('chain'); 
            $conditions_chain = array( 'useful' => 1);
            $resultchain = $chaininfo->findAll($conditions_chain);  // 分页
          
            foreach ($resultchain as $values)
            {
              $detail = str_replace($values['keywords'],"<a target='_blank' href='".$values['links']."'>".$values['keywords']."</a>",$detail);
            }
        
		     file_put_contents("article/".$sid.".txt",$detail);
		     $this->content = $detail;
		  }
		  else
		  {
        $filedetail = file_get_contents("article/".$sid.".txt");
		$filedetail = preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si","",$filedetail);
        $this->content = $filedetail;
		  }
		  $this->description = str_replace(" ","",mb_substr(trim(htmlspecialchars_decode(strip_tags($this->content))), 0, 150, 'utf-8'));
	  }
	  else
	  {
		  $this->content = $msg_result['content']; 
          $this->description = str_replace(" ","",mb_substr(trim(htmlspecialchars_decode(strip_tags($this->content))), 0, 150, 'utf-8'));
	  }

	  preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i',$this->content,$match);
	  $condition_pic = array('id'=>$sid); 
	  $row = array('picurl'=>$match[1],'traffic'=>$this->traffic+1);
	  $msgpic = spClass('messageinfo');
	  $msgpic->update($condition_pic, $row);
	  
	  $rulesinfo = spClass('rulesinfo'); 
	  $conditions = array( 'part' => $this->cjid ,'approval' => 1);
	  $rulescollect = $rulesinfo->findAll($conditions);  // 分页
	  $newrules=array();
	  foreach ($rulescollect as $value)
	  {
		 $md5file = md5_file($value['geturl']);
	     //echo $md5file . "<br />";
		 $conditionsmd5 = array('id'=>$this->id);
		 $rowmd5 = array('filemd5'=>$md5file);
		 $rulesinfomd5 = spClass('rulesinfo');
		 $rulesinfomd5->update($conditionsmd5, $rowmd5); 
		 $newrules[] = array('id'=>$value['id'],'part'=>$value['part']);
	  }
	  $this->rulescollect = $newrules;
	  $this -> display('article.html');   
	}

	public function search(){
		$keyword = $this->spArgs("keyword");
		$sceneryinfo = spClass('sceneryinfo'); 
		$conditions = 'title like '.$sceneryinfo->escape('%'.urldecode($keyword).'%');
		$result = $sceneryinfo->findAll($conditions);
		
		$p=0;
		foreach ($result as $value) {
			$conditions_city = array( 'id' => $value['provinceid'] );
			$t_city = spClass('lib_cityinfo'); 
			$result[$p]['provinceid'] = $t_city->find($conditions_city);
			$p++;
		}

        $c=0;
		foreach ($result as $value) {
			$conditions_city = array( 'id' => $value['cityid'] );
			$t_city = spClass('lib_cityinfo'); 
			$result[$c]['cityid'] = $t_city->find($conditions_city);
			$c++;
		}
        
        $a=0;
		foreach ($result as $value) {
			$conditions_city = array( 'id' => $value['areaid'] );
			$t_city = spClass('lib_cityinfo'); 
			$result[$a]['areaid'] = $t_city->find($conditions_city);
			$a++;
		}
		//dump($result);
		$this->result = $result;
		
	    // 推荐好景点
		$scenerydata = spClass('sceneryinfo'); 
	    $this->showsceneryAll = $scenerydata->findAll(null,'clicknumber DESC',NULL,"10"); // 好景点推荐
		
		// 哪玩达人
		$top_user = spClass('lib_user'); 
	    $this->top_resultuser = $top_user->findAll(null,'integral DESC',NULL,"8"); 
	    $this->cityabb = "xm";

		$this->display('search.html');
	}
	
	public function filesearch(){
		$searchinput = $this->spArgs("filename");
		$filelist = spClass('filelist'); 
		$conditions = 'sourcefile like '.$filelist->escape('%'.$searchinput.'%');
		// $conditions = "  title like '%$searchinput%' ";
		$this->result = $filelist->findAll($conditions);
		$this->display('filesearch.html');
	}

	function userajax(){
		$data = $this->spArgs(); // $data是提交上来的数据
		$user = spClass('sceneryinfo'); 
		$conditions = 'title like '.$user->escape('%'.$data['name'].'%');
	    $result_user = $user->findAll($conditions);  // 分页
        if($result_user){
			foreach($result_user as $k=>$v){
				$str.=','.$v['title'];
			}
			//生成表格
			echo $str="$str";
		}
	}

}	
