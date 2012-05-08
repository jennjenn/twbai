<?php
$todaysawesome = getAllTodaysAwesome();
?>
<ul id="todays-goal-list">
	<?php
foreach($todaysawesome as $awesome){
	?>	
	<li><?php echo $awesome['goal']; ?></li>
	<?php
}
?>
</ul>