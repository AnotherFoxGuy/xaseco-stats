<?php
/*************************************************
*                                                *
*   Stats for XAseco + RASP                      *
*   __________________________________________   *
*                                                *
*   Version 3.7                                  *
*   Copyright (c) 2007-2008 by oS.Cypher         *
*   Developed as a Project of Old School Gaming  *
*                                                *
*   http://www.os-gaming.de                      *
*                                                *
*************************************************/


require("config.php");
if ($show_php_errors) error_reporting(E_ALL);
else error_reporting(1);
require("includes/globals.php");
include("includes/functions.php");
include("languages/".$lang.".php");
include("includes/ColorParser.php");
$cp = new ColorParser();

$server_count = count($server_name);
if ($server_count == 1) {
	if ($page == 'home') $page = "info";
	$serv = 0;
}
if (!is_null($serv)) include("includes/db_connect.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>XAseco Stats - Version 3.7</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php include("includes/styles.php"); ?>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" type="image/ico" href="favicon.ico">
</head>
<body>

<div id="container">

	<div id="header">
	<?php
		$header_img = "<img src=\"img/header.jpg\" alt=\"XAseco Stats\" border=\"0\" />";
		if ($server_count == 1) {
			print "<h1>" . $header_img . "</h1>\n";
		} else {
			print "<h1><a href=\"?lang=" . $lang . "&page=home\">" . $header_img . "</a></h1>\n";
		}

		print "<div class=\"lang-select\">";
			$langurl = "<a href=\"";
			$langurl .= (!is_null($serv)) ? "?serv=".$serv."&" : "?";
			$langurl .= "lang=%s&page=".$page;
			if ($plid) $langurl .= "&plid=".$plid;
			if ($trid) $langurl .= "&trid=".$trid;
			if ($UId) $langurl .= "&UId=".$UId;
			if ($subpage) $langurl .= "&subpage=".$subpage;
			$langurl .= "\">";
			$langimg = "<img src=\"img/flags/%s.png\" width=\"18\" height=\"12\" border=\"0\">";

			printf($langurl, "ENG") . printf($langimg, "GBR") . print "</a>\n";
			printf($langurl, "GER") . printf($langimg, "GER") . print "</a>\n";
			printf($langurl, "FRA") . printf($langimg, "FRA") . print "</a>\n";
			printf($langurl, "NED") . printf($langimg, "NED") . print "</a>\n";
		print "</div>";

		print "<div class=\"header-links\">\n";
		if (($server_count > 1) && ($page != 'home')) print "<a href=\"?lang=" . $lang . "&page=home\">" . $lng_serverlist . "</a>\n";
		print "</div>\n";
		print "<h2 class=\"headtitle\">" . (($page == 'home') ? $lng_serverlist : $server_name[$serv]) . "</h2>\n";
	?>
	</div>

	<?php
	if ($page != 'home') {
		print "<div class=\"page-menu\">
				<table cellpadding=\"0\" border=\"0\" align=\"center\">
				  <tr valign=\"middle\">";
					if ($server_count == 1) {
						$butwidth = "20%";
						print "<td class=\"menuback width=\"$butwidth\"" . (($page == 'info') ? " active" : "") . "\"><a href=\"?serv=" . $serv . "&lang=" . $lang . "&page=info\" class=\"menufont\">" . $lng_title_info . "</a></td>";
					} else {
						$butwidth = "25%";
					}
				    print "<td width=\"$butwidth\" class=\"menuback" . (($page == 'players') ? " active" : "") . "\"><a href=\"?serv=" . $serv . "&lang=" . $lang . "&page=players\" class=\"menufont\">" . $lng_title_players . "</a></td>
				    <td width=\"$butwidth\" class=\"menuback" . (($page == 'tracks') ? " active" : "") . "\"><a href=\"?serv=" . $serv . "&lang=" . $lang . "&page=tracks\" class=\"menufont\">" . $lng_title_tracks . "</a></td>
				    <td width=\"$butwidth\" class=\"menuback" . (($page == 'serverstats') ? " active" : "") . "\"><a href=\"?serv=" . $serv . "&lang=" . $lang . "&page=serverstats&subpage=server\" class=\"menufont\">" . $lng_title_serverstats . "</a></td>
				    <td width=\"$butwidth\" class=\"menuback" . (($page == 'search') ? " active" : "") . "\"><a href=\"?serv=" . $serv . "&lang=" . $lang . "&page=search\" class=\"menufont\">" . $lng_title_search . "</a></td>
				  </tr>
				</table>
			</div>\n";
	}
	?>

	<div id="content">
	<?php include("pages/".$page.".php"); ?>
	</div>

	<?php
	if(isset($link_prev) || isset($link_next)) {
	?>
	<div class="pagination">
		<table width="100%" bgcolor="<?php echo $bg4 ?>" border="0" align="center">
		  <tr>
		    <td align="left">
		      <?php if($link_prev != "") print "<a href=".$link_prev."><img src=\"img/prev.png\" border=\"0\" /></a>\n"; ?>
		    </td>
		    <td align="right">
		      <?php if($link_next != "") print "<a href=".$link_next."><img src=\"img/next.png\" border=\"0\" /></a>\n"; ?>
		    </td>
		  </tr>
		</table>
	</div>
	<?php
	}
	?>

	<div id="footer">
		<table width="100%" border="0" align="center">
			<tr class="footerback">
				<td class="footer">
					<?php
					if ($page != 'home' && $show_server_banners) print "<a href=\"?serv=".$serv."&lang=".$lang."&page=serverstatsbox\">Stats</a>";
					else print "Stats";
					?>
					for XAseco + RASP - Version 3.7 - Copyright by oS.Cypher<br>
					Updated by Xymph, W1lla, Assembler Maniac, Ant, sn3p, AnotherFoxGuy<br>
					<a href="https://github.com/AnotherFoxGuy/xaseco-stats">Download XAseco-Stats</a>
				</td>
			</tr>
		</table>
	</div>

</div>

</body>
</html>

<?php
/*********************************************
* It is not allowed to remove the Copyright! *
*              www.os-gaming.de              *
*********************************************/
?>