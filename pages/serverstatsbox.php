<?php

$addr = getUrlPath();
$script = "serverstats.php?plid=".$plid."&serv=".$serv."&statsbox=%d";
$statsbox = array(3,1,2);

foreach ($statsbox as $box)
{
	$link = sprintf($script, $box);

	print "<p><img src=\"".$link."\"></p>\n";

	print "<p>HTML-Code:<br>\n";
	$tag = "<img src=\"".$addr.$link."\">";
	$tag = htmlentities($tag);
	print "<span class=\"celltext\">".$tag."</span>\n";

	print "</p><p>BB-Code:<br>";
	$tag = "[img]".$addr.$link."[/img]";
	$tag = htmlentities($tag);
	print "<span class=\"celltext\">".$tag."</span>\n";
	print "</p><br>";
}

?>