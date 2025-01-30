<?php
$path = ''; 
if($_GET["id"] === "rps"){
	$path = 'files/t.pdf'; 	
}else if($_GET["id"] === "2"){
	$path = 'files/test.zip'; 
}	

$mime_type=mime_content_type($path); 

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: " . $mime_type);
header("Content-Length: " .(string)(filesize($path)) );
header('Content-Disposition: attachment; filename="'.basename($path).'"');
header("Content-Transfer-Encoding: binary\n");

readfile($path); 
exit();
?>