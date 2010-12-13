<script src="http://www.unravelthemusic.com/assets/js/public/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="http://www.unravelthemusic.com/assets/js/public/jquery.markitup.pack.js"></script>
<script type="text/javascript" src="http://www.unravelthemusic.com/assets/js/public/set.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.unravelthemusic.com/assets/css/public/editor.css" />
<script type="text/javascript">
$(document).ready(function() {
$('#body').markItUp(mySettings);
$('#body').show();
});
</script>

<div class="important">
	<?=validation_errors()?>
</div>
<?php
		$title = array(
				'name'		=> 'title',
				'id'		=> 'title',
				'value'		=> set_value('title'),
			);
		$text = array(
				  'name'        => 'body',
				  'id'          => 'body',
				  'value'       => set_value('body'),
				  'cols'        => '30',
				  'rows'		=> '1',
				  'style'		=> 'display: none;'
				);	

		$summary = array(
				  'name'        => 'summary',
				  'id'          => 'summary',
				  'value'       => set_value('summary'),
				  'cols'        => '70',
				  'rows'		=> '10',
				);				
				
	$news = array(
				'name'			=> 'news',
				'id'			=> 'news',
				'checked'			=> set_value('news'),
				);		
	$categories = array(
			'1' => 'Announcement',
			'2' => 'Music News',
			'3' => 'Album Review',
			'4' => 'Editorial',
			'5' => 'Concert Write-Up'
		);
	$blog = array(
		'site' => 'Site Blog',
		'music' => 'Music Blog'
		);
						
    echo form_open('blog/add');
	
    ?>
	<fieldset>
		<legend>New Blog Post</legend>
		<ol>
			<li>
				<label>Title</label>
				<?=form_input($title)?>
			</li>
			<li>
				<br /><label>Body</label>
				<?=form_textarea($text)?>
			</li>
			<li>
				<br /><label>Summary - Should be the first 300-500 words of your post and should 
				end with a period.  Should contain no styling except &lt;br /&gt;&lt;br /&gt; 
				to make a new paragraph.</label><br />
				<?=form_textarea($summary)?>			
			</li>
			<li>
				<label>Category</label>
				<?=form_dropdown('category', $categories, '1')?>			
			</li>
			<li>
				<label>Blog Selection</label>
				<?=form_dropdown('blog', $blog, 'music')?>			
			</li>
			<li>
				<label>Site News</label>
				<?=form_checkbox($news)?>
			</li>
		</ol>
	
	<?php
    echo form_submit('submit', 'submit');
    echo form_close();
      
    ?>
    </fieldset>