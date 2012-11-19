<?php
/*$ptn = "/([-_A-Z]+[,.!?]+)/";
$str = "CORNELIUS. Valdes, first let him know the words of art; ";
preg_match($ptn, $str, $matches);
print_r($matches);*/
?>

<?php 

	$insert = false;

	$fileName = "marlowe.txt";
	$handle = fopen($fileName, "r");
/*	
	MySQL_connect("localhost", "root", "letmein");
    $link = MySQL_select_db("test");

	if (!$link) {
		die('Not connected : ' . mysql_error());
	
	}	*/
	
	$con = mysql_connect("localhost","root","letmein");
	if (!$con)
  	{
  		die('Could not connect: ' . mysql_error());
  	}

	mysql_select_db("test", $con);

	
	$character ='';
	$body = '';
	$index = 0;
	
	
	while($text =  fgets($handle)) {
		//$text =  fgets($handle);//fread($handle, filesize($fileName));
		$text = trim($text);
		//echo $text.'<br/>';
		$ptn = "/(^[-_A-Z]+[.]+)/";
		preg_match($ptn, $text, $matches);
		//print_r(sizeof($matches));
		if(sizeof($matches)>0) {
			//echo '#### NEW BODY';
			$character = substr($matches[0], 0, -1);
			$text= str_replace($character.'.','',$text);
			$text = preg_replace('/\[[^\]]*\]/', '', $text);
			//$text= str_replace('\'',' ',$text);
			$text = addslashes($text);
			$body=$text;
		}
		else if(strlen($text)==0 && strlen($character)>0) {
			//echo '<--';
			echo '<br><br>'.$index.' '.$character.' :<br/> '.$body;
			//echo '-->';

			$query = "INSERT INTO dialogs (`character`, `body`, `author`, `index`, `type`)
				 VALUES ('$character','$body','Marlowe',$index,' ')";
			
			echo $query;
			
			if($insert) {			
				if (!mysql_query($query,$con))
				 {
					  die('Error: ' . mysql_error());
				  }
				echo "1 record added";
			}
			$body = '';
			$character = '';
			$index++;		
			

	
		}
		else if(strlen($character)>0) {
			//echo 'last else';
			$text = preg_replace('/\[[^\]]*\]/', '', $text);
		//	$text= str_replace('\'',' ',$text);
			$text = addslashes($text);

			$body=$body.' '.$text;
		}
		else {
			//echo '%%% ignore '.$text.' %%%';
		}
		
	}
	fclose($handle);
	//header("Content-Type: application/json");
	
	mysql_close($con);
	
	

	//mysql_query('SET CHARACTER SET utf8');	
		
		
	function hasNewLine($str) {
		$found = false;
		foreach (array("\r", "\n", "\r\n", "\n\r") as $token) {
			if (strpos($str, $token) !== false) {
				$found = true;
		        echo '[[break in]] '.$str;
				break;
			}
		}
		return $found;
	}
		
?>

