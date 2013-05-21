<?php
class watemake {
 public $source_image; //ԴͼƬ
 public $source_image_size; //ԴͼƬ ��С
 public $source_image_h; //ԴͼƬ��
 public $source_image_w; //ԴͼƬ��
 public $type; //ͼƬ����
 public $attr; //�����������ͼƬhtml ��ʽ�Ŀ��
 public $style; //ˮӡ��ʽ  ͼƬ��������  1Ϊ ˮӡ���� ��2ΪˮӡͼƬ
 public $watemake_text; //��������
 public $text_size;    //���ִ�С
 public $text_color;   //������ɫ
 public $textangle ;   //������б�Ƕ�
 public $wate_image; // ˮӡͼƬ
 public $distrion; //���� ˮӡ��ͼƬ�ϵ�λ��
 public $wate_w; //ˮӡ��
 public $wate_h; //ˮӡ��
 public $watertype; //ˮӡͼƬ����
 public $waterattr; //�������ˮӡͼƬ html ��ʽ�Ŀ��
 public $newimage; //php����Դ�ļ��½�����ͼƬ
 private $x1;
 private $y1;
 private $x2;
 private $y2;
 
 public function __construct($imagename = "",$watemake_text="",$wate_image = "", $distrion = "",$text_color="",$text_size="",$wate_w="",$wate_h="",$textangle="") {
  if ($imagename == "") {
   echo "ԴͼƬ��������ȷ";
   exit ();
  } else {
   $this->source_image = $imagename;
  }
  if(($watemake_text == "" && $wate_image == "")){
  echo "�����ò������󣬵ڶ����͵�����������������ͬʱΪ�ա��򶼲�Ϊ��";
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
    die("��֧�ִ˸�ʽ�ļ����ˮӡ");
    exit ();
  }
 }
 private function imagecopyimage() { //��ˮӡ
  switch ($this->distrion) {
   case 1 : //1Ϊ���˾���
    $this->x1 = 0;
    $this->y1 = 0;
    break;
   case 2 : //2Ϊ���˾���
    $this->x1 = ceil(($this->source_image_w - $this->wate_w) / 2);
    $this->y1 = 0;
    break;
   case 3 : //3Ϊ���˾���
    $this->x1 = ceil($this->source_image_w - $this->wate_w);
    $this->y1 = 0;
    break;
   case 4 : //4Ϊ�в�����
    $this->x1 = 0;
    $this->y1 = ceil(($this->source_image_h - $this->wate_h) / 2);
    break;
   case 5 : //5Ϊ�в�����
    $this->x1 = ceil(($this->source_image_w - $this->wate_w) / 2);
    $this->y1 = ceil(($this->source_image_h - $this->wate_h) / 2);
    break;
   case 6 : //6Ϊ�в�����
    $this->x1 = ceil($this->source_image_w - $this->wate_w);
    $this->y1 = ceil(($this->source_image_h - $this->wate_h) / 2);
    break;
   case 7 : //7Ϊ�ײ�����
    $this->x1 = 0;
    $this->y1 = ceil($this->source_image_h - $this->wate_h);
    break;
   case 8 : //8Ϊ�ײ�����
    $this->x1 = ceil(($this->source_image_w - $this->wate_w) / 2);
    $this->y1 = ceil($this->source_image_h - $this->wate_h);
    break;
   case 9 : //9Ϊ�ײ�����
    $this->x1 = ceil($this->source_image_w - $this->wate_w)-25;
    $this->y1 = ceil($this->source_image_h - $this->wate_h)-25;
    break;
   default : //����Ϊ���
    $this->x1 = rand(0, ($this->source_image_w - $this->wate_w));
    $this->y1 = rand(0, ($this->source_image_h - $this->wate_h));
    break;
  }
  
  $white = ImageColorAllocate($this->newimage, 255,255,255);  // ������ɫ
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
      die("��֧�ִ˸�ʽ�ļ���ΪҪ��ӵ�ˮӡͼƬ");
      exit ();
    }
    imagecopymerge($this->newimage, $this->wate_image, $this->x1, $this->y1, 0, 0, $this->wate_w, $this->wate_h,100);
    imagejpeg($this->newimage, $this->source_image, 100);
    imagedestroy($this->wate_image);
    break;
  }
  //imagefilledrectangle($this->newimage, $this->x1, $this->y1, $this->x1 + $this->wate_w, $this->y1 + $this->wate_h, $this->color); //��ͼƬ��ָ��λ�ü�һ������Ϊ$color�ķ���
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
//ʹ��˵��  watemake
//��һ������ Ϊ Ҫ��ˮӡ��ͼƬ  ��ѡ����
//�ڶ�������Ϊ Ҫ��ͼƬ��ӵ����� ���ѡ�
//������ΪˮӡͼƬ�ĵ�ַ  ���ѡ�
//���ĸ�����Ϊ��ͼƬ��������ֻ���ͼƬ��λ�ã��粻��Ϊ��  ѡ��
//���������Ϊ������ɫ  ѡ�� --- �˲������� 0000
//����������Ϊ�����С��  ��1-5��ѡ��
//���߸���������ˮӡ�Ŀ��  ѡ��
//�ڰ˸���������ˮӡ�ĸ߶�  ѡ��
//�ھŸ�����Ϊ��б�Ƕ�ֻ���ı�ˮӡ������
//$image = new watemake('uploads/'.$filename,"","em10.gif",9,0000,1,120,115,0);
?>