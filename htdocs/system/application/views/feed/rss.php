<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">


    <channel>
    
		<title><?php echo $feed_name; ?></title>

		<link><?php echo $feed_url; ?></link>
		<description><?php echo $page_description; ?></description>
		<dc:language><?php echo $page_language; ?></dc:language>
		<dc:creator><?php echo $creator_email; ?></dc:creator>

		<dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
		<admin:generatorAgent rdf:resource="http://www.UnravelTheMusic.com/" />

		<?php foreach($posts->result() as $entry): ?>
			<?php
				date_default_timezone_set('America/Chicago');
				$date = strtotime($entry->created_on);
				$dateNorm = date('D, d M Y H:i:s T', $date);
			?>
			<item>
				<title><?php echo xml_convert($entry->title); ?></title>
				<link><?php echo site_url('blog/view/' . $entry->seo_title) ?></link>
				<guid><?php echo site_url('blog/view/' . $entry->seo_title) ?></guid>
				<description><![CDATA[
					<?=substr($entry->body, 0, 1500)?>
					<br /><br />To View the entire post <a href="<?=site_url('blog/view/' . $entry->seo_title)?>">Click here</a>
					]]>
				</description>
				<pubDate><?php  echo $dateNorm ?></pubDate>
			</item>   
		<?php endforeach; ?>
    </channel>
</rss> 