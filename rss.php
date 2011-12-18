<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:media="http://search.yahoo.com/mrss/">
<channel>

	<title>clmpr.com <?php echo $endpoint ?></title>
	<link>http://www.clmpr.com/</link>
	<description></description>
	<title>Citation, Logging and Multi-Purpose aRchive</title>

	<?php for($i = 0; $row = $q->fetch(); $i++ ): ?>
	<item>
	    <title><?php echo htmlentities($row['title']) ?></title>
	    <link><?php echo urlencode($row['url']) ?></link>
	    <pubDate><?php echo date('r', strtotime($row['date'])); ?></pubDate>

	</item>	

	<?php endfor; ?>

</channel>
</rss>

<?php

exit;

/*


<title>clmpr.com <?php echo $endpoint ?></title>
<link>http://www.clmpr.com/</link>
<description></description>
<title>Citation, Logging and Multi-Purpose aRchive</title>


<?php for($i = 0; $row = $q->fetch(); $i++ ): ?>

<item>
    <title><?php echo $row['title'] ?></title>
    <link><?php echo $row['url'] ?></link>
    <pubDate><?php echo date('r', strtotime($row['date'])); ?></pubDate>
    <tags><?php echo $row['tags'] ?></tags>
    <postedby><?php echo $row['user'] ?></postedby>
    <description>posted by <?php echo $row['user'] ?></description>
</item>

<?php endfor; ?>

*/