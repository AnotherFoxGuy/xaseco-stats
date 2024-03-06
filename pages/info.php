<?php
$timeout = 5;
$fp = @fsockopen($ip2[$serv], $server_port[$serv], $errno, $errstr, $timeout);
if($fp) {
	fclose($fp);
	require("includes/GbxRemote.inc.php");

	$client = new IXR_Client_Gbx;

	if (!$client->InitWithIp($ip[$serv], $port[$serv])) {
		die('An error occurred - '.$client->getErrorCode().":".$client->getErrorMessage());
	}
	$res = $client->query("Authenticate", $user[$serv], $pass[$serv]);
	if (!$res) {
		//Error($client->getErrorMessage(), $client->getErrorCode());
		print "<p align=\"center\" class=\"tmxoffline\">login failed !</p>";
		return;
	}
	if($client->query('GetStatus')) {
		$Status = $client->getResponse();
	}
	// return;
	if($client->query('GetPlayerList', 500, 0)) {
		$Players = $client->getResponse();
	}
	if($client->query('GetServerOptions')) {
		$ServerOptions = $client->getResponse();
		$servername=$ServerOptions['Name'];
	}
	if($client->query('GetCurrentChallengeInfo')) {
		$CurrentChallengeInfo = $client->getResponse();
	}

	$trackname=$CurrentChallengeInfo['Name'];
	$players = count($Players);
	?>

	<p class="tabletitle"></p>

	<table width="100%" border="0" bgcolor="<?php echo $bg4 ?>" align="center">
	 <tr valign="middle" bgcolor="<?php echo $bg1 ?>" onMouseOver="this.style.backgroundColor='<?php echo $bg3 ?>';" onMouseOut="this.style.backgroundColor='<?php echo $bg1 ?>';">
	    <td width="50%" class="celltext-rb"><?php echo $lng_stats_s_name ?>&nbsp;</td>
	    <td class="celltext-l">&nbsp;<?php echo $cp->toHTML($servername) ?></td>
	 </tr>
	 <tr valign="middle" bgcolor="<?php echo $bg2 ?>" onMouseOver="this.style.backgroundColor='<?php echo $bg3 ?>';" onMouseOut="this.style.backgroundColor='<?php echo $bg2 ?>';">
	    <td width="50%" class="celltext-rb"><?php echo $lng_stats_s_status ?>&nbsp;</td>
	    <td class="celltext-l">&nbsp;<?php echo $Status['Name'] ?></td>
	 </tr>
	 <tr valign="middle" bgcolor="<?php echo $bg1 ?>" onMouseOver="this.style.backgroundColor='<?php echo $bg3 ?>';" onMouseOut="this.style.backgroundColor='<?php echo $bg1 ?>';">
	    <td width="50%" class="celltext-rb"><?php echo $lng_info_track ?>:&nbsp;</td>
	    <td class="celltext-l">&nbsp;<a href="?lang=<?php echo $lang ?>&page=tmx_info&UId=<?php echo $CurrentChallengeInfo['UId'] ?>" class="celltext"><?php echo $cp->toHTML($trackname) ?></a></td>
	 </tr>
	 <tr valign="middle" bgcolor="<?php echo $bg2 ?>" onMouseOver="this.style.backgroundColor='<?php echo $bg3 ?>';" onMouseOut="this.style.backgroundColor='<?php echo $bg2 ?>';">
	    <td width="50%" class="celltext-rb"><?php echo $lng_info_players ?>:&nbsp;</td>
	    <td class="celltext-l">&nbsp;<?php echo $players." / ".$ServerOptions['CurrentMaxPlayers'] ?></td>
	  </tr>
	</table>
	<br>

<?php
} else {
	echo "<p align=\"center\" class=\"tmxoffline\">".$lng_stats_serveroff."</p>\n";
	echo "$errstr ($errno)<br />\n";
}
?>