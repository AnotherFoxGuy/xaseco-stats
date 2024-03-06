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
	if (!$client->query("Authenticate", $user[$serv], $pass[$serv])) {
		//Error($client->getErrorMessage(), $client->getErrorCode());
		print "<p align=\"center\" class=\"tmxoffline\">login failed !</p>";
		return;
	}
	if($client->query('GetVersion')) {
		$Version = $client->getResponse();
		}
	if($client->query('GetStatus')) {
		$Status = $client->getResponse();
		}
	if($client->query('GetServerOptions')) {
		$ServerOptions = $client->getResponse();
		$servername=$ServerOptions['Name'];
		$comment=$ServerOptions['Comment'];
		$servername=$cp->toHTML($servername);
		$comment=$cp->toHTML($comment);
		}
	if($client->query('GetCurrentGameInfo')) {
		$GameInfo = $client->getResponse();
		}
		if($GameInfo['GameMode']==0) {
				$GameMode="Rounds";
			}elseif($GameInfo['GameMode']==1) {
				$GameMode="TimeAttack";
			}elseif($GameInfo['GameMode']==2) {
				$GameMode="Team";
			}elseif($GameInfo['GameMode']==3) {
				$GameMode="Laps";
			}elseif($GameInfo['GameMode']==4) {
				$GameMode="Stunts";
			}
		$chattime=$GameInfo['ChatTime']/1000;
	if($client->query('GetCurrentChallengeInfo')) {
		$CurrentChallengeInfo = $client->getResponse();
		}
		$trackname=$CurrentChallengeInfo['Name'];
		//include("includes/trackname.php");
		$trackname=$cp->toHTML($trackname);
		$MWTime = $CurrentChallengeInfo['AuthorTime'];
		$minutes = floor($MWTime/(1000*60));
		$seconds = floor(($MWTime-$minutes*60*1000)/1000);
		$mseconds = substr($MWTime,strlen($MWTime)-3,2);
		$atime=sprintf("%02d:%02d.%02d", $minutes, $seconds, $mseconds);
		$atimest=sprintf("%02d", $MWTime);
		$MWTime = $CurrentChallengeInfo['GoldTime'];
		$minutes = floor($MWTime/(1000*60));
		$seconds = floor(($MWTime-$minutes*60*1000)/1000);
		$mseconds = substr($MWTime,strlen($MWTime)-3,2);
		$gtime=sprintf("%02d:%02d.%02d", $minutes, $seconds, $mseconds);
		$gtimest=sprintf("%02d", $MWTime);
		$MWTime = $CurrentChallengeInfo['SilverTime'];
		$minutes = floor($MWTime/(1000*60));
		$seconds = floor(($MWTime-$minutes*60*1000)/1000);
		$mseconds = substr($MWTime,strlen($MWTime)-3,2);
		$stime=sprintf("%02d:%02d.%02d", $minutes, $seconds, $mseconds);
		$stimest=sprintf("%02d", $MWTime);
		$MWTime = $CurrentChallengeInfo['BronzeTime'];
		$minutes = floor($MWTime/(1000*60));
		$seconds = floor(($MWTime-$minutes*60*1000)/1000);
		$mseconds = substr($MWTime,strlen($MWTime)-3,2);
		$btime=sprintf("%02d:%02d.%02d", $minutes, $seconds, $mseconds);
		$btimest=sprintf("%02d", $MWTime);
	if($client->query('GetPlayerList',50,0)) {
		$Players = $client->getResponse();
		}
	if($client->query('GetCurrentRanking',50,0)) {
		$Ranking = $client->getResponse();
		}
		if ($stunt[$serv]){
	  echo "<div class=\"sub-menu\">
			<table width=\"100%\" border=\"0\" align=\"center\">
			  <tr valign=\"middle\" bgcolor=".$menubg.">
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'server') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=server\" class=\"menufont\">".$lng_stats_server."</a></td>
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'game') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=game\" class=\"menufont\">".$lng_stats_game."</a></td>
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'challenge') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=stchallenge\" class=\"menufont\">".$lng_stats_challenge."</a></td>
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'players') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=players\" class=\"menufont\">".$lng_stats_players."</a></td>
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'ranking') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=stranking\" class=\"menufont\">".$lng_stats_ranking."</a></td>
				<td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'ServerBanners') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=ServerBanners\" class=\"menufont\">".$lng_stats_Server_Banners."</a></td>
			  </tr>
			</table>
		  </div>";		
        } else {
	  echo "<div class=\"sub-menu\">
			<table width=\"100%\" border=\"0\" align=\"center\">
			  <tr valign=\"middle\" bgcolor=".$menubg.">
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'server') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=server\" class=\"menufont\">".$lng_stats_server."</a></td>
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'game') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=game\" class=\"menufont\">".$lng_stats_game."</a></td>
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'challenge') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=challenge\" class=\"menufont\">".$lng_stats_challenge."</a></td>
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'players') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=players\" class=\"menufont\">".$lng_stats_players."</a></td>
			    <td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'ranking') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=ranking\" class=\"menufont\">".$lng_stats_ranking."</a></td>
				<td width=\"16.666667%\" class=\"menuback2" . (($subpage == 'ServerBanners') ? " active" : "") . "\"><a href=\"?serv=".$serv."&lang=".$lang."&page=serverstats&subpage=ServerBanners\" class=\"menufont\">".$lng_stats_Server_Banners."</a></td>
			  </tr>
			</table>
		  </div>";
		 }

	if($subpage){
		include("subpages/".$subpage.".php");
	}
} else {
	echo "<span class=\"tmxoffline\"><p align=\"center\">".$lng_stats_serveroff."</p></span>";
}
?>
