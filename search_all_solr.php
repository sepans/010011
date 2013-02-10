<?php

//	include 'array_to_json.php';
	
//	header("Content-Type: application/json");



  $url = 'http://localhost:8080/solr-example/core0/select?q=content%3Aphilosophy&sort=score+desc&fl=author%2Ctitle%2Cscore%2Ccontent&wt=json&indent=true&rows=10';//&debugQuery=true';
  $result = file_get_contents($url);
 
 // echo $result;
 
  $decoded = json_decode($result);
  
  echo '<br/>------<br/>';
  
  echo $decoded;
  
  echo $decoded->response;
	
	$word = $_GET['keyword'];
	$author = $_GET['author'];
	
	$authorClause = "";
	
	//echo $author;
	
	
	if($author!=null && strlen($author)>0) {
		$authorClause = " and author='$author' ";
	}
		


	if($word == null)
		$word = 'being';
	$id = $_GET['id'];
/*
    MySQL_connect("localhost", "root", "letmein");
    $link = MySQL_select_db("jm");

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
   
   
while($r = mysql_fetch_assoc($res)) {
   //$rows[] = $r['title'];
   $id = $r['id'];
   $title = $r['title'];
   $score = $r['score'];
   $body = $r['body'];
   $author = $r['author'];
   
//   $words = explode(" ", $body);



	//$index = array_search($word, $words);
	//$string = $words[$index - 1]." ".$words[$index]." ".$words[$index + 1];
	$strings = getAllAppearences($word, $body);
	for($i = 0; $i < count($strings) ; $i++) {
		$row = array(id => $id, title => $title, author => $author, score => $score, sentence => $strings[$i]['sentence'], 
		index => $strings[$i]['index'],wordIndex => $strings[$i]['wordIndex']);
		$rows[] = $row;
	}

    //$rows[] = $strings;
}
*/
//$json = json_encode($rows);

//print $json;

?>


<?php
   function getAllAppearences($word, $body) {
		$results = array();
		$secondWord = '';
        if(strpos($word," ")>0) {
           // echo 'second';
           list($word,$secondWord) = split(" ",$word);
           // $secondWord = explode(" ", $word)[1];
           // $word = explode(" ", $word)[0];
        }
		$words = explode(" ", $body);
		
		$keys = array_keys( $words,$word);
		for($keyCount = 0; $keyCount < count($keys) ; $keyCount++) {
			$string = "";
			$hasSecondWord = false;
			$index = $keys[$keyCount];
			for ($i = $index-10; $i < $index+10; $i++) {
			    if($i>0 && $words[$i]==$secondWord)
		           $hasSecondWord=true;
				$string = $string.$words[$i]." ";
			}
			if(strlen($secondWord)==0 || $hasSecondWord==true) {
    			$results[] = array(index=> $keyCount, wordIndex=> $index, sentence=> '...'.$string.'...');
    		}
		}
	//	$string = $words[$index - 1]." ".$words[$index - 1]." ".$words[$index - 1]." ".$words[$index - 1]." ".
	//				$words[$index]." ".$words[$index + 1];
		
		
		return $results;
 
		
   
   }

?>
