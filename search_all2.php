<?php

	include 'array_to_json.php';
	
//	header("Content-Type: application/json");
	
    MySQL_connect("localhost", "jm2_user", "jmLetmein00");
    $link = MySQL_select_db("jm2");
	$word = $_GET['keyword'];
	$author = $_GET['author'];
	$max = $_GET['max'] ? $_GET['max'] : 0;
	
	$authorClause = "";
	
	$MAX_SEARCH_RESULTS = 100;
	
	//echo $author;
	
	
	if($author!=null && strlen($author)>0) {
		$authorClause = " and author='$author' ";
	}
		


	if($word == null)
		$word = 'being';
	$id = $_GET['id'];
	if (!$link) {
		die('Not connected : ' . mysql_error());
	}
    $search_query = "SELECT id, title , body, author,
				MATCH (
				title, body
				)
				AGAINST (
				'$word'
				) AS score
				FROM articles
				where  INSTR(body, '$word')> 0 ".$authorClause." order by author";
	//echo $search_query;
	$body_query = "SELECT id, title , body from articles where id = $id";
	if($word !=null)	
		$query = $search_query;
	else if($id !=null)	
		$query = $body_query;

		mysql_query('SET CHARACTER SET utf8');
		$res = MySQL_query($query);
   $rows = array();
   
    $num_result= mysql_num_rows ( $res); 
    
    $ratio = $num_result>MAX_SEARCH_RESULTS ? $MAX_SEARCH_RESULTS/$num_result : 1;
    
    //echo $ratio;
    $doc_count = 0;
    
    $prevAuthor = '';
    
    while($r = mysql_fetch_assoc($res)) {
    
      	if($ratio<1) {
      	    $rand = rand()/getrandmax();
			if($rand>$ratio) {
		        continue;	
			}
		}
		
        // one text per author
        
		if($num_result>MAX_SEARCH_RESULTS && strcmp($r['author'],$prevAuthor)==0)  {
		    continue;
		}
		
	   $doc_count++;
	   
	   if($max!=0 && $doc_count>$max) {
	        break;
	   }

       //$rows[] = $r['title'];
       $id = $r['id'];
       $title = $r['title'];
       $score = $r['score'];
       $body = $r['body'];
       $author = $r['author'];
       
       $prevAuthor=$author;
       
    //   $words = explode(" ", $body);
    
    
    
        //$index = array_search($word, $words);
        //$string = $words[$index - 1]." ".$words[$index]." ".$words[$index + 1];
        $strings = getAllAppearences($word, $body,0);
        for($i = 0; $i < count($strings) ; $i++) {
            $row = array(id => $id, title => $title, author => $author, score => $score, sentence => $strings[$i]['sentence'], 
            index => $strings[$i]['index'],wordIndex => $strings[$i]['wordIndex']);
            $rows[] = $row;
        }
    
        //$rows[] = $strings;
    }
    
    //echo ' count '.$doc_count;

    $json = json_encode($rows);

    print $json;

?>


<?php
   function getAllAppearences($word, $body, $max) {
		$results = array();
		$secondWord = '';
		//$body = preg_replace('!\s+!', ' ', $body);  // making word indexes out of sync?
        if(strpos($word," ")>0) {
           // echo 'second';
           list($word,$secondWord) = split(" ",$word);
           // $secondWord = explode(" ", $word)[1];
           // $word = explode(" ", $word)[0];
        }
		$words = explode(" ", $body);
		
		$keys = array_keys( $words,$word);
		$startingPoint = 0;
		$endPoint = count($keys);
		
		if(count($keys)>50) {
		    $startingPoint = rand(0,count($keys)-10);
		    $endPoint = rand($startingPoint, count($keys));
		}
		
		
		for($keyCount = $startingPoint; $keyCount < $endPoint  ; $keyCount++) {
			$string = "";
			$hasSecondWord = false;
			$index = $keys[$keyCount];
			//echo $index.', ';
			//echo $words[$index].', ';
			for ($i = $index-10; $i < $index+10; $i++) {
			    if($i>0 && $words[$i]==$secondWord) {
		           $hasSecondWord=true;
		        }
				$string = $string.$words[$i]." ";
			}
			if(strlen($secondWord)==0 || $hasSecondWord==true) {
			   
			    $string = str_replace(array("\\r\\n", "\\r","\\n","\\"), " ", $string);
                //echo $string.'--<br>';
    			$results[] = array(index=> $keyCount, wordIndex=> $index, sentence=> '...'.$string.'...');
    		}
		}
	//	$string = $words[$index - 1]." ".$words[$index - 1]." ".$words[$index - 1]." ".$words[$index - 1]." ".
	//				$words[$index]." ".$words[$index + 1];
		
		
		return $results;
 
		
   
   }

?>
