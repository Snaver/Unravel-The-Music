<script src="http://www.unravelthemusic.com/assets/js/public/jquery.form.min.js" type="text/javascript"></script>
<?php 
$meaningsIds = array();
$row = $query->row();
//$lyricsRow = $lyrics->row();
$javascript[] = '';
$spam = array(
		'src' => 'assets/images/public/spam.png',
		'alt' => 'Spam',
		'class' => 'spam',
		'title' => 'Mark This Song as Spam',
		'border' => 0
	);
$duplicate = array(
		'src' => 'assets/images/public/dupe.png',
		'alt' => 'Duplicate',
		'class' => 'duplicate',
		'title' => 'Mark This Song as a Duplicate',
		'border' => 0
    );   
//images for topMeanings and meangings
$voteup = array(
		'src' => 'assets/images/public/up_vote.png',
		'alt' => 'Vote Up',
		'class' => 'voting',
		'title' => 'Vote Up',
		'border' => 0
	);
$votedown = array(
		'src' => 'assets/images/public/down_vote.png',
		'alt' => 'Vote Down',
		'class' => 'voting',
		'title' => 'Vote Down',
		'border' => 0
	);
$permLinkDiv = array(
		'src' => 'assets/images/public/divider_blue.png',
		'alt' => '*',
		'class' => 'permLinkDiv',
		'title' => '',
		'border' => 0
	);
$report = array(
		'src' => 'images/delete.png',
		'alt' => 'Report this meaning',
		'class' => 'reporting',
		'title' => 'Report this meaning',
		'border' => 0
	);
$branch = array(
		'src' => 'images/branch.png',
		'alt' => 'This reply is a meaning',
		'class' => 'reporting',
		'title' => 'This reply is a meaning',
		'border' => 0
	);

