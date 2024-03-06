<?php

$timeout = 5;
require("includes/GbxRemote.inc.php");

print "<div id=\"server-listing\">\n";
for ($i = 0; $i < $server_count; $i++)
{
	$url = "?serv=" . $i . "&lang=" . $lang . "&page=players";

	$fp = @fsockopen($ip2[$i], $server_port[$i], $errno, $errstr, $timeout);
	if($fp) {
		$client = new IXR_Client_Gbx;

		if (!$client->InitWithIp($ip[$i], $port[$i])) {
			die('An error occurred - '.$client->getErrorCode().":".$client->getErrorMessage());
		}

		$showinfo = 0;
		if (!$client->query("Authenticate", $user[$i], $pass[$i])) {
			//print "<p align=\"center\" class=\"tmxoffline\">login failed !</p>";
			//continue;
		} else {
			$showinfo = 1;
			if($client->query('GetStatus')) {
				$Status = $client->getResponse();
			}
			if($client->query('GetPlayerList', 500, 0)) {
				$Players = $client->getResponse();
			}
			if($client->query('GetServerOptions')) {
				$ServerOptions = $client->getResponse();
				$servername = $ServerOptions['Name'];
			}
			if($client->query('GetCurrentChallengeInfo')) {
				$CurrentChallengeInfo = $client->getResponse();
			}

			$trackname = $CurrentChallengeInfo['Name'];
			$players = count($Players);
		}

		print "<div class=\"server\" onclick=\"javascript: window.location.href='".$url."';\" onMouseOver=\"this.className+=' hover';\" onMouseOut=\"this.className=this.className.replace(new RegExp(' hover\\\b'), '');\">
				<table cellpadding=\"0\" cellspacing=\"0\">\n";
				  if ($showinfo) {
				  	print "<tr><td class=\"server-name\" colspan=\"2\">" . $cp->toHTML($servername) . "</td></tr>
			  			<tr><th class=\"label\"><span>".$lng_stats_s_status."</span></td><td>".$Status['Name']."</td></tr>
			 			<tr><th class=\"label\"><span>".$lng_info_track.":</span></td><td>".$cp->toHTML($trackname)."</td></tr>
			 			<tr><th class=\"label\"><span>".$lng_info_players.":</span></td><td>".$players."/".$ServerOptions['CurrentMaxPlayers']."</td></tr>\n";
				  } else {
				  	print "
					  <tr>
						 <td class=\"server-name\" colspan=\"2\">" . $server_name[$i] . "</td>
					  </tr>
					  <tr>
						 <th class=\"label\"><span>" . $lng_stats_s_status . "</span></td>
						 <td class=\"red\">Could not retreive server info.</td>
					  </tr>\n";
				  }
				print "</table></div>\n";

	} else {

		print "<div class=\"server\" onclick=\"javascript: window.location.href='".$url."';\" onMouseOver=\"this.className+=' hover';\" onMouseOut=\"this.className=this.className.replace(new RegExp(' hover\\\b'), '');\">
				<table cellpadding=\"0\" cellspacing=\"0\">
				  <tr>
					 <td class=\"server-name\" colspan=\"2\">" . $server_name[$i] . "</td>
				  </tr>
				  <tr>
					 <th class=\"label\"><span>" . $lng_stats_s_status . "</span></td>
					 <td class=\"red\">Down</td>
				  </tr>
			   </table>
			   </div>";
	}
}
print "</div>\n";

?>