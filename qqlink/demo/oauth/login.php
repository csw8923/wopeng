<?php
    header("Content-type:text/html; charset=UTF-8;");
    session_start();
    include "../common/function.php";
    include "../common/config.php";
    //$sState = md5(date("YmdHis".getip()));
    //$_SESSION["state"] = $sState;
    $sUri =  "http://".$_SERVER["HTTP_HOST"].str_replace("/oauth/login.php", "/oauth/callback.php", $_SERVER["REQUEST_URI"]);
    $_SESSION["URI"] = $sUri;
    $aParam = array(
    	"response_type"    => "code",
    	"client_id"        =>    $aConfig["appid"],
    	"redirect_uri"    =>    $sUri,
    	"scope"            =>    "get_user_info",
    );
    $aGet = array();
    foreach($aParam as $key=>$val){
        $aGet[] = $key."=".urlencode($val);
    }
    $sUrl = "https://graph.qq.com/oauth2.0/authorize?";
    $sUrl .= join("&",$aGet);
    header("location:".$sUrl);