$list = array(
			'src' => base_url() . 'assets/images/public/view_all_tracks.png',
			'class' => 'viewalltracksImg'
		);
	echo('<div class="artistInfo">');	
		if ($row->artist_picture_verified == 1)
		{					
			$filename = substr($row->artist_picture, 0, -4);
			$extension = substr($row->artist_picture, -4);
			$artistPicture = $filename . '_thumb' . $extension;
			$artistPic = array(
						'src' => "http://static.unravelthemusic.com/artists/" . $artistPicture,
						'class' => 'artistPic',
						'align' => 'left'
					);
			echo(anchor('artists/view/' . $row->artist_seo_name, img($artistPic)));
		} else {
			$artistPic = array(
						'src' => base_url() . 'assets/images/public/blank_artist.png',
						'class' => 'artistPic',
						'align' => 'left'
					);
			echo(anchor('artists/view/' . $row->artist_seo_name, img($artistPic)));		
		}
		$tagArtist = "<span class='tagArtist' id='" . $row->artist_seo_name . "' ></span>";
		$tagArtist2 = "<span class='tagArtist' id='" . $row->artist_seo_name . "' style='display:none'></span>";
		$untagArtist = "<span class='untagArtist' id='" . $row->artist_seo_name . "'></span>";
		$untagArtist2 = "<span class='untagArtist' style='display:none' id='" . $row->artist_seo_name . "'></span>";
		if($watchingArtist == false)
		{					
			echo("<h3 class='artistName'>" . anchor('artists/view/' . $row->artist_seo_name, $row->artist) . "</h3><div class='tags'>" . $tagArtist . $untagArtist2. "</div><br />");
		} else {	  	
			echo("<h3 class='artistName'>" . anchor('artists/view/' . $row->artist_seo_name, $row->artist) . "</h3><div class='tags'>" . $untagArtist . $tagArtist2 . "</div><br />");
		}

		echo(anchor('songs/catalog/' . $row->artist_seo_name, img($list)) . '<br />');
		if($this->dx_auth->is_logged_in())
		{
			if($row->artist_picture == null || $row->artist_picture_verified == -1)
			{
				$upload = array(
					'src' => base_url() . 'assets/images/public/add_artist_image.png',
					'class' => 'viewalltracksImg'
					);
				?>
				<a href="/#" class="showForm"><?=img($upload)?></a>
				<div class="uploadFormHidden">
				
					<?php echo form_open_multipart('artists/upload_picture/'. $row->artist_seo_name);?>

						<input type="file" name="userfile" size="20" />

						<br />

						<input type="submit" value="upload" />

					</form>
				</div>
		 
				<?php 
			}
		}
		?>
	</div>
	<div class="songInfo">
		<span class="report">
			Report Song:
			<?=anchor('report/song/' . $row->song_id . '/duplicate/', img($duplicate))?>
			<?=anchor('report/song/' . $row->song_id . '/spam/', img($spam))?>
		</span>	
		<?php
		$tagSong = "<span class='tagSong' id='" . $row->artist_seo_name . "/" . $row->album_seo_name . "/" . $row->song_seo_name  ."'></span>";
		$tagSong2 = "<span class='tagSong' style='display:none' id='" . $row->artist_seo_name . "/" . $row->album_seo_name . "/" . $row->song_seo_name  ."'></span>";
		$untagSong = "<span class='untagSong' id='" . $row->artist_seo_name . "/" . $row->album_seo_name . "/" . $row->song_seo_name  ."'></span>";
		$untagSong2 = "<span class='untagSong' style='display:none' id='" . $row->artist_seo_name . "/" . $row->album_seo_name . "/" . $row->song_seo_name  ."'></span>";
		
		$tagAlbum = "<span class='tagAlbum' id='" . $row->artist_seo_name . "/" . $row->album_seo_name ."'></span>";
		$tagAlbum2 = "<span class='tagAlbum' style='display:none' id='" . $row->artist_seo_name . "/" . $row->album_seo_name ."'></span>";
		$untagAlbum = "<span class='untagAlbum' id='" . $row->artist_seo_name . "/" . $row->album_seo_name ."'></span>";
		$untagAlbum2 = "<span class='untagAlbum' style='display:none' id='" . $row->artist_seo_name . "/" . $row->album_seo_name ."'></span>";		
		?>
		<h2>Now Viewing:</h2> <?=$row->song?> 
		<?php
		if($watchingSong == false)
		{
		    echo("<div class='tags'>" . $tagSong . $untagSong2. "</div>");
		} else {
		    echo("<div class='tags'>" . $untagSong . $tagSong2. "</div>");
		}		
		?>
		<br />On the album <?=anchor('albums/view/' . $row->artist_seo_name . '/' . $row->album_seo_name, $row->album)?>
		<?php
		if($watchingAlbum == false)
		{
		    echo("<div class='tags'>" . $tagAlbum . $untagAlbum2. "</div>");
		} else {
		    echo("<div class='tags'>" . $untagAlbum . $tagAlbum2. "</div>");
		}	
		
		?>
	</div>

<?php
if($asin != NULL)
{
	$amazon = "<a href='http://www.amazon.com/gp/product/$asin?ie=UTF8&tag=unrthemus-20&linkCode=as2&camp=1789&creative=9325&creativeASIN=$asin'><img src='http://www.unravelthemusic.com/assets/images/public/amazon.png'</a>";
	

}
?>

<div class="lyrics">

	<?
		echo('Buy this song on: ' . $amazon . '<hr class="lyricHr" />');
		if($lyrics != 'Not found')
		{
			if($lyricsVerified == 0)
			{
				echo('We are currently verifying these lyrics.  Please check back later');
			} else {
				if(isset($lyricsId))
				{
					?>
					<span class="report">
						<?if($this->dx_auth->is_role('admin'))
						{
							echo anchor('lyrics/edit/' . $row->song_id, 'Edit Lyrics');
						}?>
						<div class="reportLyrics" id="<?=$row->song_id?>">Report Lyrics</div>
					</span>
					<?php			
					if($this->dx_auth->is_role(array('admin', 'poweruser')))
					{
						echo(anchor('/lyrics/expire/' . $lyricsId, 'Expire These Lyrics') . "<br /><br />");
					}
				
				}
				echo($lyrics);
			}
		} else {
			echo('We currently do not have lyrics content for this song at this time');
			if($this->dx_auth->is_logged_in())
			{
				echo('Add lyrics for this song for 20 user points<br /><br />');
				echo(form_open('lyrics/add/' . $row->song_id));
				$lyricInput = array(
							'cols' => '70',
							'rows' => '12',
							'name' => 'lyrics',
							'id' 	=> 'lyrics',
						);
				echo(form_textarea($lyricInput));
				echo(form_submit('submit', 'submit'));
				echo(form_close());
			} else {
				echo('<br />' . anchor('/users/login', 'Login') . ' or ' . anchor('/users/register', 'Register') . ' to add lyrics to this song');
			}
		}
	
	?>

