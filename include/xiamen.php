<?php
	$id = $this->spArgs("id");
	$tag = $this->spArgs("tag");

    $conditions = array( 'used' => 1 );
	$t_chain = spClass('chain');
	$this->keywords = $t_chain->findAll($conditions,'seq ASC',NULL,"20");

	// 获取游记攻略排行
    $e_youji = spClass('youjiinfo'); 
    $this->out_youji = $e_youji->findAll(null,'clicknumber DESC',NULL,"8");

    // 获取论坛排行
	$e_answers = spClass('answersinfo'); 
    $this->out_answers = $e_answers->findAll(null,'clicknumber DESC',NULL,"8");

    // 推荐好景点
	$scenerydata = spClass('sceneryinfo'); 
    $this->showsceneryAll = $scenerydata->findAll(null,'clicknumber DESC',NULL,"10"); // 好景点推荐

    // 市
	$conditions_city = array( 'id' => 2 );
	$t_city = spClass('lib_cityinfo'); 
	$this->city = $t_city->find($conditions_city);

    //城市
    $cityinfo = spClass('lib_cityinfo');
    $this->cityinfo =$cityinfo->findAll(array("pid"=>1),'seq ASC',NULL,"20");

	if(empty($id) && $tag == 'tag'){
        $conditions = array( 'id' => $id );
        $t_chain = spClass('chain');
        $t_show = $t_chain->find($conditions);
        $this->keywordsname = $t_show['keywords'];

	    $conditions = array( 'used' => 1 );
		$keywords = $t_chain->findAll($conditions,'sort desc',NULL,"20");
        
        $num = 0;
        foreach ($keywords as $value) {
        	if($value['istop'] == "1")
        	{
        		$conditions = "ispass = 1 and tags like '%".$value['keywords']."%'";
		        $user = spClass('sceneryinfo');
		        $results = $user->findAll($conditions,'id DESC',NULL,"10");
		        $keywords[$num]['cdirections'] = $results;
            }
            $num++;
        }
        $this->keywords = $keywords;
        
        // 当季最佳
		$conditions = array( 'cityid' => 2 ,'ispass' => 1);
		$results_bset = $user->findAll($conditions,'tophot DESC,id DESC',NULL,"10"); 
		$this->tags_bset = $results_bset;

		// 人气排行
		$conditions = array( 'cityid' => 2 ,'ispass' => 1);
		$results_traffic = $user->findAll($conditions,'clicknumber DESC',NULL,"10"); 
		$this->tags_traffic = $results_traffic;

        if($this->keywords  == null)
        {
            $this -> display('404.php');
        }
        else
        {

            $this -> display('tag.html'); // 厦门关键词首页
        }

	}
	elseif(empty($id) && $tag == 'lines'){

        $conditions = array( 'id' => $id );
        $t_chain = spClass('chain');
        $t_show = $t_chain->find($conditions);
        $this->keywordsname = $t_show['keywords'];

	    $conditions = array( 'used' => 1 );
		$keywords = $t_chain->findAll($conditions,'sort desc',NULL,"20");

        $num = 0;
        foreach ($keywords as $value) {
        	if($value['istop'] == "1")
        	{
        		$conditions = "ispass = 1 and tags like '%".$value['keywords']."%'";
        		$user = spClass('linesinfo');
		        $results = $user->findAll($conditions,'id DESC',NULL,"10");
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
		        $keywords[$num]['cdirections'] = $results;
            }
            $num++;
        }
        $this->keywords = $keywords;
        $this->cityabb = "xm";

        // 当季最佳
		$conditions = array( 'cityid' => 2 ,'ispass' => 1);
		$results_bset = $user->findAll($conditions,'tophot DESC,id DESC',NULL,"20"); 

		$n=0;
        foreach ($results_bset as $sdvalue) {
	        $conditionssd = array( 'linesid' => $sdvalue['id'] );
			$soldoutinfo = spClass('soldoutinfo'); 
	        $showsoldoutinfo = $soldoutinfo->find($conditionssd); 
	        if(!empty($showsoldoutinfo['title']))
	        {
	          $results_bset[$n]['title'] = $showsoldoutinfo['title'];
	        }
	        $n++;
        }

		$this->tags_bset = $results_bset;

		// 人气排行
		$conditions = array( 'cityid' => 2 ,'ispass' => 1);
		$results_traffic = $user->findAll($conditions,'clicknumber DESC',NULL,"20"); 

		$n=0;
        foreach ($results_traffic as $sdvalue) {
	        $conditionssd = array( 'linesid' => $sdvalue['id'] );
			$soldoutinfo = spClass('soldoutinfo'); 
	        $showsoldoutinfo = $soldoutinfo->find($conditionssd); 
	        if(!empty($showsoldoutinfo['title']))
	        {
	          $results_traffic[$n]['title'] = $showsoldoutinfo['title'];
	        }
	        $n++;
        }

		$this->tags_traffic = $results_traffic;

        if($this->keywords  == null)
        {
            $this -> display('404.php');
        }
        else
        {

            $this -> display('lines.html'); // 厦门关键词首页
        }

	}
	elseif(!empty($id) && $tag == 'tag' || !empty($id) && $tag == 'day')
	{
		$conditions = array( 'id' => $id );
		$t_chain = spClass('chain'); 
		$t_show = $t_chain->find($conditions); 
		$this->keywordsname = $t_show['keywords'];
		$this->keywordsid = $t_show['id'];
        $this->show = $t_show;
		$conditions = "ispass = 1 and tags like '%".$t_show['keywords']."%'";
		$user = spClass('sceneryinfo'); 
		$results = $user->spPager($this->spArgs('page', 1), 15)->findAll($conditions,'id DESC');
        $this->pager = $user->spPager()->getPager();
		//dump($results); 

		// 城市标题
		$conditions_city = array( 'citystate' => '厦门市' );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);
        
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
		$this->areaids = $areaids;
		//dump($this->areaids);

		$this->tags = $results;
		if($this->tags == null || $this->keywords  == null)
		{
			$this -> display('404.php');
		}
		else
		{
		    $this -> display('content/tag.html');
	    }
	}
	elseif($tag == 'rank' && $id == 'bset') //当季最佳
	{
		$this->keywordsname = "当季最佳";
		$this->id = $id;
		$this->show = $t_show;
		$conditions = "ispass = 1 and tags like '%".$t_show['keywords']."%'";
		$user = spClass('sceneryinfo'); 
		$results = $user->findAll($conditions,'clicknumber DESC',NULL,"20"); 
		$this->tags = $results;

		// 城市标题
		$conditions_city = array( 'citystate' => '厦门市' );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);

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
		$this->areaids = $areaids;

		if($this->tags == null || $this->keywords  == null)
		{
			$this -> display('404.php');
		}
		else
		{
		    $this -> display('content/rank.html');
	    }
	}
	elseif($tag == 'rank' && $id == 'linebset') //当季最佳
	{
		$this->keywordsname = "当季最佳";
		$this->id = $id;
		$this->show = $t_show;
		$conditions = "ispass = 1 and tags like '%".$t_show['keywords']."%'";
		$user = spClass('linesinfo'); 
		$this->cityabb = "xm";
		$results = $user->findAll($conditions,'clicknumber DESC',NULL,"20"); 

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

		$this->tags = $results;

		// 城市标题
		$conditions_city = array( 'citystate' => '厦门市' );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);

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
		$this->areaids = $areaids;

		if($this->tags == null || $this->keywords  == null)
		{
			$this -> display('404.php');
		}
		else
		{
		    $this -> display('content/linerank.html');
	    }
	}
	elseif($tag == 'rank' && $id == 'traffic') // 人气排行
	{
		$this->keywordsname = "人气排行";
		$this->id = $id;
        $this->show = $t_show;
		$conditions = "ispass = 1 and tags like '%".$t_show['keywords']."%'";
		$user = spClass('sceneryinfo'); 
		$results = $user->findAll($conditions,'clicknumber DESC',NULL,"20"); 
		$this->tags = $results;

		// 城市标题
		$conditions_city = array( 'citystate' => '厦门市' );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);

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
		$this->areaids = $areaids;

		if($this->tags == null || $this->keywords  == null)
		{
			$this -> display('404.php');
		}
		else
		{
		    $this -> display('content/rank.html');
	    }
	}
	elseif($tag == 'rank' && $id == 'linetraffic') // 人气排行
	{
		$this->keywordsname = "人气排行";
		$this->id = $id;
        $this->show = $t_show;
		$conditions = "ispass = 1 and tags like '%".$t_show['keywords']."%'";
		$user = spClass('linesinfo'); 
		$this->cityabb = "xm";
		$results = $user->findAll($conditions,'clicknumber DESC',NULL,"20");

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

		$this->tags = $results;

		// 城市标题
		$conditions_city = array( 'citystate' => '厦门市' );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);

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
		$this->areaids = $areaids;

		if($this->tags == null || $this->keywords  == null)
		{
			$this -> display('404.php');
		}
		else
		{
		    $this -> display('content/linerank.html');
	    }
	}
	elseif($tag == 'rank' && $id == 'new') // 最新发现
	{
		$conditions = array( 'id' => $id );
		$t_chain = spClass('chain'); 
		$t_show = $t_chain->find($conditions); 
		$this->keywordsname = "最新发现";
		$this->id = $id;
        $this->show = $t_show;
		$conditions = "ispass = 1 and tags like '%".$t_show['keywords']."%'";
		$user = spClass('sceneryinfo'); 
		$results = $user->findAll($conditions,'id DESC',NULL,"20"); 
		$this->tags = $results;

		// 城市标题
		$conditions_city = array( 'citystate' => '厦门市' );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);

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
		$this->areaids = $areaids;

		if($this->tags == null || $this->keywords  == null)
		{
			$this -> display('404.php');
		}
		else
		{
		    $this -> display('content/rank.html');
	    }
	}
	elseif($tag == 'rank' && $id == 'linenew') // 最新发现
	{
		$conditions = array( 'id' => $id );
		$t_chain = spClass('chain'); 
		$t_show = $t_chain->find($conditions); 
		$this->keywordsname = "最新发现";
		$this->id = $id;
        $this->show = $t_show;
		$conditions = "ispass = 1 and tags like '%".$t_show['keywords']."%'";
		$user = spClass('linesinfo'); 
		$this->cityabb = "xm";
		$results = $user->findAll($conditions,'id DESC',NULL,"20"); 

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

		$this->tags = $results;

		// 城市标题
		$conditions_city = array( 'citystate' => '厦门市' );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);

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
		$this->areaids = $areaids;

		if($this->tags == null || $this->keywords  == null)
		{
			$this -> display('404.php');
		}
		else
		{
		    $this -> display('content/linerank.html');
	    }
	}
	elseif($tag == 'hot' && $id == 'youji') // 最新
	{
		$conditions = array('cityid' => 2 );
	    $e_youji = spClass('youjiinfo');

        //标题栏的所有游戏列表
        $this->youji = $e_youji->spPager($this->spArgs('page', 1), 5)->findAll($conditions,'id DESC',NULL,"20");
        $this->pager = $e_youji->spPager()->getPager();
        if($this->youji == null)
		{
			import(APP_PATH.'/tpl/404.php');exit();
		}
		else
		{
		    $this -> display('youji.html');
	    }
	}
	elseif($tag == 'hot' && $id == 'bdlist') // 最新
	{
		$conditions = array('cityid' => 2 );
	    $e_youji = spClass('listbdinfo');

        //标题栏的所有游戏列表
        $this->bdlist = $e_youji->spPager($this->spArgs('page', 1), 10)->findAll($conditions,'id DESC',NULL,"20");
        $this->pager = $e_youji->spPager()->getPager();
        if($this->bdlist == null)
		{
			import(APP_PATH.'/tpl/404.php');exit();
		}
		else
		{
		    $this -> display('bdlist.html');
	    }
	}
	elseif($tag == 'hot' && $id == 'comment') // 最新
	{
    	$conditions = array('cityid' => 2 );
	    $e_comment = spClass('commentinfo');
        $this->comment = $e_comment->spPager($this->spArgs('page', 1), 10)->findAll($conditions,'id DESC',NULL,"20");

        $sceneryuser=array();
        foreach ($this->comment as $value)
		{
			$conditions = array( 'ispass' => 1,'id' => $value['linkid']);
            $sceneryinfo = spClass('sceneryinfo');
            $sceneryinfo = $sceneryinfo->find($conditions,"id DESC","id,title","0,10");
            $sceneryuser[] = array('id'=>$sceneryinfo['id'],'title'=>$sceneryinfo['title']);
		}
        $this->sceneryuser = $sceneryuser;

        // 获取头像
        $userarray = array();
        foreach ($this->comment as $value)
		{
		  	$conditions = array( 'uid' => $value['userid'] );
			$e_user = spClass('lib_user'); 
	        $e_result = $e_user->find($conditions);
	        $userarray[] = $e_result['avatar'];
		}
        $this->userarray = $userarray;
        // 获取头像
        $this->pager = $e_comment->spPager()->getPager();
        if($this->comment == null)
		{
			import(APP_PATH.'/tpl/404.php');exit();
		}
		else
		{
		    $this -> display('comment.html');
	    }
	}
    elseif($tag == 'hot' && $id == 'activity') // 最新
	{

		$conditions_push = array('cityid' => 2,'ispush' => 1);
	    $s_activity = spClass('activityinfo');
        //标题栏的所有游戏列表
        $this->activity_push = $s_activity->findAll($conditions_push,'id DESC',NULL,"3");

		$conditions = array('cityid' => 2,'ispass' => 1);
	    $this->cityabb = "xm";

        //标题栏的所有游戏列表
        $this->activity = $s_activity->spPager($this->spArgs('page', 1), 5)->findAll($conditions,'id DESC',NULL,"20");
        $this->pager = $s_activity->spPager()->getPager();
        //dump($this->activity);
        if($this->activity == null)
		{
			$this -> display('activity.html');
		}
		else
		{
		    $this -> display('activity.html');
	    }
	}
	elseif($tag == 'hot' && $id == 'forum') // 最新
	{
    	$conditions = array('cityid' => 2 );
        $this->info = "xm";
	    $e_answers = spClass('answersinfo');
        $this->answers = $e_answers->spPager($this->spArgs('page', 1), 10)->findAll($conditions);

        $sceneryuser=array();
        foreach ($this->answers as $value)
		{
			$conditions = array( 'ispass' => 1,'id' => $value['linkid']);
            $sceneryinfo = spClass('sceneryinfo');
            $sceneryinfo = $sceneryinfo->find($conditions,"id DESC","id,title","0,10");
            $sceneryuser[] = array('id'=>$sceneryinfo['id'],'title'=>$sceneryinfo['title']);
		}
        $this->sceneryuser = $sceneryuser;
        $this->pager = $e_answers->spPager()->getPager();

        $showtext=array();
        $replies=array();
		foreach($this->answers as $value){ 
            // 回复数查询
			$conditions = array( 'types' => 'answers','linkid' => $value['id']);
			$e_user = spClass('replyinfo'); 
	        $e_result = $e_user->findAll($conditions); 
	        $replies[] = count($e_result);
            // 回复数查询
			$conditions = array( 'id' => $value['linkid']);
			if($this->info == 'lines')
			{
			    $s_scenery = spClass('linesinfo'); 
			}
			 else
			{
                $s_scenery = spClass('sceneryinfo');
			}
			$this->show = $s_scenery->find($conditions);
			$showtext[] = array('id'=>$this->show["id"],'title'=>$this->show["title"]);
		}

		$conditions = array( 'id' => $this->id);
		if($this->info == 'lines')
		{
		    $s_sceneryseo = spClass('linesinfo'); 
		}
		 else
		{
            $s_sceneryseo = spClass('sceneryinfo');
		}
		$this->showseo = $s_sceneryseo->find($conditions);

		$this->showtext = $showtext;
		$this->replies = $replies;
			
        if($this->answers == null)
		{
			import(APP_PATH.'/tpl/404.php');exit();
		}
		else
		{
		    $this -> display('answers.html');
	    }
	}
	elseif(!empty($id) && $tag == 'lines' || !empty($id) && $tag == 'day')
	{

		$conditions = array( 'id' => $id );
		$t_chain = spClass('chain'); 
		$t_show = $t_chain->find($conditions); 
		$this->keywordsname = $t_show['keywords'];
        $this->show = $t_show;
        $this->id = $id;
		$conditions = "ispass = 1 and tags like '%".$t_show['keywords']."%'";
		$user = spClass('linesinfo'); 
        $results = $user->spPager($this->spArgs('page', 1), 15)->findAll($conditions,'id DESC');
        $this->pager = $user->spPager()->getPager();
		//dump($results); 

		// 城市标题
		$conditions_city = array( 'citystate' => '厦门市' );
		$t_city = spClass('lib_cityinfo'); 
		$this->city = $t_city->find($conditions_city);
        
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
		$this->areaids = $areaids;
		//dump($this->areaids);

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
         
		$this->tags = $results;
		$this->cityabb = "xm";
		if($this->tags == null || $this->keywords  == null)
		{
			$this -> display('404.php');
		}
		else
		{
		    $this -> display('content/lineskeyword.html');
	    }
	}
	else
	{
        //点评
        $commentinfo = spClass('commentinfo');
        $this->commentinfo_result = $commentinfo->findSql("SELECT sceneryinfo.title, sceneryinfo.id AS sid,commentinfo.*  FROM  sceneryinfo , commentinfo WHERE sceneryinfo.id = commentinfo.linkid  order by commentinfo.id DESC LIMIT 0 , 3");
       	// 获取头像
        $userarray = array();
        foreach ($this->commentinfo_result as $value)
		{
		  	$conditions = array( 'uid' => $value['userid'] );
			$e_user = spClass('lib_user'); 
	        $e_result = $e_user->find($conditions);
	        $userarray[] = $e_result['avatar'];
		}
        $this->userarray = $userarray;
        // 获取头像
        //景点
        $conditions = array( 'ispass' => 1 );
        $sceneryinfo = spClass('sceneryinfo');
        $this->sceneryinfo_result=$sceneryinfo->findAll($conditions,"id DESC","id,title","0,6");
        //人气景点
        $this->sceneryinfo_result_clicknumber=$sceneryinfo->findAll($conditions,"clicknumber DESC",null,"0,7");
        //推荐线路
        $linesinfo = spClass('linesinfo');
        $tjlines=$linesinfo->findAll($conditions,"clicknumber DESC",null,"0,30");
        $n=0;
        foreach ($tjlines as $value) {
        	$conditions_city = array( 'id' => $value['cityid'] );
			$t_city = spClass('lib_cityinfo'); 
			$tjlines[$n]['cityid'] = $t_city->find($conditions_city);
			$n++;
        }
        $this->tjlines = $tjlines;

        //论坛
        $answersinfo =spClass('answersinfo');
        $this->answersinfo_result = $answersinfo->findSql("SELECT sceneryinfo.title, sceneryinfo.id AS sid,answersinfo.*  FROM  sceneryinfo , answersinfo WHERE sceneryinfo.id = answersinfo.linkid  order by answersinfo.id DESC  LIMIT 0 , 7");
        $sceneryuser=array();
        foreach ($this->answersinfo_result as $value)
		{
			$conditions = array( 'ispass' => 1,'id' => $value['linkid']);
            $sceneryinfo = spClass('sceneryinfo');
            $sceneryinfo = $sceneryinfo->find($conditions,"id DESC","title","0,10");
            $sceneryuser[] = $sceneryinfo['title'];
		}
        $this->sceneryuser = $sceneryuser;
        //旅游动态
        $syshistory = spClass('syshistory');
        $this->syshistory_result = $syshistory->findSql("SELECT syshistory . * , user.uname FROM syshistory, user WHERE syshistory.userid = user.uid  order by syshistory.id DESC  LIMIT 0 , 5");

        //游记
        $youjiinfo = spClass('youjiinfo');
        $this->youjiinfo_result_top=$youjiinfo->findAll(null,"id DESC","id,title,content,picurl","0,2");
        $this->youjiinfo_result=$youjiinfo->findAll(null,"id DESC","id,username,title,releasedate","0,8");
        $this->youjiphoto=$youjiinfo->findAll(null,"clicknumber DESC","id,username,picurl,title,releasedate","0,5");

        $s_activity = spClass('activityinfo'); 
		$this->activitytop = $s_activity->findAll(null,"clicknumber DESC",null,"0,3");

		$conditions = array( 'cityid' => 2);
		$s_bdlist = spClass('listbdinfo'); 
		$this->bdlist = $s_bdlist->findAll($conditions);

        // 幻灯片管理
		$slide_db = spClass('slide'); 
		$conditions = array( 'ispass' => 1 );
		$this->result_slide = $slide_db->findAll($conditions,"id DESC",NULL,"10"); // 查找
		$this->slidenum = $slide_db->findCount($conditions,"id DESC",NULL,"10"); // 查找

        $this->timebefore30 = strtotime(date('Y-m-d 00:00:00',strtotime("-30 day")));
        $this->timebefore7 = strtotime(date('Y-m-d 00:00:00',strtotime("-7 day")));
        $this->timebefore3 = strtotime(date('Y-m-d 00:00:00',strtotime("-3 day")));
		$this->timebefore = strtotime(date('Y-m-d 00:00:00',strtotime("-1 day")));
		$this->timecurrent = strtotime(date('Y-m-d 00:00:00'));
		$this->timeafter = strtotime(date("Y-m-d 0:0:0",strtotime("+1 day")));

		// 窗口信息
		$windisplay_db = spClass('windisplay'); 
		$conditions_periphery = array( 'ispass' => 1,'class' =>'periphery' );
		$this->result_peripherybig = $windisplay_db->findAll($conditions_periphery,"seq desc,id desc",NULL,"4"); // 查找
		$this->result_more = $windisplay_db->findAll($conditions_periphery,"seq desc,id desc",NULL,"4,10"); // 查找


		$sceneryinfo = spClass('sceneryinfo');

        // 周边旅游
		$co7day = "releasedate >=". $this->timebefore7 ." AND releasedate <= ".$this->timeafter." and provinceid = 1";
        $peripheryscenerybig = $sceneryinfo->findAll($co7day,"clicknumber DESC",null,"0,4");
        $n=0;
        foreach ($peripheryscenerybig as $value) {
        	$conditions = array('linkid' => $value['id'],'top'=>1 );
		    $picture = spClass('pictureinfo'); 
	        $coverpicture = $picture->find($conditions);
	        $peripheryscenerybig[$n]['picurl'] = $coverpicture;
	        $n++;
        }
        $this->peripheryscenerybig = $peripheryscenerybig;
        //dump($this->peripherybig);
        $peripherybigscenerymore = $sceneryinfo->findAll($co7day,"clicknumber DESC",null,"4,10");
        $this->peripherybigscenerymore = $peripherybigscenerymore;

        // 周末休闲
		$co30day = "releasedate >=". $this->timebefore30 ." AND releasedate <= ".$this->timeafter." AND cityid = 2";
        $weekscenerybig = $sceneryinfo->findAll($co30day,"clicknumber DESC",null,"0,4");
        $n=0;
        foreach ($weekscenerybig as $value) {
        	$conditions = array('linkid' => $value['id'],'top'=>1 );
		    $picture = spClass('pictureinfo'); 
	        $coverpicture = $picture->find($conditions);
	        $weekscenerybig[$n]['picurl'] = $coverpicture;
	        $n++;
        }
        $this->weekscenerybig = $weekscenerybig;
        $weekscenerymore = $sceneryinfo->findAll($co30day,"clicknumber DESC",null,"4,9");
        $this->weekscenerymore = $weekscenerymore;

        // 哪玩达人
		$top_user = spClass('lib_user'); 
	    $this->top_resultuser = $top_user->findAll(null,'integral DESC',NULL,"8"); 
	    $this->cityabb = "xm";

	    $this -> display('xiamen.html'); // 厦门首页
    }
?>