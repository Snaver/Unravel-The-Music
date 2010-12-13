<script type="text/javascript" src="http://www.unravelthemusic.com/assets/js/public/jquery.js"></script>
<script type="text/javascript" src="http://www.unravelthemusic.com/assets/js/public/jquery.markitup.pack.js"></script>
<script type="text/javascript" src="http://www.unravelthemusic.com/assets/js/public/set.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.unravelthemusic.com/assets/css/public/editor.css" />
<script type="text/javascript">
$(document).ready(function() {
$('#body').markItUp(mySettings);
$('#body').show();
});
</script>

<?php
$user = $userQuery->row();
if($user->avatar != '')
{
	$img = 'http://static.unravelthemusic.com/users/' . $user->avatar;
} else {
	$img = 'http://www.unravelthemusic.com/assets/images/public/blank_user.png';
}
	$cover = base_url() . 'assets/images/public/cover.png';
	$userImg = array(
			'src' => $cover,
			'class' => 'artistPic',
			'style' => 'background:url(' . $img . ') no-repeat; background-position: center center',
			'alt' => $user->username,
			'align' => 'right'
		);
	$profileButton = array(
			'src' => 'assets/',
			'class' => 'journalButton',
			'alt' => 'Profile'
			);
?>

<?=img($userImg)?>
<h1><?=$user->username?></h1>
<table class="journalInfo">
	<tr>
		<td class="leftJournal">
			<?=$this->usermodel_unravel->loadTitle($user->karma)?>
		</td>
		<td class="rightJournal">
			Age: <?php
			if(isset($user->birthday))
			{
				echo(floor((time() - strtotime($user->birthday)) / (60 * 60 * 24 * 365)));
			}
			?>
		</td>
	</tr>
	<tr>
		<td class="leftJournal">
			Points: <?=$user->karma?>
		</td>
		<td class="rightJournal">
			Location: <?=$user->location?>
		</td>
	</tr>
	<tr>
		<td class="leftJournal">
			Join Date: <?=date("F d, Y", strtotime($user->created))?>
		</td>
		<td class="rightJournal">
			Name: <?=$user->name?>
		</td>
	</tr>
</table>
<hr class="journalHr" />
<div class="subNav">
<div class="profileButton">
<?=anchor('users/view/' . $user->username, 'Profile', array('class' => 'buttonLink'))?>
</div>
<div class="journalButton">
<?=anchor('journals/view/' . $user->username, 'Journals', array('class' => 'buttonLink'))?>
</div>
</div>
<br /><br />
<div>
<?php

	if($user->username == $this->session->userdata('DX_username') && $hideInput == false)
	{
		$attributes = array(
						'class' => 'form', 
						'id' => 'journalForm'
					);

		$title = array(
					  'name'        => 'title',
					  'id'          => 'title',
					  'value'       => '',
					  'maxlength'   => '100',
					  'style'       => 'width:95%',
					);		
		$text = array(
				  'name'        => 'body',
				  'id'          => 'body',
				  'value'       => '',
				  'cols'        => '30',
				  'rows'		=> '1',
				  'style'		=> 'display: none;'
				);					
		echo('<div><p class="error"></p></div>');
		
		$this->load->helper('form');
		
		echo('<div class="journalInput">' . form_open('journals/add', $attributes));
		echo('Title: <br />');
		echo(form_input($title));
		echo('<br />Body:');
		echo('<br />');
		echo(form_textarea($text));
		echo('<br />' . form_submit('submit', 'submit'));
		echo(form_close());
		echo('</div>');
	}
	echo('<div id="firstJournalMark"></div>');
	if($journalQuery->num_rows() > 0)
	{
		foreach($journalQuery->result() as $journal)
		{
			?>
			<div class="journalEntry">
			<?php
			echo('<h3>' . anchor('journals/view/' . $journal->user . '/' . $journal->journal_id, ucfirst($journal->title)) . '</h3><br />');
			echo('created on: ' . $journal->created_on . '<br />');
			
			if($commentNum == true)
			{
				echo("<div class='body'><p class='". $journal->journal_id . "'>" . substr($journal->body, 0, 500) . anchor('journals/view/' . $journal->user . '/' . $journal->journal_id, 'Read more') . "</p></div>");
				echo("Total Comments: " . $journal->totalComments);
			} else {
				echo("<div class='body'><p class='". $journal->journal_id . "'>" . $journal->body . "</p></div>");
			}
			?>
			</div>
		
			<?php
		}
	} else {
		echo('this user hasn\'t posted any journal entries yet');
	}
	if($comments != null)
	{
		if($comments->num_rows() > 0)
		{
			echo('<h3>Comments</h3><br />');
			foreach($comments->result() as $comment)
			{
				echo($comment->title . '<br />');
				echo('Created by: ' . ucfirst($comment->author) . '<br /><br />');
				echo($comment->body . '<br /><br /><br />');
			}
		
		}
	}
