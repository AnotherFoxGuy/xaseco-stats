<?php

require_once('./classes/tmfdatafetcher.inc.php');
require_once('./classes/tmfcolorparser.inc.php');

$cp = new TMFColorParser();
$logins = array('fufi', 'fuckfish', 'fuckfish3', 'no_lolmaps', 'klinkitsv', 'scotty_tmnf', 'trc_nike', 'speedbreaker2', 'smashingdeluxe', 'mic3man', 'fraese', 'shepard07', 'ladydeluxe', '__lynx__', '_00fbzf_0f0_w_itazz', 'loltazz', 'carstoo', 'elch2006', 'gluexxxkeks', 'rdx08', 'passat20vt', 'gunslinger', 'raingods', 'schnaegg', 'smashingdeluxe', 'smashingdeluxe2');

echo "<html> 
		<head> 
			<meta http-equiv='Content-Language' content='de'> 
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>  
			<title>Sample login list</title>
		</head> 
	  <body style='background:#BBBBBB; font-family:Arial, sans-serif'>
	  	<table>";
echo '<tr><td><b>Login</b></td><td><b>Nick</b></td><td><b>Nation</b></td><td width="120px" align="right"><b>Ladderpoints</b><td width="100px" align="right"><b>Worldrank</b></td><td width="100px" align="right"><b>Time</b></td><td width="100px" align="center"><b>Tries</b></td><td><b>Error</b></td></tr>';

$begin = microtime(true);
foreach ($logins as $login){
	$tries = 0;
	$time = microtime(true);
	do {
		$info = new TMFDataFetcher($login);
		$error = $info->getError();
		$errorCode = $error['code'];
		$tries++;
	} while ($errorCode == 125);
	$time = microtime(true)-$time;
	echo '<tr><td>'.$login.'</td>';
	echo '<td>'.$cp->toHTML($info->nickname).'</td>';
	echo '<td align="center"><img src="'.$info->nationiconURL.'" alt="'.htmlentities(utf8_decode($info->zonepath)).'"></td>';
	echo '<td align="right">'.$info->ladderpoints.'</td>';
	echo '<td align="right">'.$info->worldrank.'</td>';
	echo '<td align="right">'.(round($time*1000)/1000).' s</td>';
	echo '<td><center>'.$tries.'</center></td>';
	echo '<td>'.$error['msg'].'</td>';
	echo '</tr>';
}

echo "</table>
<br>
<br>Total execution time: ".(round((microtime(true)-$begin)*1000)/1000)." seconds</body></html>";
?>