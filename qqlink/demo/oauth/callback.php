<?php
    header("Content-type:text/html; charset=UTF-8;");
    session_start();
    include_once("../common/config.php");
    include_once("../common/function.php");
	$sUrl = "https://graph.qq.com/oauth2.0/token";
	$aGetParam = array(
		"grant_type"    =>    "authorization_code",
		"client_id"        =>    $aConfig["appid"],
		"client_secret"    =>    $aConfig["appkey"],
		"code"            =>    $_GET["code"],
		"state"            =>    $_GET["state"],
		"redirect_uri"    =>    $_SESSION["URI"]
	);
	unset($_SESSION["state"]);
	unset($_SESSION["URI"]);
	$sContent = get($sUrl,$aGetParam);
	if($sContent!==FALSE){
		$aTemp = explode("&", $sContent);
		$aParam = array();
		foreach($aTemp as $val){
			$aTemp2 = explode("=", $val);
			$aParam[$aTemp2[0]] = $aTemp2[1];
		}
		$_SESSION["access_token"] = $aParam["access_token"];
		$sUrl = "https://graph.qq.com/oauth2.0/me";
		$aGetParam = array(
			"access_token"    => $aParam["access_token"]
		);
		$sContent = get($sUrl, $aGetParam);
		if($sContent!==FALSE){
			$aTemp = array();
			preg_match('/callback\(\s+(.*?)\s+\)/i', $sContent,$aTemp);
			$aResult = json_decode($aTemp[1],true);
			$_SESSION["openid"] = $aResult["openid"];
			//原始版本简化版本到此结束
			//get_user_info 拿过来
			$sUrl = "https://graph.qq.com/user/get_user_info";
			$aGetParam = array(
					"access_token" => $_SESSION["access_token"],
					"oauth_consumer_key"    =>    $aConfig["appid"],
					"openid"                =>    $_SESSION["openid"],
					"format"                =>    "json"
			);
			$sContent = get($sUrl,$aGetParam);
			if($sContent!==FALSE){
				$aResult = json_decode($sContent,true);
				if($aResult["ret"]==0){
					$_SESSION["qquser"] = $aResult;
					echo "<script>location.href='../bind.php';</script>";
					exit;
				}else{
					echo "<script>alert('".$aResult["msg"]."');location.href='../index.php';</script>";
					exit;
				}
			}else{
				echo "<script>alert('获取用户信息失败。');location.href='../index.php';</script>";
				exit;
			}
		}
	}
	echo "<script>alert('获取QQ信息失败。');location.href='../index.php';</script>";

?>