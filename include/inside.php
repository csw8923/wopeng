<?php
    function inside($target)
    {
      // 内链关键字替换
      $insidelinks = spClass('insidelinks');	
          $showinsidelinks = $insidelinks->findAll(null,"id desc");  // 分页
          $kwname = array();
          $kwurl = array();
          foreach ($showinsidelinks as $value) {
        $kwname[] = $value['name'];
        $kwurl[] = '<a target="_blank" href='.$value['url'].'>'.$value['name'].'</a>';
      }
      $target = str_replace($kwname,$kwurl,$target);
      // 内链关键字替换
      return $target;
    }
?>