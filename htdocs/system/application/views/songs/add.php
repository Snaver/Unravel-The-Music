<?php

	$song = array(
			'name' 		=> 'song',
			'id'		=> 'song',
			'value'		=> set_value('song'),
			'size' 		=> '30'
		);
    ?>
 	<div class="important">
		<?=validation_errors()?>
	</div>    
	<?php
	$form = array(
			'class' => 'form'
		);
    echo form_open('songs/add/' . $artist_seo_name . '/' . $album_seo_name, $form);

    ?>
    <h4>Add a song for <?=anchor('/artists/view/' . $artist_seo_name, $artist)?>'s album
    <?=anchor('/albums/view/' . $artist_seo_name . '/' . $album_seo_name, $album)?></h4>
    <fieldset>
		<ol>
			<li>
				<label>Name of the song</label>
				<?=form_input($song)?>
			</li>
		</ol>
	</fieldset>
	
	<?php
    echo form_submit('submit song', 'Submit New Song');
    echo form_close();
      
    ?>
