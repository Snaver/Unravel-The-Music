
				
<?php


$attributes = array('class' => 'form');

echo form_open('lyrics/edit/' . $songId, $attributes);
$lyricsField = array(
			  'name'        => 'lyrics',
			  'id'          => 'lyrics',
			  'value'       => 	set_value('lyrics', $lyrics),
			  'cols'   => '80',
			  'rows'        => '29',
			);	

		?>	
	<div class="important">
		<?=validation_errors()?>
	</div>
	<fieldset>
		<legend>Edit Lyrics</legend>
		<ul>
			<li>
				<label>Lyrics</label>		
				<?=form_textarea($lyricsField)?>
			</li>

		</ul>
	</fieldset>
<?php
    echo form_submit('submit', 'submit');
    echo form_close();    
?>