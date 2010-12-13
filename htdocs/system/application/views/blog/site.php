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
		<?=anchor('blog/', 'Music News', array('class' => 'blogLink'))?>
	</span>
</div>
<hr class="blogHr" />
<?php
		foreach($results->result() as $row)
		{
			?>
				<div class="blogPostHeader">
					<div class="blogPostTitle">
						<h1><?=anchor('blog/view/' . $row->post_id, $row->title, array('class' => 'blogHeader'))?></h1>
					</div>
					<?php
						$date = $row->created_on;
						$day = date("d", strtotime($date));
						$month = date('M', strtotime($date));
						$year = date('Y', strtotime($date));
					?>
					<div class="blogPostDate">
						<span class="blogPostYear">
							<?=$month?><br /><?=$year?>
						</span>
						<span class="blogPostDivider">
							|
						</span>
						<span class="blogPostDay">
							<?=$day?>
						</span>
					</div>
				</div>
				<div class="blogPost">
					<?=$row->summary?>
				</div>
				<div class="blogPostFooter">
				<?php
					$filing = array(
								'src' => 'assets/images/public/substance_filing.png',
					
						);
				?>
					<p class="blogCategory"><?=img($filing)?> In: <?=anchor('blog/category/' . url_title($row->category), $row->category, array('class' => 'blogPostCategory'))?></p>
					<span class="blogLinksRight">
						<?php
						$readMore = array(
									'src' => 'assets/images/public/substance_read_more.png',
						);
						
						$comments = array(
									'src' => 'assets/images/public/substance_comments.png',
						);
						
						?>
						<?=anchor('blog/view/' . $row->post_id, img($readMore) . ' Read More', array('class' => 'blogLink'))?> | <?=anchor('blog/view/' . $row->post_id . '#comments', img($comments) . ' Comments', array('class' => 'blogLink'))?>
					</span>
				</div>
			<br /><br />
			<?php
		}

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
<div onMouseOver="showDiv('TW-Pop2');return false;" onMouseOut="showDiv('TW-Pop2');return false;">
<ul id="TW-feed">
<li>Subscribe <img src="http://i150.photobucket.com/albums/s93/twistermc/feed-icon.gif" border="0">
<ul id="TW-Pop2">
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
