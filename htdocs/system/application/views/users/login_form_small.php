<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 50,
	'value' => set_value('username'),
	'style' => "border: 0;",
	'class' => "rightSearch"
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 50,
	'style' => "border: 0;",
	'class' => "rightSearch"
);

$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0'
);

$confirmation_code = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
	'style' => "border: 0;",
	'class' => "rightSearch"	
);

?>
<h1>Login to Unravel</h1>
<fieldset>
<?php echo form_open($this->uri->uri_string())?>

<?php echo $this->dx_auth->get_auth_error(); ?>
<ul>
	<li>
		<?php echo form_label('Username', $username['id']);?>
	
		<div class="inputBox"><?php echo form_input($username)?></div>
		
		<?php echo form_error($username['name']); ?>
	</li>

	<li>
		<?php echo form_label('Password', $password['id']);?>
	
		<div class="inputBox"><?php echo form_password($password)?></div>
    
		<?php echo form_error($password['name']); ?>
	</li>

<?php if ($show_captcha): ?>

	<li>
		Enter the code exactly as it appears. There are no zeroes.
		<?php echo $this->dx_auth->get_captcha_image(); ?>

		<?php echo form_label('Confirmation Code', $confirmation_code['id']);?>
	
		<div class="inputBox"><?php echo form_input($confirmation_code);?></div>
		<?php echo form_error($confirmation_code['name']); ?>
	</li>
	
<?php endif; ?>

	<li>
		 <?php echo form_label('Remember me', $remember['id']);?> 
		 <?php echo form_checkbox($remember);?>
	</li>

	<?php echo form_submit('login','Login');?>
</ul>	
	<br /><br />
		<?php echo anchor($this->dx_auth->forgot_password_uri, 'Forgot password');?> <br />
		<?php
			if ($this->dx_auth->allow_registration) {
				echo anchor($this->dx_auth->register_uri, 'Register');
			};
		?>


<?php echo form_close()?>
</fieldset>
