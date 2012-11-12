<?php

	include 'array_to_json.php';
	
	header("Content-Type: application/json");
	
    MySQL_connect("localhost", "root", "letmein");
    $link = MySQL_select_db("jm");
	$word = $_GET['keyword'];
	$id = $_GET['id'];
	$wordIndex = $_GET['wordIndex'];

	if (!$link) {
		die('Not connected : ' . mysql_error());
	}
 /*   $search_query = "SELECT id, title ,
				MATCH (
				title, body
				)
				AGAINST (
				'$word'
				) AS score,
				CONCAT_WS(
				' ',
				TRIM(
					SUBSTRING_INDEX(
						SUBSTRING(body, 1, INSTR(body, '$word') - 1 ),
						' ',
						-10
					)
				),
				'$word',
				TRIM(
					SUBSTRING_INDEX(
						SUBSTRING(body, INSTR(body, '$word') + LENGTH('$word') ),
						' ',
						10
					)
				)) AS sentence
				FROM articles
				where  INSTR(body, '$word')> 0 order by score desc";*/
	$body_query = "SELECT id, title , body from articles where id = $id";
/*	if($word !=null)	
		$query = $search_query;*/
	if($id !=null)	
		$query = $body_query;

	mysql_query('SET CHARACTER SET utf8');
	$res = MySQL_query($query);
    $rows = array();
	while($r = mysql_fetch_assoc($res)) {
		if($wordIndex!=null) {
			// trim result
			$fullBody = $r['body'];
			$words = explode(" ", $fullBody);
			$wordCount = 0;
			$before = '';
			$after = '';
			$i = 1;
			$numberOfBreaks = 0;
			$currentWord = $words[$wordIndex-$i];
			while($numberOfBreaks<1 && $i<400) {
				if(hasNewLine( $currentWord)) {
					$firstBreak = strrpos($currentWord, "\n");
					$currentWord = substr($currentWord,$firstBreak);
					$numberOfBreaks++;
				}
				$before = $currentWord.' '.$before;
				$i++;
				$currentWord = $words[$wordIndex-$i];
				$wordCount++;

			}
			//$before = $currentWord.' '.$before;

			$i = 1;
			$currentWord = $words[$wordIndex+$i];
			$numberOfBreaks = 0;
			while($numberOfBreaks<1 && $i<400) {
			
				if(hasNewLine( $currentWord)) {
					$lastBreak = strrpos($currentWord, "\n",-1);
					$currentWord = substr($currentWord,0,$lastBreak);
					$numberOfBreaks++;
				}
				$after = $after.' '.$currentWord;
				$i++;
				$currentWord = $words[$wordIndex+$i];
				$wordCount++;

			}
			//$after = $after.' '.$currentWord;
			
	/*		for($i = 1; $i<20; $i++) {
				$currentWord = $words[$wordIndex-$i];
				//echo $currentWord;
				//echo 'b? '.hasNewLine($currentWord);
				
				if (hasNewLine( $words[$wordIndex-$i])) {
    				echo ' [[breakline]] ';
				}
				$before = $words[$wordIndex-$i].' '.$before;
				$after = $after.' '.$words[$wordIndex+$i];
			}*/
			

			$r['body'] = $before.$words[$wordIndex].$after;
			
		}
    	$rows[] = $r;
	}
	$json = json_encode($rows);//array_to_json($rows);

	print $json;
	
	
	
	function hasNewLine($str) {
    $found = false;
    foreach (array("\r", "\n", "\r\n", "\n\r") as $token) {
        if (strpos($str, $token) !== false) {
            $found = true;
     //       echo '[[break in]] '.$str;
            break;
        }
    }
    return $found;
}

?>
