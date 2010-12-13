<link rel="alternate" type="application/rss+xml" title="RSS" href="http://feeds2.feedburner.com/UnravelTheMusic" />
<?php
$logo = array(
			'class' => 'blogLogo',
			'alt' => 'Unravel The Music Official Music Blog',
			'src' => 'assets/images/public/substance_logo.png'
		);
echo(anchor('blog', img($logo)));
?>
<br />
<div class="blogLinks">
	<span class="blogLinksRight">
		<?=anchor('blog/feed', 'Subscribe', array('class' => 'blogLink'))?>
	</span>
	<span class="blogLinksLeft">
		<?=anchor('blog/archives/', 'Archives', array('class' => 'blogLink'))?> |
		<?=anchor('blog/category/', 'Categories', array('class' => 'blogLink'))?>
	</span>
</div>
<hr class="blogHr" />
<?php
foreach($query->result() as $row)
{
	echo(anchor('blog/category/' . url_title($row->category), $row->category) . '<br />');


}