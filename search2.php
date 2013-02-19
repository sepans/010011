<?php

	include 'array_to_json.php';
	
	header("Content-Type: application/json");
	
    MySQL_connect("localhost", "jm2_user", "jmLetmein00");
    $link = MySQL_select_db("jm2");
	$word = $_GET['keyword'];
	$id = $_GET['id'];
	$wordIndex = $_GET['wordIndex'];
	$searchTerm = $_GET['searchTerm'];

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
	$body_query = "SELECT id, title ,author, body from articles where id = $id";
/*	if($word !=null)	
		$query = $search_query;*/
	if($id !=null)	
		$query = $body_query;

	mysql_query('SET CHARACTER SET utf8');
	$res = MySQL_query($query);
    $rows = array();
	while($r = mysql_fetch_assoc($res)) {
		if($wordIndex!=null && $wordIndex!='undefined' && $wordIndex!=-1) {
			// trim result
			$fullBody = $r['body'];
			//$fullBody = preg_replace('!\s+!', ' ', $fullBody);  // making word indexes out of sync?
			$words = explode(" ", $fullBody);
			$wordCount = 0;
			$before = '';
			$after = '';
			$i = 1;
			$numberOfBreaks = 0;
			$currentWord = $words[$wordIndex-$i];
			while($numberOfBreaks<1 && $i<200) {
				if(hasNewLine( $currentWord)) {
					$firstBreak = strrpos($currentWord, "\\n");
					$currentWord = substr($currentWord,$firstBreak);
					if($i>20) {   // to make sure there are at least 40 words
    					$numberOfBreaks++;
    				}
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
			while($numberOfBreaks<1 && $i<100) {
			
				if(hasNewLine( $currentWord)) {
					$lastBreak = strrpos($currentWord, "\\n",-1);
					$currentWord = substr($currentWord,0,$lastBreak);
					if($i>10) {   // to make sure there are at least 40 words
    					$numberOfBreaks++;
    				}
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
			
            $section = $before.$words[$wordIndex].$after;
            $section = str_replace(array("\\r\\n", "\\r","\\n","\\","\n","\r"), " ", $section);

			$r['body'] = $section;
			
		}
		else {
            $section = $r['body'];

            $text_length = strLen($section);
            
            $new_length = $text_length>2000 ? round($text_length*0.1) : $text_length;
          //  echo $text_length.' ';
          //  echo $new_length.' ';
            
            $start_point = round(rand(1000, $text_length));
         //   echo $start_point.' ';
            
            $section = substr($section,$start_point,$start_point+$new_length);

            $section = substr($section,strpos($section,". ")+2);
            

            $section = str_replace(array("\\r\\n", "\\r","\\n","\\","\n","\r"), " ", $section);

			$r['body'] = $section;
		
		
		}

    	$rows[] = $r;
	}
	$json = json_encode($rows);//array_to_json($rows);

	print $json;
	
	
	
	function hasNewLine($str) {
    $found = false;
    foreach (array("\\r", "\\n", "\\r\\n", "\\n\\r") as $token) {
        if (strpos($str, $token) !== false) {
            $found = true;
            break;
        }
    }
    return $found;
}

?>