</div>
<script type="text/javascript"><!--
google_ad_client = "pub-9570825177650183";
/* unravel-below_lyrics */
google_ad_slot = "2414366678";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

<div class="subNavTracks">
	<div class="meaningsTab">
		<?=anchor('/#', 'Meanings (' . $meaningCount . ')', array('class' => 'meaningsTabActive'))?>
	</div>
	<div class="commentsTab">
		<?=anchor('/#', 'Comments (' . $commentCount . ')', array('class' => 'commentsTabInactive'))?>
	</div>
	
</div>
<hr class="topTracksHr" />
<div class="meanings">
	
	<?php
	if($this->dx_auth->is_logged_in())
	{
	?>
		<div class="errorMeaning"></div>
		<?php 
		$random = rand(0,1);
		if($random == 1) {
		echo ('<div class="addFeature"></div>');
		}
		?>
		<div class="addMeaning">
			<?=anchor("#", "Add your meaning now!", array('class' => 'addMeaningLink', 'id' => $row->song_id))?>
		</div>
	<?php
	} else {
		echo(anchor('/users/login', 'Login') . ' or ' . anchor('/users/register', 'Register') . ' to add your meaning or comment about this song.');
	}
	$meanings = $meaningsQuery->result();
	$blockList = array();	
	if($this->session->userdata('DX_username'))
	{
		$blockList = $this->session->userdata('blockList');
	}
	$i = 0;
	if($numberOfTop == 0 || count($meanings) == $numberOfTop)
	{
		echo('<div id="zero"></div>');
	}
	$authPoints = array();

	foreach($topMeanings as $meaning)
	{
		$key = $meaning['key'];
	
		
		if($i == 0)
		{
			echo('<h3>Highest Rated Meanings:</h3>');
			
		} else if($i == $numberOfTop ) {
			echo('<hr /><br /><h3>Other Meanings:</h3>');
			echo('<div id="zero"></div>');
		}
		$i++;
		?>
		<div class="topMeanings">
			<div class="meaning" id="<?=$meanings[$key]->meaning_id?>">
				<a name="<?=$meanings[$key]->meaning_id?>"></a>
				<div class="meaningLeft">
					<?php
					$id = $this->session->userdata('DX_user_id');						
					$editable = 0;
					if($this->dx_auth->is_logged_in())
					{
						if(strtolower($meanings[$key]->author) == strtolower($this->session->userdata('DX_username')))
						{
							$editable = 1;
						}
					}					
					echo('<div class="voteUpMeaning" id="' . $meanings[$key]->meaning_id . '">' . img($voteup)  . '</div> ');
					if($userVotes != null && array_key_exists($meanings[$key]->meaning_id, $userVotes))
					{
						if($userVotes[$meanings[$key]->meaning_id] == 1)
						{
							echo('<div class="green"><div id="' . $meanings[$key]->meaning_id . 'votes" class="votes"  >' . $meaning['votes'] . '</div></div>');
						} else {
							echo('<div class="red"><div id="' . $meanings[$key]->meaning_id . 'votes" class="votes"  >' . $meaning['votes'] . '</div></div>');
						}
					} else {
						echo('<div id="' . $meanings[$key]->meaning_id . 'votes" class="votes">' . $meaning['votes'] . '</div>');
					}
					echo(' <div class="voteDownMeaning" id ="' . $meanings[$key]->meaning_id . '">' . img($votedown) . '</div>');
					?>
				</div>
				
				<div class="meaningRight">
					<?php
					$author = $meanings[$key]->author;
					if(!array_key_exists($author, $authPoints))
					{
						$authPoints[$author] = $this->usermodel_unravel->getPoints($author);
					}
					?>
					<span class='report'>Posted by <?=anchor('users/view/' . $meanings[$key]->author, $meanings[$key]->author) . '(' . $authPoints[$author] . ')' ?>
					<?php
						$this->load->helper('date');
						$posted = strtotime($meanings[$key]->created_on);
						$ago = now() - $posted;
					
						if($ago < 60) {
							echo($ago . ' seconds ago');
						} else if ($ago < 3600) {
							$ago = $ago / 60;
							echo(floor($ago));
							if(floor($ago) == 1)
							{
							echo(' minute ago');
							} else {
							echo (' minutes ago');
							}
						} else if ($ago < 86400) {
							$ago = $ago / (60 * 60);
							echo(floor($ago));
							if(floor($ago) == 1)
							{
							echo(' hour ago');
							} else {
							echo (' hours ago');
							}
						} else {
							$ago = $ago / (60 * 60 * 24);
							echo(floor($ago));
							if(floor($ago) == 1)
							{
							echo(' day ago');
							} else {
							echo (' days ago');
							}
						}

					?>									
					</span>
					<?php
					//If user is blocked do not show right side
					if(in_array($meanings[$key]->author, $blockList, TRUE))
					{
						echo('<div class="meaning_title"><strong>User blocked</strong></div>');
						$editable = 0;
					} else {
					?>					
					
					<div class="meaning_title_<?=$meanings[$key]->meaning_id?>"><strong><?=$meanings[$key]->title?></strong></div>
					<?php
					if($editable == 1)
					{
						echo('<a href="" class="editMeaning" id="' . $meanings[$key]->meaning_id . '" onclick="return false">Edit Meaning</a>');
						$meaningsIds[] = $meanings[$key]->meaning_id;					
					}
					/*
					echo("<br />Points: " . $authPoints[$author]);
					echo('<br />Title: ' . $this->usermodel_unravel->loadTitle($authPoints[$author]));
					*/
					?>
					<div class="meaning_content_body_<?=$meanings[$key]->meaning_id?>">
						<?=$meanings[$key]->body?>
					</div>
					<!--start replies-->
					<div class="replies">
						<br />
						
						<div class="<?=$meanings[$key]->meaning_id?>_replies" style="display:none">
							<hr />
							<?php
							$replies = 0;
							foreach($replyQuery->result() as $replyRow) 
							{
								if($replyRow->parent_id == $meanings[$key]->meaning_id) 
								{
									//add the variable to the js array so we can figure out what meanings to add buttons to.
									if($replies > 0)
									{
										$javascript[] = $meanings[$key]->meaning_id;
									}	
									$replies++;									
									?>
									<p>reply <?=$replies?>  </p>
									<?php
									if($replyRow->moved == 0) {
										echo('<span class="report">' . anchor('report/reply/' . $replyRow->reply_id, 'Report') . img($permLinkDiv) . anchor('report/reply/' . $replyRow->reply_id . '/meaning', 'Reply is a Meaning') . '</span><br />');
									}
									
									

									?>
									<div class="reply_info">
										<span class="report">
											<?php
											
											echo('Posted by: ' . $replyRow->author . ' ');
											$posted = strtotime($replyRow->created_on);
											
											$this->load->helper('date');
											
											$ago = now() - $posted;
										
											if($ago < 60) {
												echo($ago . ' seconds ago');
											
											} else if ($ago < 3600) {
												$ago = $ago / 60;
												echo(floor($ago));
												if(floor($ago) == 1)
												{
												echo(' minute ago');
												} else {
												echo (' minutes ago');
												}
											
											} else if ($ago < 86400) {
												$ago = $ago / (60 * 60);
												echo(floor($ago));
												if(floor($ago) == 1)
												{
												echo(' hour ago');
												} else {
												echo (' hours ago');
												}
											
											} else {
												$ago = $ago / (60 * 60 * 24);
												echo(floor($ago));
												if(floor($ago) == 1)
												{
												echo(' day ago');
												} else {
												echo (' days ago');
												}
											}
											
											?>
										</span>
										<?='<strong>' . $replyRow->title . '</strong>'?>
									</div>
									<div class="reply_body">
										<?=$replyRow->body?>
									</div>
									<br /><br />
									<?php
								}
							}
							?>
						</div><!--end hidden replies-->
						<span class='report'>
						<?php
						echo(anchor("meanings/view/" . $meanings[$key]->meaning_id, "Permalink") . img($permLinkDiv)); 
						echo(anchor('meanings/report/' . $meanings[$key]->meaning_id, 'Report'));
						?>
						</span>
						<?php
						//if the reply variable is ticked up at all create the submit button to show/hide all replies
						echo(anchor('meanings/reply/' . $meanings[$key]->meaning_id, 'Reply', array('class' => 'replyMeaning')));
						if($replies > 1)
						{
							echo(" <p class='show' id='" . $meanings[$key]->meaning_id . "' >(" . $replies. ' Replies - Click to View)</p>');
						} else if($replies == 1) {
							echo(" <p class='show' id='" . $meanings[$key]->meaning_id . "' >(" . $replies. ' Reply - Click to View)</p>');
						
						}
						?>
					</div><!--end replies-->
					<?php
					}
					?>
				</div>
			</div><!--end meaning-->
			<?php			

			if($editable == 1)
			{
				?>
				<div class="meaningEdit_<?=$meanings[$key]->meaning_id?>" style="display:none">
					<div class="errorEdit<?=$meanings[$key]->meaning_id?>"></div>
					<br /><h3>Edit your meaning</h3>
					<?php
					$attrs = array('class'=>'form_' . $meanings[$key]->meaning_id, 'id'=>'form_' . $meanings[$key]->meaning_id);
					echo(form_open('http://www.unravelthemusic.com/meanings/edit/' . $meanings[$key]->meaning_id, $attrs));
					$titleData = array(
						'name' => 'title',
						'class' => 'title_' . $meanings[$key]->title,
						'value' => $meanings[$key]->title,
					);
					echo(form_input($titleData));
					echo(form_input('body', $meanings[$key]->body));
					echo(form_submit('submit', 'Submit Edit'));
					echo("</form>");
					?>
				</div>
				<?php
			}	
			?>
		</div><!--end top meanings--><br clear="both" />
	<?php
	}
	?>