?>
</div>
<?php
	if($this->dx_auth->is_logged_in() && $journalQuery->num_rows() == 1)
	{
	?>
		<div class="errorComment"></div>
		<div class="addComment">
			<?=anchor("#", "Add a comment about this journal entry", array('class' => 'addCommentLink'))?>
		</div>
		<?php
	} else if(!$this->dx_auth->is_logged_in()){
		echo(anchor('/users/login', 'Login') . ' or ' . anchor('/users/register', 'Register') . ' to add your meaning or comment about this journal entry.');
	}
	echo('<div id="zeroComment"></div>');
?>
<script src="http://www.unravelthemusic.com/assets/js/public/jquery.color.js" type="text/javascript"></script>
<script src="http://www.unravelthemusic.com/assets/js/public/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript">
	var author = '<?php echo($user->username);?>';
	$(document).ready(function() {
		
		$('#journalForm').ajaxForm({ 
			dataType:  'json', 
			success:   process,
		});

		function process(json) { 

			if(json.result == 'fail')
			{
				$('.error').text(json.message);
			} else {
				$('.journalInput').hide();
				$('#firstJournalMark').after("<div class='journalEntry' style='background-color: #cee7cb;'><h3><a href='/journals/view/" + json.user +"/" + json.id + "/'>" + json.title + '</a></h3><br />created on: ' + json.createdOn + "<br /><div class='body'>" + json.body +  '</p></div></div><br />')
				$('.journalEntry').animate({backgroundColor: 'white'}, 350 );

				$('.error').text('');
			}
			
		}
		<?
		if(isset($journal->journal_id))
		{
		?>
		$('.addCommentLink').click(function() {
			var revert = $('.addComment').html();
			$('.addComment').html('<fieldset><form action="http://www.unravelthemusic.com/journals/addComment/<?=$journal->journal_id?>" method="post" class="addCommentForm"><legend>New Comment</legend><ol><li><label>Title</label><input type="text" name="title" value=""  /></li><li><label>Body</label><textarea name="body" cols="40" rows="7" id="body" maxlength="500" ></textarea></li></ol><input type="submit" name="submit" value="Submit New Comment"  /><input type="button" id="cancel" name="cancel" value="Cancel"  /></form></fieldset>');
			bindCancelComment(revert);
			return false; 
		});
		<?php
		}
		?>

	});

		var bindCancelComment = function(revert) {
			$('#cancel').click(function() {
				$('.addComment').html(revert);
			});
			$('.addCommentForm').ajaxForm({ 
				dataType:  'json', 
				success:   processComment
			});
			

			function processComment(json) { 

				if(json.result == 'fail')
				{

					$('.errorComment').text(json.message);
				} else {
					$('#zeroComment').after('<div class="authorInfo" style="background-color: #cee7cb;">' + json.author + "<?php $points = $this->usermodel_unravel->getPoints($this->session->userdata('DX_username')); echo('<br />User Points: ' . $points);echo('<br />Title: ' . $this->usermodel_unravel->loadTitle($points));?>" +
					"<div class='meaning_content' style='background-color: #cee7cb;'><h5>" + json.title + "</h5>" +
					"<br /><h8>Created On: " + json.createdOn +	"</h8><div class='meaning_content_body'>" + json.body + "</div>");
					$('.errorComment').text('');
					$('.addComment').html("<h3>Thank You for your comment!</h3><p>You can find your comment below</p>");
					$('.authorInfo, .meaning_content').animate({backgroundColor: 'white'}, 350 );
				}
							
			};				
		};				
	

</script>