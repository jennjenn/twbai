<?php
$todaysawesome = getAllTodaysAwesome();
?>
<ul id="todays-goal-list">
	<?php
foreach($todaysawesome as $awesome){
	?>	
	<li><a href="/if/<?php echo $awesome['ugid']; ?>"><?php echo $awesome['goal']; ?></a></li>
	<?php
}
?>
</ul>