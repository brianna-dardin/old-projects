 <?php 
$db = new mysqli('', '', '', 'thought_record');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

//Reusable function to clean user input
function clean($input) {
  $input = trim($input);
  $input = strip_tags($input);
  $input = htmlspecialchars($input);
  $input = addslashes($input);
  return $input;
}	
?> 