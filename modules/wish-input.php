<form id='goal-create' method="POST" action="/api/addgoal.php">
	<div id="goal-input">
		<input type='hidden' name='uid' value="<?php echo $uid; ?>">
		<input type="text" id="goal" name="goal" maxlength='100' />
		<input type="submit" id="submit-goal" name="add_goal" value="let's do it" />
	</div>
</form>