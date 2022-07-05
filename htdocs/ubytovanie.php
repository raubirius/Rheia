<?php
include 'pdf.php';
$rssBaseName = 'ubytovanie';
// $rssXML = 'http://www.truni.sk/sk/taxonomy/term/19/all/feed';
// $rssXML = 'http://www.truni.sk/taxonomy/term/19/all/feed:64';
// $rssXML = 'http://www.truni.sk/taxonomy/term/19/all/feed';
$rssXML = 'https://www.truni.sk/taxonomy/term/19/all/feed';
include 'design.php';
include 'RSSShowArticlesList.php';
?>