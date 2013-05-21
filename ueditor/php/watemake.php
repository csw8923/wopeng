<?php
class watemake {
 public $source_image; //源图片
 public $source_image_size; //源图片 大小
 public $source_image_h; //源图片高
 public $source_image_w; //源图片宽
 public $type; //图片类型
 public $attr; //用于输出生成图片html 格式的宽高
 public $style; //水印样式  图片或者文字  1为 水印文字 ，2为水印图片
 public $watemake_text; //文字内容
 public $text_size;    //文字大小
 public $text_color;   //文字颜色
 public $textangle ;   //字体倾斜角度
 public $wate_image; // 水印图片
 public $distrion; //方向 水印在图片上的位置
 public $wate_w; //水印宽
 public $wate_h; //水印高
 public $watertype; //水印图片类型
 public $waterattr; //用于输出水印图片 html 格式的宽高
 public $newimage; //php根据源文件新建立的图片
 private $x1;
 private $y1;
 private $x2;
 private $y2;
 
 public function __construct($imagename = "",$watemake_text="",$wate_image = "", $distrion = "",$text_color="",$text_size="",$wate_w="",$wate_h="",$textangle="") {
  if ($imagename == "") {
   echo "源图片参数不正确";
   exit ();
  } else {
   $this->source_image = $imagename;
  }
  if(($watemake_text == "" && $wate_image == "")){
  echo "您调用参数错误，第二个和第三个参数，不允许同时为空、或都不为空";
  exit();
  }
  if($watemake_text != ""){
   $this->style=1;
   $this->watemake_text=$watemake_text;
   $this->wate_w=$wate_w;
   $this->wate_h=$wate_h;
  }else{
   $this->style=2;
   $this->wate_image=$wate_image;
   $this->wate_w=$wate_w;
   $this->wate_h=$wate_h;
  }
  $this->distrion = $distrion;
  $this->text_color=$text_color;
  $this->text_size=$text_size;
  $this->textangle=$textangle;
  $this->createimage();
  $this->imagecopyimage();
  $this->repetedimage();
  $this->showimage();
 }
 private function createimage() {
  list ($this->source_image_w, $this->source_image_h, $this->type, $this->attr) = getimagesize($this->source_image);
  switch ($this->type) {
   case 1 :
    $this->newimage = imagecreatefromgif($this->source_image);
    break;
   case 2 :
    $this->newimage = imagecreatefromjpeg($this->source_image);
    break;
   case 3 :
    $this->newimage = imagecreatefrompng($this->source_image);
    break;
   case 6 :
    $this->newimage = imagecreatefromwbmp($this->source_image);
    break;
   default :
    die("不支持此格式文件添加水印");
    exit ();
  }
 }
 private function imagecopyimage() { //加水印
  switch ($this->distrion) {
   case 1 : //1为顶端居左
    $this->x1 = 0;
    $this->y1 = 0;
    break;
   case 2 : //2为顶端居中
    $this->x1 = ceil(($this->source_image_w - $this->wate_w) / 2);
    $this->y1 = 0;
    break;
   case 3 : //3为顶端居右
    $this->x1 = ceil($this->source_image_w - $this->wate_w);
    $this->y1 = 0;
    break;
   case 4 : //4为中部居左
    $this->x1 = 0;
    $this->y1 = ceil(($this->source_image_h - $this->wate_h) / 2);
    break;
   case 5 : //5为中部居中
    $this->x1 = ceil(($this->source_image_w - $this->wate_w) / 2);
    $this->y1 = ceil(($this->source_image_h - $this->wate_h) / 2);
    break;
   case 6 : //6为中部居右
    $this->x1 = ceil($this->source_image_w - $this->wate_w);
    $this->y1 = ceil(($this->source_image_h - $this->wate_h) / 2);
    break;
   case 7 : //7为底部居左
    $this->x1 = 0;
    $this->y1 = ceil($this->source_image_h - $this->wate_h);
    break;
   case 8 : //8为底部居中
    $this->x1 = ceil(($this->source_image_w - $this->wate_w) / 2);
    $this->y1 = ceil($this->source_image_h - $this->wate_h);
    break;
   case 9 : //9为底部居右
    $this->x1 = ceil($this->source_image_w - $this->wate_w)-25;
    $this->y1 = ceil($this->source_image_h - $this->wate_h)-25;
    break;
   default : //其他为随机
    $this->x1 = rand(0, ($this->source_image_w - $this->wate_w));
    $this->y1 = rand(0, ($this->source_image_h - $this->wate_h));
    break;
  }
  
  $white = ImageColorAllocate($this->newimage, 255,255,255);  // 设置颜色
  $text = iconv('gb2312','utf-8',$this->watemake_text);
  
  switch ($this->style) {
   case 1 :
    imagettftext($this->newimage,$this->text_size,$this->textangle,$this->x1,$this->y1,$white,"simhei.ttf",$text);
    break;
   case 2 :
    list ($this->wate_w, $this->wate_w, $this->watertype, $this->waterattr) = getimagesize($this->wate_image);
    switch ($this->watertype) {
     case 1 :
      $this->wate_image = imagecreatefromgif($this->wate_image);
      break;
     case 2 :
      $this->wate_image = imagecreatefromjpeg($this->wate_image);
      break;
     case 3 :
      $this->wate_image = imagecreatefrompng($this->wate_image);
      break;
     case 6 :
      $this->wate_image = imagecreatefromwbmp($this->wate_image);
      break;
     default :
      die("不支持此格式文件成为要添加的水印图片");
      exit ();
    }
    imagecopymerge($this->newimage, $this->wate_image, $this->x1, $this->y1, 0, 0, $this->wate_w, $this->wate_h,100);
    imagejpeg($this->newimage, $this->source_image, 100);
    imagedestroy($this->wate_image);
    break;
  }
  //imagefilledrectangle($this->newimage, $this->x1, $this->y1, $this->x1 + $this->wate_w, $this->y1 + $this->wate_h, $this->color); //在图片的指定位置加一个背景为$color的方框
 }
 private function repetedimage() {
  switch ($this->type) {
   case 1 :
    imagejpeg($this->newimage, $this->source_image);
    break;
   case 2 :
    imagejpeg($this->newimage, $this->source_image);
    break;
   case 3 :
    imagepng($this->newimage, $this->source_image);
    break;
   case 6 :
    imagewbmp($this->newimage, $this->source_image);
    break;
  }
 }
 public function showimage() {
  //echo "<img src=\"" . $this->source_image . "\" " . $this->attr . " />";
 }
 public function __destruct() {
  imagedestroy($this->newimage);
 }
}
?>
<?php
//使用说明  watemake
//第一个参数 为 要加水印的图片  必选参数
//第二个参数为 要给图片添加的文字 必填（选填）
//第三个为水印图片的地址  必填（选填）
//第四个参数为在图片上添加文字或者图片的位置，如不填为随  选填
//第五个参数为文字颜色  选填 --- 此参数报废 0000
//第六个参数为字体大小。  （1-5）选填
//第七个参数设置水印的宽度  选填
//第八个参数设置水印的高度  选填
//第九个参数为倾斜角度只对文本水印起作用
//$image = new watemake('uploads/'.$filename,"","em10.gif",9,0000,1,120,115,0);
?>