<h1>Advertise With Unravel</h1>
<?php
	$form = array(
		'class' => 'form'
		);
	$who = array(
				'width' => '80%',
				'value' => set_value('who'),
				'name'	=> 'who'
		);
	$email = array(
				'width' => '80%',
				'value' => set_value('email'),
				'name' => 'email'
		);
	$tellme = array(
				'rows'	=> '5',
				'cols'	=> '70',
				'name'	=> 'tellme',
				'value'	=> set_value('tellme')
		);
	$budget = array(
				'width' => '80%',
				'value' => set_value('budget'),
				'name' => 'budget'
		);
echo(form_open('advertising/submit/', $form));
?>
<?=validation_errors()?>
<fieldset>
	<ol>
		<li>
			<label>Who you are:</label>
			<?=form_input($who)?>
		</li>
		<li>
			<label>Email</label>
			<?=form_input($email)?>
		</li>
		<li>
			<label>Tell me about your company and advertisements</label>
			<?=form_textarea($tellme)?>
		</li>
		<li>
			<label>Budget</label>
			<?=form_input($budget)?>
		</li>
	</ol>
</fieldset>
<?=form_submit('submit', 'submit')?>
<?=form_close()?>