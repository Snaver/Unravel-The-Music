    <?php


    $hidden = array('final' => 'false');
	$attributes = array('class' => 'form');
    echo form_open('artists/add', $attributes, $hidden);
	$artist = array(
				  'name'        => 'artist',
				  'id'          => 'artist',
				  'value'       => 	set_value('artist'),
				  'maxlength'   => '80',
				  'size'        => '30',
				);
	$alternate = array(
				  'name'        => 'alternate',
				  'id'          => 'alternate',
				  'value'       => 	set_value('alternate'),
				  'maxlength'   => '80',
				  'size'        => '30',
				);
	$value = 'http://www.';
	if(set_value('website'))
	{
		$value = set_value('website');
	}
	$wikiValue = 'http://en.wikipedia.org/wiki/';
	if(set_value('website'))
	{
		$wikiValue = set_value('website');
	}	
	$website = array(
				  'name'        => 'website',
				  'id'          => 'website',
				  'value'       => $value,
				  'maxlength'   => '100',
				  'size'        => '35',
				);
	$wiki = array(
				  'name'        => 'wikiPage',
				  'id'          => 'wikiPage',
				  'value'       => $wikiValue,
				  'maxlength'   => '100',
				  'size'        => '35',
				);				
	$notify = array(
					'name'        => 'notify',
					'id'          => 'notify',
					'checked'     => $checked,
    );
	?>
	<div class="important">
		<?=validation_errors()?>
	</div>
	<fieldset>
		<legend>Submit an Artist</legend>
		<ul>
			<li>
				<label>Enter an Artist</label>
				<?=form_input($artist)?>
			</li>
			<li>
				<label>Alternate Spellings</label>
				<?=form_input($alternate)?>
			<li>
				<label>Artist Website</label>
				<?=form_input($website)?>
			</li>
			<li>
				<label>Notify when approved</label>
				<?=form_checkbox($notify)?>
		</ul>
	</fieldset>
	<?php
    echo form_submit('submit artist', 'Submit');
    echo form_close();    
    ?>

