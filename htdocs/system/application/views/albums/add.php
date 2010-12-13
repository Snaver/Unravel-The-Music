    <?php

	$form = array(
		'class' => 'form'
		);
    $year = array(
			'name' => 'year',
			'id' => 'year',
			'size' => '4',
			'value'=> set_value('year'),
		);
	$album = array(
			'name' => 'album',
			'id'   => 'album',
			'size' => '30',
			'value'=> set_value('album'),
		);
		
	  ?>
	  <h2>Add a new album for <?=$artist?></h2>
	  <p>Fill in the form below and hit submit to add a new album</p>
		<div class="important">
	  	<?php echo validation_errors(); ?>
		</div>
		<?=form_open('albums/add/' . $artist_seo_name, $form)?>
	<fieldset>
	
		<legend>Add an Album</legend>
		<ul>
			
			<li>
				<label>Album Name:</label>
				<?=form_input($album)?>
			</li>
			<li>
				<label>Release Date</label>
				<?=form_input($year)?>YYYY
			</li>
		</ul>
	</fieldset>
      			<?php
			echo form_submit('submit album', 'Submit');
			echo form_close();
			?>

