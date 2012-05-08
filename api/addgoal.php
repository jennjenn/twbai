<?php
require_once('../initial.php');
header("Content-Type: application/json");

print_r($_POST);

$goal = mysql_real_escape_string($_POST['goal']);

if(empty($goal){
	?>
	{"status":"empty goal"}
	<?php
}else{
	?>
	{"status":"<?php echo $goal; ?>"}
	<?php
}
?>