</div>
<div class="comments">
	
		<?php
	if($this->dx_auth->is_logged_in())
	{
	?>
		<div class="errorComment"></div>
		<div class="addComment">
			<?=anchor("#", "Add a comment about this song", array('class' => 'addCommentLink', 'id' => $row->song_id))?>
		</div>
		<?php
	} else {
		echo(anchor('/users/login', 'Login') . ' or ' . anchor('/users/register', 'Register') . ' to add your meaning or comment about this song.');
	}
	echo('<div id="zeroComment"></div>');
	if($commentQuery->num_rows() > 0)
	{
		echo("<h3>" . $commentQuery->num_rows . ' Comments</h3>');
		foreach($commentQuery->result() as $comment)
		{
		?>
			<div class="meaning">
				<div class="meaningRight">
				<?php
					$author = $comment->author;
					$this->load->model('usermodel_unravel');
					?>
					<span class='report'>Posted by <?=anchor('users/view/' . $comment->author, $comment->author) . '(' . $this->usermodel_unravel->getPoints($comment->author) . ')' ?>
					<?php
						$this->load->helper('date');
						$posted = strtotime($comment->created_on);
						$ago = now() - $posted;
						if($ago < 60) {
							echo($ago . ' seconds ago');
						} else if ($ago < 3600) {
							$ago = $ago / 60;
							echo(floor($ago));
							if(floor($ago) == 1)
							{
							echo(' minute ago');
							} else {
							echo (' minutes ago');
							}
						} else if ($ago < 86400) {
							$ago = $ago / (60 * 60);
							echo(floor($ago));
							if(floor($ago) == 1)
							{
							echo(' hour ago');
							} else {
							echo (' hours ago');
							}
						} else {
							$ago = $ago / (60 * 60 * 24);
							echo(floor($ago));
							if(floor($ago) == 1)
							{
							echo(' day ago');
							} else {
							echo (' days ago');
							}
						}
					?>									
					</span>
					<?php
					if(in_array($author, $blockList, TRUE))
					{
						echo('<div class="mcomment_title"><strong>User blocked</strong></div>');
						$editable = 0;
					} else {
					?>					
						<div class="comment_title_<?=$comment->comment_id?>"><strong><?=$comment->title?></strong></div>
						<div class="comment_content_body_<?=$comment->comment_id?>">
							<?=$comment->body?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		<?php
		
		}
	}
	?>
</div>
<script src="http://www.unravelthemusic.com/assets/js/public/jquery.color.min.js" type="text/javascript"></script>	
