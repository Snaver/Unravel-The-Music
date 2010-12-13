<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 20,
	'value' =>  set_value('username')
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 20,
	'value' => set_value('password')
);

$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'size'	=> 20,
	'value' => set_value('confirm_password')
);

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'maxlength'	=> 80,
	'size'	=> 20,
	'value'	=> set_value('email')
);

$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha'
);
?>
<p>To register, enter your information below</p>
<hr class="popupHr" />
<fieldset>
<?php echo form_open($this->uri->uri_string(), array('class' => 'form'))?>

<ul>
	<li><?php echo form_label('Username', $username['id'], array('class' => 'popup'));?>

		<?php echo form_input($username)?>
		<?php echo form_error($username['name']); ?>
	</li>

	<li><?php echo form_label('Password', $password['id'], array('class' => 'popup'));?>
	
		<?php echo form_password($password)?>
    <?php echo form_error($password['name']); ?>
	</li>

	<li><?php echo form_label('Confirm Password', $confirm_password['id'], array('class' => 'popup'));?>
	
		<?php echo form_password($confirm_password);?>
		<?php echo form_error($confirm_password['name']); ?>
	</li>

	<li><?php echo form_label('Email Address', $email['id'], array('class' => 'popup'));?>
	
		<?php echo form_input($email);?>
		<?php echo form_error($email['name']); ?>
	</li>
		
<?php if ($this->dx_auth->captcha_registration): ?>

	<li>
	
	<?php echo $this->dx_auth->get_captcha_image(); ?>

	</li>
	<li>
		
		
		<?php echo form_label('Are you human?', $captcha['id'], array('class' => 'popup'));?>
		<?php echo form_input($captcha);?>
		<?php echo form_error($captcha['name']); ?>
	</li>
	
<?php endif; ?>

	
	<?php echo form_submit('register','Register');?>
	
</ul>

<?php echo form_close()?>
</fieldset>
</body>
</html>