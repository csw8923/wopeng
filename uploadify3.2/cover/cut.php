<?php
error_reporting(7);
date_default_timezone_set("Asia/Shanghai");
header("Content-type:text/html; Charset=utf-8");
require_once("./image.class.php");

$images = new Images("file");

if ($_GET['act'] == 'cut'){	
	//$image = "files/0000.jpg";
	$src = explode("/",$_REQUEST["src"]);
	$image = "images/".$src[count($src)-1];
	$res = $images->thumb($image,false,1);
	if($res == false){
		echo "裁剪失败";
	}elseif(is_array($res)){
		//echo '<img src="'.$res['big'].'" style="margin:10px;">';
		//echo '<img src="'.$res['small'].'" style="margin:10px;">';
		//echo '<button onclick="updata();" id="updata" type="button">保存</button>';
		//echo '<input id="cancel" name="cancel" type="button" value="取消" />';
        
        echo '<script type="text/javascript">';
        //echo 'function updata()';
	    //echo '{';
			//echo 'alert("保存成功");';
        echo 'alert("上传成功!!");window.location.href="../../coveralbum.html?info=scenery&pid='.$_REQUEST["pid"].'"';
			//echo 'window.close()';
	    //echo '};';
		echo '</script>';
	}elseif(is_string($res)){
		echo '<img src="'.$res.'">';
	}
}elseif(isset($_GET['act']) && $_GET['act'] == "upload"){
	
	$path = $images->move_uploaded();
	$images->thumb($path,false,0);							//文件比规定的尺寸大则生成缩略图，小则保持原样
	if($path == false){
		$images->get_errMsg();
	}else{
		echo "上传成功！<a href='".$path."' target='_blank'>查看</a>";
	}
}
?>