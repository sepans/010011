<?php
    MySQL_connect("sepans.com", "sepan0_chrsmnn", "letmein11");
    $link = MySQL_select_db("sepan0_chrsmnn");
	$word = "borges";
	if (!$link) {
		die('Not connected : ' . mysql_error());
	}
    $query = "SELECT id, title , body,
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
where  INSTR(body, '$word')> 0 order by score desc";
    $res = MySQL_query($query);
?>
<table>
<tr><td>SCORE</td><td>TITLE</td><td>ID#</td><td>sentence</td><td>body</td></tr>
<?php
        if($res!=null)
		{
		while($row = MySQL_fetch_array($res)) {
           echo "<tr><td>{$row['score']}</td>";
            echo "<td>{$row['title']}</td>";
            echo "<td>{$row['id']}</td>"; 
            echo "<td>{$row['sentence']}</td>"; 
            echo "<td>{$row['body']}</td></tr>"; 
		}
        echo "</table>";
		}
?>