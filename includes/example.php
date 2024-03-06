<?php
$login = $_GET['login'];
if (!$login) $login = 'fufi';

require_once('./classes/tmfdatafetcher.inc.php');
require_once('./classes/tmfcolorparser.inc.php');

$cp = new TMFColorParser();

$info = new TMFDataFetcher($login);
if ($info->errorOccured()){
	$info->outputError();
} else {
	
echo "<html> <head> <meta http-equiv='Content-Language' content='de'> 
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>  </head> <body style='background:#BBBBBB; font-family:Arial, sans-serif'>";
	$nickname = $cp->toHTML($info->nickname);
	echo "<br>
	Login: $login<br>
	Nickname: $nickname<br>
	Nickname Source: $info->nickname<br>
	Nation: $info->nation<br>
	Nation Short: $info->nationshort<br>
	Nation Flag: "; 	echo '<img src="'.$info->nationiconURL.'"/><br>';
	echo"
	Homezone: $info->homezone<br>
	Zone Path: $info->zonepath<br>
	Account Type: $info->accountType<br>
	Skillpoints: $info->skillpoints<br>
	Skillrank: $info->skillrank<br>
	Worldrank: $info->worldrank<br>
	Ladderpoints: $info->ladderpoints<br>
	Nationrank: $info->nationrank<br>
	Homezonerank: $info->homezonerank<br>
	";
	echo 'Nation Flag Big: <img src="'.$info->nationflagURL.'"/><br>';
	echo 'Homezone Flag Big: <img src="'.$info->homezoneflagURL.'"/><br>';
	echo '</body></html>';
//	var_dump($info);
}

?>