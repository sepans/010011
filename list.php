<?php

	include 'array_to_json.php';
	
//	header("Content-Type: application/json");
	
    MySQL_connect("localhost", "jm2_user", "jmLetmein00");
    $link = MySQL_select_db("jm2");
	$word = $_GET['keyword'];
	if($word == null)
		$word = 'being';
	$id = $_GET['id'];
	if (!$link) {
		die('Not connected : ' . mysql_error());
	}
    $search_query = "SELECT id, title , author FROM articles order by author";
	

	$res = MySQL_query($search_query);
    $rows = array();
   
   
while($r = mysql_fetch_assoc($res)) {
   //$rows[] = $r['title'];
 /*  $id = $r['id'];
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

    //$rows[] = $strings;*/
   // echo $r['author'].' ';
    $rows[] = $r;
}

$json = json_encode($rows);

print $json;

?>

