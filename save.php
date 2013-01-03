
	

<?php

$METHOD_DESKTOP = 'desktop';
$METHOD_EMAIL = 'email';


$method = $_GET['method'];
$content = $_GET['content'];

if($content==null) {
	return;
}
if($method==null) {
	$method=$METHOD_DESKTOP;
}

if($method==$METHOD_DESKTOP) {
	header('Content-type: text/text');
	header("Content-Disposition: attachment;filename=010011.txt");
	
	// stream
	$f  =   fopen('php://output', 'a');
	fwrite($f, $content);
	
}
else if($method==$METHOD_EMAIL) {

	$to = $_GET['to'];
	if($to==null)
		return;
	$subject = "010011.net";

	$from = "library@010011.net";
	$headers = "From:" . $from;
	mail($to,$subject,$content,$headers);
	echo "Mail Sent.";

}

?>
