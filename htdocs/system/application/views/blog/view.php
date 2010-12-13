<link rel="alternate" type="application/rss+xml" title="RSS" href="http://feeds2.feedburner.com/UnravelTheMusic" />
<?php
$logo = array(
			'class' => 'blogLogo',
			'alt' => 'Unravel The Music Official Music Blog',
			'src' => 'assets/images/public/substance_logo.png'
		);
$rssButton = array(
			'class' => 'blogLinkButton',
			'alt' => 'RSS feed',
			'src' => 'assets/images/public/subscribe_rss.png'
		);
$emailButton = array(
			'class' => 'blogLinkButton',
			'alt' => 'Email link',
			'src' => 'assets/images/public/subscribe_email.png'
		);
echo(anchor('blog', img($logo)));
?>
<br />
<div class="blogLinks">
	<span class="blogLinksRight">
		Subscribe
		<div onMouseOver="showDiv('TW-Pop');return false;" onMouseOut="showDiv('TW-Pop');return false;" class="rssDiv">
		<ul id="TW-feed">
		<li><?=img($rssButton)?>
		<ul id="TW-Pop">
		<li><a href="http://fusion.google.com/add?feedurl=http://feeds2.feedburner.com/UnravelTheMusic"><img src="http://www.unravelthemusic.com/assets/images/public/googleButton.gif" alt="Google Reader or Homepage" border="0"></a>
		</li>
		<li><a href="http://add.my.yahoo.com/rss?url=http://feeds2.feedburner.com/UnravelTheMusic"><img src="http://www.unravelthemusic.com/assets/images/public/yahooButton.gif" border="0" alt="Add to My Yahoo!"></a>
		</li>
		<li><a href="http://www.bloglines.com/sub/http://feeds2.feedburner.com/UnravelTheMusic"><img src="http://www.unravelthemusic.com/assets/images/public/bloglinesButton.gif" alt="Subscribe with Bloglines" border="0" /></a>
		</li>
		<li><a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url=http://feeds2.feedburner.com/UnravelTheMusic"><img src="http://www.unravelthemusic.com/assets/images/public/newsgatorButton.gif" alt="Subscribe in NewsGator Online" border="0"></a> 
		</li>
		<li><a href="http://my.msn.com/addtomymsn.armx?id=rss&ut=http://feeds2.feedburner.com/UnravelTheMusic&ru=http://www.unravelthemusic.com/blog"><img src="http://www.unravelthemusic.com/assets/images/public/msnButton.gif" border="0"></a>
		</li>
		<li><a href="http://feeds.my.aol.com/add.jsp?url=http://feeds2.feedburner.com/UnravelTheMusic"><img src="http://www.unravelthemusic.com/assets/images/public/aolButton.gif" alt="Add to My AOL" border="0"/></a>
		</li>
		<li><a href="http://technorati.com/faves?add=http://www.unravelthemusic.com/blog"><img src="http://www.unravelthemusic.com/assets/images/public/technoratiButton.gif" alt="Add to Technorati Favorites!" border="0"/></a>
		</li>
		<li><a href="http://www.netvibes.com/subscribe.php?url=http://feeds2.feedburner.com/UnravelTheMusic"><img alt="Add to netvibes" src="http://www.unravelthemusic.com/assets/images/public/netvibesButton.gif" border="0"></a>
		</li>
		<li><a href="http://www.live.com/?add=http://feeds2.feedburner.com/UnravelTheMusic"><img style="width: 92px; height: 17px;" src="http://www.unravelthemusic.com/assets/images/public/liveButton.gif" border="0"></a>
		</li>
		<li><a href="http://feeds2.feedburner.com/UnravelTheMusic"><img src='http://feeds.feedburner.com/~fc/UnravelTheMusic?bg=FFFFFF&amp;fg=000000&amp;anim=0" height="26" width="88" style="border:0" alt=""' /></a>
		</li>
		</ul></li></ul></div>
		
		<?=anchor('blog/email', img($emailButton))?>
	</span>
	<span class="blogLinksLeft">
		<?=anchor('blog/archives/', 'Archives', array('class' => 'blogLink'))?> <span class="blogLinkDiv">|</span>
		<?=anchor('blog/category/', 'Categories', array('class' => 'blogLink'))?> <span class="blogLinkDiv">|</span>
		<?=anchor('blog/site/', 'Site News', array('class' => 'blogLink'))?>
	</span>
</div>
<hr class="blogHr" />
<h1 class="blogHeader"><?=$row->title?></h1><br />Posted On: <?=$row->created_on?>
<br /><br />
<?=$row->body?>
<br /><br />
<?
if($comments->num_rows() > 0)
{
	echo('<h3>Comments</h3><br /><br />');
	foreach($comments->result() as $comment)
	{
		?>
		<div class="blogPost">
			<span class="report">
			<?php
			echo($comment->author . ' | Created On: ' . $comment->created_on);
			?>
			</span>
			<?php
			echo('<h3>' . $comment->title . '</h3><br /><br />');
			echo($comment->body . '<br /><br /><br />');
			?>
		</div>
		<?php
	}



} else {
echo('No comments here, why don\'t you add one below.<br />');
}

	if($this->dx_auth->is_logged_in())
	{
	?>
		<div class="errorComment"></div>
		<div class="addComment">
			<?=anchor("#", "Add a comment about this blog post", array('class' => 'addCommentLink'))?>
		</div>
	

			<script src="http://www.unravelthemusic.com/assets/js/public/jquery.color.js" type="text/javascript"></script>
			<script src="http://www.unravelthemusic.com/assets/js/public/jquery.form.js" type="text/javascript"></script>
			<script type="text/javascript">
				var author = '<?php echo($this->session->userdata('DX_username'));?>';
				$(document).ready(function() {
					
					$('.addCommentLink').click(function() {
						var revert = $('.addComment').html();
						$('.addComment').html('<fieldset><form action="http://www.unravelthemusic.com/blog/addComment/<?=$row->post_id?>" method="post" class="addCommentForm"><legend>New Comment</legend><ol><li><label>Title</label><input type="text" name="title" value=""  /></li><li><label>Body</label><textarea name="body" cols="40" rows="7" id="body" maxlength="500" ></textarea></li></ol><input type="submit" name="submit" value="Submit New Comment"  /><input type="button" id="cancel" name="cancel" value="Cancel"  /></form></fieldset>');
						bindCancelComment(revert);
						return false; 
					});


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
								$('#zeroComment').after('<div class="authorInfo" style="background-color: #cee7cb;">' + json.author +
								"<div class='meaning_content' style='background-color: #cee7cb;'><h5>" + json.title + "</h5>" +
								"<br /><h8>Created On: " + json.createdOn +	"</h8><div class='meaning_content_body'>" + json.body + "</div>");
								$('.errorComment').text('');
								$('.addComment').html("<h3>Thank You for your comment!</h3><p>You can find your comment below</p>");
								$('.authorInfo, .meaning_content').animate({backgroundColor: 'white'}, 350 );
							}
										
						};				
					};				
				

			</script>
			<?php
	} else if(!$this->dx_auth->is_logged_in()){
		echo(anchor('/users/login', 'Login') . ' or ' . anchor('/users/register', 'Register') . ' to add your meaning or comment about this song.');
	}
	echo('<div id="zeroComment"></div>');
?>	
	   <script type="text/javascript">
function showDiv(objectID) {
var theElementStyle = document.getElementById(objectID);
if(theElementStyle.style.display == "block"){
theElementStyle.style.display = "none";
}else{
theElementStyle.style.display = "block";}
}
</script>