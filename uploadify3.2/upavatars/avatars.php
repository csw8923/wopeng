<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/uploadify3.2/upavatars/images'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	//$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'].'ddd';
	$filename = time().mt_rand().'.jpg';
	$targetFile = rtrim($targetPath,'/') . '/' . $filename;
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','JPG'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
        $image_size=getimagesize('images/'.$filename);
		  require 'image.php';
			echo $filename;
	} else {
		echo 'Invalid file type.';
	}
	
}
?>