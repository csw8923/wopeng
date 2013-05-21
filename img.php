<?php
$url = $_REQUEST["url"];
$urlarray = parse_url($url);
$path = str_replace("/","",$urlarray['path']);
$tbwidth = $_REQUEST["tbwidth"];
$tbheight = $_REQUEST["tbheight"];

if(file_exists('catchimg/'.$path) == 0)
{
  $im = imagecreatefromjpeg($url);
  header("Content-type:image/jpg");
  $oldwidth = imagesx($im);
  $oldheight = imagesy($im);
  $newimg = imagecreatetruecolor($tbwidth,$tbheight);
  //改变后的图象的比例
  $resize_ratio = ($tbwidth/$tbheight);
  //实际图象的比例
  $ratio = ($oldwidth)/($oldheight);
  if($ratio>=$resize_ratio)
  //高度优先
  {
     imagecopyresampled($newimg,$im, 0, 0, $oldwidth*0.15, 0, $tbwidth,$tbheight,$oldheight*$resize_ratio,$oldheight);
  }
  if($ratio<$resize_ratio)
  //宽度优先
  {
     imagecopyresampled($newimg,$im, 0, 0, 0, $oldheight*0.15, $tbwidth,$tbheight,$oldwidth*$resize_ratio,$oldwidth);
  }
  ImageJpeg($newimg,'catchimg/'.$path);
  ImageJpeg($newimg);
  imagedestroy($newimg);
}
else
{

  $im = imagecreatefromjpeg('http://www.xmoline.com/'.'catchimg/'.$path);
  header("Content-type:image/jpg");
  ImageJpeg($im);
  imagedestroy($im);
}
?>