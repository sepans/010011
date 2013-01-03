


<?php 

require 'markov_core.php';

  $content = $_GET['content'];
  $order = $_GET['order'];

  
  if($order==null) {
  	$order = 5;
  }
  $length = 200;
  
  
  $markov_table = generate_markov_table($content, $order);
  $markov = generate_markov_text($length, $markov_table, $order);
  
  //$markov_split = split('\.',$markov); 
  echo $markov; 

    ?>
