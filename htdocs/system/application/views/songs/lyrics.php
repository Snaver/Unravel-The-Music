<?php 
?>
<html>
  <head>


    <title>
      <?php echo $title; ?> 
    </title>
  </head>
  <body>
    <p>
    <?php
	//flash message if there is an error
	$flash=validation_errors();
	if (isset($flash) AND $flash!='')
	{?>
		<div id="flashMessage" style="display:none;">
			<?=$flash?>
		</div>
	<?php }
	//end flash message

    if(set_value('Lyrics' != '')){
		$value = set_value('Lyrics');
    } else {
		$value = "You must have permission to add lyrics";
    }
    
     
	echo('Add Lyrics for ' . $artist . '\'s song ' . $song);
    echo form_open('songs/lyrics/add/' . $songId, array('class' => 'form'));
    
    ?>
    <fieldset>
		<ol>

	<li>
		<label>Song Lyrics</label><?php

    $lyrics = array(
              'name'        => 'Lyrics',
              'id'          => 'Lyrics',
              'value'       =>  $value,
              'maxlength'   => '100',
              'size'        => '50',
              'style'       => 'width:50%',
            );
    echo form_textarea($lyrics);
    ?></li></ol></fieldset><br />
	<?php
    echo form_submit('submit lyrics', 'Submit Lyrics');
    echo form_close();
      
    ?>
    </p>
  </body>
</html>
