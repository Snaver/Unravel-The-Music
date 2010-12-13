Edit Profile
<?php
	$name = array(
				  'name'        => 'name',
				  'id'          => 'name',
				  'value'       => 	set_value('name', $name),
				  'maxlength'   => '80',
				  'size'        => '30',
				);
	$month = array(
				  'name'        => 'month',
				  'id'          => 'month',
				  'value'       => 	set_value('month', $month),
				  'maxlength'   => '2',
				  'size'        => '2',
				);
	$day = array(
				  'name'        => 'day',
				  'id'          => 'day',
				  'value'       => 	set_value('day', $day),
				  'maxlength'   => '2',
				  'size'        => '2',
				);
	$year = array(
				  'name'        => 'year',
				  'id'          => 'year',
				  'value'       => 	set_value('year', $year),
				  'maxlength'   => '4',
				  'size'        => '3',
				);
	$location = array(
				  'name'        => 'location',
				  'id'          => 'location',
				  'value'       => 	set_value('location', $location),
				  'maxlength'   => '80',
				  'size'        => '30',
				);
	$website = array(
				  'name'        => 'website',
				  'id'          => 'website',
				  'value'       => 	set_value('website', $website),
				  'maxlength'   => '80',
				  'size'        => '30',
				);
				
	$interests = array(
				  'name'        => 'interests',
				  'id'          => 'interests',
				  'value'       => 	set_value('interests', $interests),
				  'maxlength'   => '80',
				  'size'        => '30',
				);						
	$notify = array(
				'name'			=> 'notify',
				'id'			=> 'notify',
				'checked'			=> set_value('notify', $notify),
				);
	$cover = base_url() . 'assets/images/public/cover.png';
	$userImg = array(
			'src' => $cover,
			'style' => 'background:url(' . $img . ') no-repeat',
			'alt' => $username
		);					
	$attributes = array('class' => 'form');
	echo validation_errors();
	if(isset($error))
	{
		echo($error);
	}
    echo form_open_multipart('users/edit/' . $this->session->userdata('DX_username'), $attributes);
	?>
	<fieldset>
		<legend>Edit Your profile</legend>
		<ol>
			<li>
				<label>Name: </label>
				<?=form_input($name)?>
			</li>
			<li>
				<label>Birthday: </label>
				<?=form_input($month)?><?=form_input($day)?><?=form_input($year)?>
			</li>
			<li>
				<label>Location: </label>
				<?=form_input($location)?>
			</li>
			<li>
				<label>Website: </label>
				<?=form_input($website)?>
			</li>
			<li>
				<label>Interests: </label>
				<?=form_input($interests)?>			
			</li>
			<li>
				<label>Points: </label>
				<?=$points?>
			</li>
			<?php
			if($points >= 50)
			{
			?>
			<li>
				<label>Current Avatar:</label>
				<?=img($userImg)?><br />
				<input type="file" name="userfile" size="20" />			
			</li>
			<?php
			}
			?>
			<li>
				<label>Notify by default</label>
				<?=form_checkbox($notify)?>
			</li>
		</ol>
	</fieldset>
	<?php
	echo form_submit('submit', 'submit');
	echo form_close();
?>