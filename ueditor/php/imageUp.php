<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: taoqili
     * Date: 12-7-18
     * Time: 上午10:42
     */
    session_start();
    header("Content-Type: text/html; charset=utf-8");
    error_reporting(E_ERROR | E_WARNING);
    include "Uploader.class.php";
    //上传图片框中的描述表单名称，
    $title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
    $path = htmlspecialchars($_POST['dir'], ENT_QUOTES);

    //上传配置
    $uppicdata = $_SESSION["quser"]["uid"];
    $config = array(
        //"savePath" => ($path == "1" ? "upload/" : "upload1/"),
        "savePath" => ($path == "1" ? $uppicdata."/" : "upload1/"),
        "maxSize" => 1000, //单位KB
        "allowFiles" => array(".gif", ".png", ".jpg", ".jpeg", ".bmp")
    );

    //生成上传实例对象并完成上传
    $up = new Uploader("upfile", $config);
    /**
     * 得到上传文件所对应的各个参数,数组结构
     * array(
     *     "originalName" => "",   //原始文件名
     *     "name" => "",           //新文件名
     *     "url" => "",            //返回的地址
     *     "size" => "",           //文件大小
     *     "type" => "" ,          //文件类型
     *     "state" => ""           //上传状态，上传成功时必须返回"SUCCESS"
     * )
     */
    $info = $up->getFileInfo();

    /**
     * 向浏览器返回数据json数据
     * {
     *   'url'      :'a.jpg',   //保存后的文件路径
     *   'title'    :'hello',   //文件描述，对图片来说在前端会添加到title属性上
     *   'original' :'b.jpg',   //原始文件名
     *   'state'    :'SUCCESS'  //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
     * }
     */
    include "image.php";
    require 'watemake.php';
    $image_size=getimagesize($info["url"]);
    $resizeimage = new resizeimage($info["url"], 273, 182, "0","min.");
    if($image_size[0] >= 580)
    {
      $resizeimage2 = new resizeimage($info["url"], "580", "500", "0",".");
    }
    if($image_size[0] >= 250)
    {
      $image = new watemake($info["url"],"","em10.gif",9,0000,1,65,62,0);
    }
    #$_img = new Image($info["url"]);//$_path为图片文件的路径
    echo "{'url':'" . $info["url"] . "','title':'" . $title . "','original':'" . $info["originalName"] . "','state':'" . $info["state"] . "'}";

