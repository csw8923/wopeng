<?php
	header("Content-type:text/html;charset=UTF-8;");
	session_start();
	unset($_SESSION["user"]);
	die("<script>alert('用户退出成功');location.href='index.php';</script>");