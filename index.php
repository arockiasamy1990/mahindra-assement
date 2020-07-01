<html>
<body>
<h2>Find Word</h2>
<style>
table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 15px;
}
</style>
<form action="index.php" method="POST">
  <input type="text" id="search" name="search"><br><br>
   <input type="submit" value="Submit">
</form> 
<?php if(isset($_POST) && !empty($_POST)) {
	#$file = 'http://www.gutenberg.org/files/2701/2701-0.txt';
	$file = '2701-0.txt';
	$contents = file_get_contents($file);
	$search_txt=$_POST['search'];
	$pattern = preg_quote($_POST['search'], '/');
	$pattern = "/^.*\b$pattern\b.*$/mi";
	$total_occurence=0;
	$ListArray=array();
	$lines = file($file);
	if(preg_match_all($pattern, $contents, $matches,PREG_OFFSET_CAPTURE)){
	   foreach($matches[0] as $matchedline) {
		  $matchedLineArr=explode(" ",$matchedline[0]);
		  list($before) = str_split($contents, $matchedline[1]);
		  $line_number = strlen($before) - strlen(str_replace("\n", "", $before)) + 1;
		  foreach($matchedLineArr as $key=>$matched) {
			  if(substr_count(strtoupper($matched),strtoupper($search_txt))) {
				  $previous='';
				  if(isset($matchedLineArr[$key-1])){
					$previous=$matchedLineArr[$key-1];
				  } 
				  $next='';
				  if(isset($matchedLineArr[$key+1])){
					$next=$matchedLineArr[$key+1];
				  }
				  $ListArray[]=array('line'=>$line_number,'previous'=>$previous,'next'=>$next);
				  break;
			  }
		  }
		  $total_occurence+=substr_count(strtoupper($matchedline[0]),strtoupper($search_txt));
		 
	   }
	} else{
	   echo "No matches found";
	}
	
} ?>
<h1><?php echo $search_txt; ?>:is Number of occurance: <?php echo $total_occurence;  ?></h1>

<table>
  <tr>
    <th>Line Number</th>
    <th>Previous Word</th>
    <th>Next Word</th>
  </tr>
  <?php 
     #print_r($ListArray);
	if(!empty($ListArray)) { 
	  foreach($ListArray as $data) {
  ?>
	<tr>
		<td><?php echo $data['line'] ?></td>
		<td><?php echo $data['previous'] ?></td>
		<td><?php echo $data['next'] ?></td>
	</tr>
  <?php 
		}
	}
  ?>
</table>

</body>
</html>