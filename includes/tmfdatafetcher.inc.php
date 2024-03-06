<?php
/**
 * TMFDataFetcher by oorf|fuckfish (Alexander P.)
 * Original Version 1.3 by oorf|fuckfish
 * Version 1.5 by int`r W1lla (Willem van den Munckhof)
 * Version 1.5b by RamCUP2000  *   
 */

class Environment{

	var $name;
	var $wins;
	var $draws;
	var $losses;
	var $ladderpoints;
	var $ranking;

	function Environment($xml){
		$this->name   = strval($xml->enviro);
		$this->wins   = intval($xml->wins);
		$this->draws  = intval($xml->draws);
		$this->losses = intval($xml->losses);
		$this->ladderpoints = floatval($xml->ladderpoints);
		$this->ranking = array();
		foreach ($xml->ranking as $rank){
			$entry = array();
			$entry['idzone']=intval($rank->idzone);
			$entry['zonepath']=strval($rank->zonepath);
			$entry['rank']=intval($rank->rank);
			$this->ranking[] = $entry;
		}
	}

	function getSpezificZoneRanking($zonepath){
		foreach ($this->ranking as $rank){
			if (substr($rank['zonepath'], strlen($rank['zonepath'])- strlen($zonepath)) == $zonepath) return $rank;
		}
		return false;
	}

	function getFullRankingArray(){
		return $this->ranking;
	}

}

class Ladder{
	var $Merge, $Bay, $Coast, $Desert, $Island, $Rally, $Snow, $Stadium;

	function Ladder($ladderarray, $freeaccount){
		if ($freeaccount){
			$this->Bay = null;
			$this->Coast = null;
			$this->Desert = null;
			$this->Island = null;
			$this->Rally = null;
			$this->Snow = null;
			$this->Merge = new Environment($ladderarray[0]);
			$this->Stadium = new Environment($ladderarray[1]);
		} else {
			$this->Merge = new Environment($ladderarray[0]);
			$this->Bay = new Environment($ladderarray[1]);
			$this->Coast = new Environment($ladderarray[2]);
			$this->Desert = new Environment($ladderarray[3]);
			$this->Island = new Environment($ladderarray[4]);
			$this->Rally = new Environment($ladderarray[5]);
			$this->Snow = new Environment($ladderarray[6]);
			$this->Stadium = new Environment($ladderarray[7]);
		}
	}

	function getFullArray(){
		$result = array();
		if ($this->Bay == null){
			$envs = array('Merge', 'Stadium');
		} else {
			$envs = array('Merge', 'Bay', 'Coast', 'Desert', 'Island', 'Rally', 'Snow', 'Stadium');
		}
		foreach ($envs as $env){
			$entry = array();
			$entry['environment'] = $env;
			eval('$entry["wins"] = $this->'.$env.'->wins;');
			eval('$entry["draws"] = $this->'.$env.'->draws;');
			eval('$entry["losses"] = $this->'.$env.'->losses;');
			eval('$entry["ladderpoints"] = $this->'.$env.'->ladderpoints;');
			eval('$entry["ranking"] = $this->'.$env.'->getFullRankingArray();');
			$result[] = $entry;
		}
		return $result;
	}
}

class TMFDataFetcher{

	var $login;

	var $id;
	var $nickname;
	var $freeaccount;
	var $accountType;
	var $idzone;
	var $zonepath;
	var $maniastars;
	var $lastmovedate;
	var $skillpoints;
	var $skillrank;
	var $ladder;
	var $worldrank;
	var $ladderpoints;
	var $nationrank;
	var $homezonerank;
	var $nation;
	var $homezone;
	var $nationshort;
	var $nationiconURL;
	var $nationflagURL;
	var $homezoneflagURL;
	var $tags;
	var $tags1;
	var $tags2;
	private $zones;
	private $config;
	private $dbnick;
	private $errorCode;
	private $inDB;
	private $updated;
	private $curlAvailable, $openSSLavailable, $allowURLfopen;



	function TMFDataFetcher($login){
		$this->config = TMFDataFetcherConfig::getInstance();
		$this->dbnick = false;
		$this->checkForModules();
		$this->login = $login;
		$this->inDB = false;
		$this->errorCode = -1;

		if (!mysql_connect($this->config->mysqlServer, $this->config->mysqlLogin, $this->config->mysqlPassword)){
			$this->errorCode = -3;
			return;
		}
		if (!mysql_select_db($this->config->mysqlDatabase)) {
			$this->errorCode = -4;
			return;
		}
		$this->installTables();
		$this->fetchData();
		if (!$this->errorOccured())	$this->saveDB();
		mysql_close();
	}

	function installTables(){
		$query = "CREATE TABLE IF NOT EXISTS `tmfdata_ladder` (
				  `id` int(11) NOT NULL auto_increment,
				  `idplayer` int(11) NOT NULL,
				  `environment` text NOT NULL,
				  `wins` int(11) NOT NULL,
				  `draws` int(11) NOT NULL,
				  `losses` int(11) NOT NULL,
				  `ladderpoints` float NOT NULL,
				  PRIMARY KEY  (`id`)
				  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		mysql_query($query);
		echo mysql_error();

		$query = "CREATE TABLE IF NOT EXISTS `tmfdata_players` (
		          `id` int(11) NOT NULL,
		          `login` text collate utf8_unicode_ci NOT NULL,
		          `nickname` text collate utf8_unicode_ci NOT NULL,
		          `freeaccount` tinyint(1) NOT NULL,
		          `idzone` int(11) NOT NULL,
		          `lastmovedate` int(11) NOT NULL,
		          `skillpoints` int(11) NOT NULL,
		          `skillrank` int(11) NOT NULL,
		          `lastupdate` int(11) NOT NULL,
		          PRIMARY KEY  (`id`)
		          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($query);
		echo mysql_error();
		
		if (false === mysql_query("select `tags`, `tags1`, `tags2` from tmfdata_players limit 0")) {
		$query = "ALTER TABLE `tmfdata_players` ADD  `tags` TEXT NOT NULL AFTER  `lastupdate` ,
		ADD  `tags1` TEXT NOT NULL AFTER  `tags` ,
		ADD  `tags2` TEXT NOT NULL AFTER  `tags1`"; 
		mysql_query($query) or die(mysql_error());


		$query = "CREATE TABLE IF NOT EXISTS `tmfdata_ranking` (
				  `id` int(11) NOT NULL auto_increment,
				  `idzone` int(11) NOT NULL,
				  `rank` int(11) NOT NULL,
				  `idladder` int(11) NOT NULL,
				  PRIMARY KEY  (`id`)
				  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($query);
		echo mysql_error();

		$query = "CREATE TABLE IF NOT EXISTS `tmfdata_zones` (
				  `id` int(11) NOT NULL,
				  `zonepath` text NOT NULL,
				  PRIMARY KEY  (`id`)
				  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		mysql_query($query);
		echo mysql_error();
		

	}
}

	function checkForModules(){
		ob_start(); // Stop output of the code and hold in buffer
		phpinfo(); // get loaded modules and their respective settings.
		$phpinfo = ob_get_contents(); // Get the buffer contents and store in $data variable
		ob_end_clean(); // Clear buffer
		$this->curlAvailable = false;
		$this->openSSLavailable = false;
		$this->allowURLfopen = false;

		$data = $phpinfo;
		if (strstr($data, 'module_curl')){
			$data = substr($data, strpos($data, 'module_curl'));
			$data = substr($data, 0, strpos($data, '<h2>'));
			if (strstr($data, 'enabled')) $this->curlAvailable = true;
		}


		$data = $phpinfo;
		if (strstr($data, 'module_openssl')){
			$data = substr($data, strpos($data, 'module_openssl'));
			$data = substr($data, 0, strpos($data, '<h2>'));
			if (strstr($data, 'enabled')) $this->openSSLavailable = true;
		}

		$data = $phpinfo;
		if (strstr($data, 'allow_url_fopen')){
			$data = substr ($data, strpos($data, 'allow_url_fopen')+34);
			$data = substr ($data, 0, strpos($data, '</td>'));
			if ($data == 'On') $this->allowURLfopen = true;
		}


	}

	function getPlayerID(){

		$query = 'SELECT id FROM tmfdata_players
		          WHERE login="'. $this->login.'"';
		$result = mysql_query($query);

		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_row($result);
			$return = $row[0];
			$this->inDB = true;
		}else{
			$url = "https://".$this->config->statsUser.":".$this->config->statsPassword."@stats.trackmaniaforever.com/pubGetPlayerId.php?login=".$this->login;
			$xmlRaw = $this->file_contents($url);
			$xml = simplexml_load_string($xmlRaw);
			if ($this->responseIsError($xml)) return;

			$return = intval($xml->r->c->idplayer);
		}
		mysql_free_result($result);
		return $return;
	}

	function tag($key, $value){
		return '<'.$key.'>'.$value.'</'.$key.'>';
	}

	function createXMLfromDB($dbdata){

		$xml='<?xml version="1.0" encoding="utf-8" ?><r><r><n>GetPlayerAllInfosById</n><c>';

		$xml.=
		$this->tag('nickname', htmlspecialchars($dbdata['nickname'])).
		$this->tag('freeaccount', $dbdata['freeaccount']).
		$this->tag('idzone', $dbdata['idzone']).
		$this->tag('zonepath', htmlspecialchars($this->zones[$dbdata['idzone']])).
		$this->tag('lastmovedate', $dbdata['lastmovedate']).
		$this->tag('skillpoints', $dbdata['skillpoints']).
		$this->tag('skillrank', $dbdata['skillrank']);
		

		$query = 'SELECT * FROM tmfdata_ladder
		          WHERE idplayer="'. $this->id.'"';
		$result = mysql_query($query);
		echo mysql_error();

		for ($i=0; $i<mysql_num_rows($result); $i++) {
			$row = mysql_fetch_assoc($result);
			$content =
			$this->tag('enviro', $row['environment']).
			$this->tag('wins', $row['wins']).
			$this->tag('draws', $row['draws']).
			$this->tag('losses', $row['losses']).
			$this->tag('ladderpoints', $row['ladderpoints']);

			$query = 'SELECT * FROM tmfdata_ranking
		          WHERE idladder="'. $row['id'].'"';
			$result2 = mysql_query($query);
			for ($a=0; $a<mysql_num_rows($result2); $a++) {
				$row2 = mysql_fetch_assoc($result2);
				$content2 =
				$this->tag('idzone', $row2['idzone']).
				$this->tag('zonepath', htmlspecialchars($this->zones[$row2['idzone']])).
				$this->tag('rank', $row2['rank']);

				$content.=$this->tag('ranking', $content2);
			}

			$xml.=$this->tag('laddermulti', $content);
		}
		$xml.=$this->tag('tags', $this->tag('count', count(array($dbdata['tags'],$dbdata['tags1'],$dbdata['tags2'])))."\n".$this->tag('url', $dbdata['tags'])."\n".$this->tag('url', $dbdata['tags1'])."\n".$this->tag('url', $dbdata['tags2']));
		
		$xml.="</c></r></r>";
		return $xml;

	}

	function getXMLData(){

		$query = 'SELECT * FROM tmfdata_players
		          WHERE login="'. $this->login.'"';
		$result = mysql_query($query);

		$dbdata = false;

		if (mysql_num_rows($result) > 0) {
			$dbdata = mysql_fetch_assoc($result);
		}

		//get zones
		$query = 'SELECT * FROM tmfdata_zones';
		$result = mysql_query($query);
		$zones = array();
		for ($i=0; $i<mysql_num_rows($result); $i++) {
			$row = mysql_fetch_assoc($result);
			$zones[''.$row['id']] = $row['zonepath'];
		}
		$this->zones = $zones;

		$cachetime = 86400;
		$midnight = floor(time()/86400)*86400-3600;
		$secondssincemidnight = time()-$midnight;
		$nextupdate = floor($secondssincemidnight / $cachetime)*$cachetime + $midnight;

		$refreshStats = false;
		if ($dbdata){
			if ($this->config->refreshAfterMidnight){
				$refreshStats = $dbdata['lastupdate'] < $nextupdate;
			} else {
				$refreshStats = $dbdata['lastupdate']+$cachetime < time();
			}
		} else $refreshStats = true;

		if (!$refreshStats){

			$xmlRaw = $this->createXMLfromDB($dbdata);
			$xml = simplexml_load_string($xmlRaw);

			//create raw xml from database
		} else {
			$url = "https://".$this->config->statsUser.":".$this->config->statsPassword."@stats.trackmaniaforever.com/pubGetPlayerAllInfosById.php?idplayer=".$this->id;
			$xmlRaw = $this->file_contents($url);
			$xml = simplexml_load_string($xmlRaw);
			if ($this->responseIsError($xml)) return;
			$this->updated = true;
		}

		mysql_free_result($result);
		return $xml;
	}

	function fetchData(){

		if (!$this->openSSLavailable){
			$this->errorCode = -2;
			return;
		}

		$this->id = $this->getPlayerID();

		$xml = $this->getXMLData();
		if ($this->errorOccured()) return;

		$infos = $xml->r->c;

		$this->nickname = strval($infos->nickname);
		$this->freeaccount = intval($infos->freeaccount) != 0;
		if ($this->freeaccount) $this->accountType = 'Nations'; else $this->accountType = 'United';
		$this->idzone = intval($infos->idzone);
		$this->zonepath = strval($infos->zonepath);
		$this->maniastars = intval($infos->maniastars);
		$this->lastmovedate = intval($infos->lastmovedate);
		$this->skillpoints = intval($infos->skillpoints);
		$this->skillrank = intval($infos->skillrank);
		$ladder = array();
		foreach ($infos->laddermulti as $laddermulti){
			$ladder[] = $laddermulti;
		}
		$this->ladder = new Ladder($ladder, $this->freeaccount);
		$this->worldrank = $this->ladder->Merge->ranking[0]['rank'];
		$this->nationrank = $this->ladder->Merge->ranking[1]['rank'];
		$this->ladderpoints = $this->ladder->Merge->ladderpoints;
		$this->homezonerank = $this->ladder->Merge->getSpezificZoneRanking($this->zonepath);
		$this->homezonerank = $this->homezonerank['rank'];
		$this->nation = str_replace('World|', '', $this->zonepath);
		if (strstr($this->nation, '|')) $this->nation = substr($this->nation, 0, strpos($this->nation, '|'));
		$this->nationshort = $this->mapCountry($this->nation);
		$this->homezone = substr($this->zonepath, strrpos($this->zonepath, '|') + 1);
		$this->nationiconURL = 'http://tm.maniazones.com/images/flags/'.$this->getImageString($this->nation).'.gif';
		$this->nationflagURL = 'http://tm.maniazones.com/images/icons/leagues/'.$this->getImageString($this->nation, true).'.jpg';
		if ($this->nation != $this->homezone){
			$this->homezoneflagURL = 'http://flags.trackmaniaforever.com/static/'.$this->getImageString($this->homezone).'.jpg';
		} else {
			$this->homezoneflagURL = $this->nationflagURL;
		}
		$this->tags = strval($infos->tags->url[0]);
		$this->tags1 = strval($infos->tags->url[1]);
		$this->tags2 = strval($infos->tags->url[2]);
	}

	function saveDB(){
		if (!$this->updated) return;

		if ($this->inDB){
			//update
			$query =
			'UPDATE tmfdata_players SET
			login = "'.$this->login.'",
			nickname = "'.mysql_real_escape_string($this->nickname).'",
			idzone = "'.$this->idzone.'",
			lastmovedate = "'.$this->lastmovedate.'",
			skillpoints = "'.$this->skillpoints.'",
			skillrank = "'.$this->skillrank.'",
			lastupdate = "'.time().'",
			tags = "'.$this->tags.'",
			tags1 = "'.$this->tags1.'",
			tags2 = "'.$this->tags2.'" WHERE id = "'.$this->id.'" LIMIT 1;'; 

			mysql_query($query);
			echo mysql_error();

			//save laddermulti
			$ladder = $this->ladder->getFullArray();
			$zones = array();
			foreach ($ladder as $env){
				$query = 'SELECT id from tmfdata_ladder where (environment = "'.$env['environment'].'") AND (idplayer = "'.$this->id.'")';
				$result = mysql_query($query);
				echo mysql_error();

				if (mysql_num_rows($result) > 0) {
					$row = mysql_fetch_row($result);
					$ladderid = $row[0];
					$query = 'UPDATE tmfdata_ladder SET
					wins = "'.$env['wins'].'", 
					draws = "'.$env['draws'].'",  
					losses = "'.$env['losses'].'", 
					ladderpoints = "'.$env['ladderpoints'].'" WHERE id = "'.$ladderid.'";';
					mysql_query($query);
					echo mysql_error();

					if (count($env['ranking'])>0){
						foreach ($env['ranking'] as $rank){
							$query = 'UPDATE tmfdata_ranking SET rank = "'.$rank['rank'].'" WHERE idladder="'.$ladderid.'" AND idzone="'.$rank['idzone'].'"';
							mysql_query($query);
							echo mysql_error();

							if ($env['environment']=='Merge'){
								$entry = array();
								$entry['id'] = $rank['idzone'];
								$entry['zonepath'] = $rank['zonepath'];
								$zones[] = $entry;
							}
						}
					}

				}


			}



		} else {
			//save
			$query = '
INSERT INTO tmfdata_players 
			(id, login, nickname, freeaccount, idzone, lastmovedate, skillpoints, skillrank, lastupdate, tags, tags1, tags2) 
			VALUES ("'.$this->id.'", "'.$this->login.'", "'.mysql_real_escape_string($this->nickname).'", "'.$this->freeaccount.'", "'.$this->idzone.'", "'.$this->lastmovedate.'", "'.$this->skillpoints.'", "'.$this->skillrank.'", "'.time().'", "'.mysql_real_escape_string($this->tags).'", "'.mysql_real_escape_string($this->tags1).'", "'.mysql_real_escape_string($this->tags2).'");';
			mysql_query($query);
			echo mysql_error();

			//save laddermulti
			$ladder = $this->ladder->getFullArray();
			$zones = array();
			foreach ($ladder as $env){
				$query = 'INSERT INTO tmfdata_ladder (idplayer, environment, wins, draws, losses, ladderpoints)	VALUES
				("'.$this->id.'", "'.$env['environment'].'", "'.$env['wins'].'", "'.$env['draws'].'", "'.$env['losses'].'", "'.$env['ladderpoints'].'")';
				mysql_query($query);
				echo mysql_error();
				$insertid = mysql_insert_id();

				if (count($env['ranking'])>0){
					$query = 'INSERT INTO tmfdata_ranking (idladder, idzone, rank) VALUES ';
					foreach ($env['ranking'] as $rank){
						$query.='("'.$insertid.'", "'.$rank['idzone'].'", "'.$rank['rank'].'"), ';
						if ($env['environment']=='Merge'){
							$entry = array();
							$entry['id'] = $rank['idzone'];
							$entry['zonepath'] = $rank['zonepath'];
							$zones[] = $entry;
						}
					}
					$query = substr($query, 0, strlen($query)-2);
					mysql_query($query);
					echo mysql_error();
				}
			}

		}

		//save zones
		foreach ($zones as $zone){

			$query = 'SELECT * FROM tmfdata_zones WHERE id="'.$zone['id'].'"';
			$result = mysql_query($query);
			if (mysql_num_rows($result) > 0) {
				$dbdata = mysql_fetch_assoc($result);
				if ($dbdata['zonepath'] != $zone['zonepath']){
					$query = 'UPDATE tmfdata_zones SET zonepath = "'.$zone['zonepath'].'" WHERE id="'.$zone['id'].'"';
					mysql_query($query);
					echo mysql_error();
				}
			} else {
				$query = 'INSERT INTO tmfdata_zones (id, zonepath) VALUES ("'.$zone['id'].'", "'.$zone['zonepath'].'")';
				mysql_query($query);
				echo mysql_error();
			}

		}


	}

	function getImageString($str, $replaceSpaces = false){
		$str = utf8_decode($str);
		$str = str_replace( array ('ä', 'á', 'ŕ', 'â'), 'a', $str);
		$str = str_replace( array ('Ä', 'Á', 'Ŕ', 'Â'), 'A', $str);
		$str = str_replace( array ('é', 'č', 'ę'), 'e', $str);
		$str = str_replace( array ('É', 'Č', 'Ę'), 'E', $str);
		$str = str_replace( array ('í', 'ě', 'î'), 'i', $str);
		$str = str_replace( array ('Í', 'Ě', 'Î'), 'I', $str);
		$str = str_replace( array ('ö', 'ó', 'ň', 'ô'), 'o', $str);
		$str = str_replace( array ('Ö', 'Ó', 'Ň', 'Ô'), 'O', $str);
		$str = str_replace( array ('ü', 'ú', 'ů', 'ű'), 'u', $str);
		$str = str_replace( array ('Ü', 'Ú', 'Ů', 'Ű'), 'U', $str);
		$str = str_replace( ' (', '_(', $str);
		if ($replaceSpaces) $str = str_replace(' ', '_', $str);
		return (htmlentities($str));

	}

	function responseIsError($xml){
		if (isset($xml->r->e->v)){
			$this->errorCode = intval($xml->r->e->v);
			return true;
		}
		return false;
	}

	function errorOccured(){
		return ($this->errorCode != -1);
	}

	function outputError(){
		$error = $this->getError();
		echo '<b>[<span style="color:#ff0000;">'.$error['code'].'</span>] Error occured:</b> '.$error['msg'];
	}

	function getError(){

		$error = array();
		$error['code'] = $this->errorCode;
		$error[0] = $error['code'];
		switch ($this->errorCode){
			case -8:  $errstr = 'HTTPS Authorization failed: Your login or password seem to be invalid. If you used capitals in the login, try writing it all lower case.'; break;
			case -7:  $errstr = 'Neither the cURL module nor URL file access are enabled on your server.'; break;
			case -6:  $errstr = 'The cURL module is not enabled on your server.'; break;
			case -5:  $errstr = 'URL file access is not enabled on your server ("allow_url_fopen"). Enable it or try the cURL module.'; break;
			case -4:  $errstr = 'Database "'.$this->config->mysqlDatabase.'" not found. Please create it.'; break;
			case -3:  $errstr = 'Could not connect to MySQL server. Please check your configuration file.'; break;
			case -2:  $errstr = 'You need to install and enable the OpenSSL php extension.'; break;
			case 7:   $errstr = 'Invalid "login" parameter.'; break;
			case 14:  $errstr = 'Player unknown.'; break;
			case 24:  $errstr = 'Challenge unknown.'; break;
			case 26:  $errstr = 'Invalid "idchallenge" parameter.'; break;
			case 29:  $errstr = 'Invalid "ccode" parameter.'; break;
			case 41:  $errstr = 'Parent zone unknown.'; break;
			case 57:  $errstr = 'Environment unknown.'; break;
			case 62:  $errstr = 'Invalid "parentzonepath" parameter.'; break;
			case 82:  $errstr = 'Code unknown.'; break;
			case 125: $errstr = 'Permission denied. You cannot use this request or you call it too often.'; break;
			case 157: $errstr = 'There is a missing parameter.'; break;
			case 154: $errstr = 'Wrong community code.';
			case 161: $errstr = 'Invalid "first" parameter.'; break;
			case 162: $errstr = 'Invalid "count" parameter.'; break;
			default:  $errstr = 'No error occured.'; break;
		}
		$error['msg'] = $errstr;
		$error[1] = $errstr;
		return $error;
	}

	/**
	 * Ripped out of XAseco, thanks to Xymph for the effort =)
	 *
	 * @param unknown_type $country
	 * @return unknown
	 */
	function mapCountry($country) {

		$nations = array(
		'Afghanistan' => 'AFG',
		'Albania' => 'ALB',
		'Algeria' => 'ALG',
		'Andorra' => 'AND',
		'Angola' => 'ANG',
		'Argentina' => 'ARG',
		'Armenia' => 'ARM',
		'Aruba' => 'ARU',
		'Australia' => 'AUS',
		'Austria' => 'AUT',
		'Azerbaijan' => 'AZE',
		'Bahamas' => 'BAH',
		'Bahrain' => 'BRN',
		'Bangladesh' => 'BAN',
		'Barbados' => 'BAR',
		'Belarus' => 'BLR',
		'Belgium' => 'BEL',
		'Belize' => 'BIZ',
		'Benin' => 'BEN',
		'Bermuda' => 'BER',
		'Bhutan' => 'BHU',
		'Bolivia' => 'BOL',
		'Bosnia&Herzegovina' => 'BIH',
		'Botswana' => 'BOT',
		'Brazil' => 'BRA',
		'Brunei' => 'BRU',
		'Bulgaria' => 'BUL',
		'Burkina Faso' => 'BUR',
		'Burundi' => 'BDI',
		'Cambodia' => 'CAM',
		'Cameroon' => 'CAR',  // actually CMR
		'Canada' => 'CAN',
		'Cape Verde' => 'CPV',
		'Central African Republic' => 'CAF',
		'Chad' => 'CHA',
		'Chile' => 'CHI',
		'China' => 'CHN',
		'Chinese Taipei' => 'TPE',
		'Colombia' => 'COL',
		'Congo' => 'CGO',
		'Costa Rica' => 'CRC',
		'Croatia' => 'CRO',
		'Cuba' => 'CUB',
		'Cyprus' => 'CYP',
		'Czech Republic' => 'CZE',
		'Czech republic' => 'CZE',
		'DR Congo' => 'COD',
		'Denmark' => 'DEN',
		'Djibouti' => 'DJI',
		'Dominica' => 'DMA',
		'Dominican Republic' => 'DOM',
		'Ecuador' => 'ECU',
		'Egypt' => 'EGY',
		'El Salvador' => 'ESA',
		'Eritrea' => 'ERI',
		'Estonia' => 'EST',
		'Ethiopia' => 'ETH',
		'Fiji' => 'FIJ',
		'Finland' => 'FIN',
		'France' => 'FRA',
		'Gabon' => 'GAB',
		'Gambia' => 'GAM',
		'Georgia' => 'GEO',
		'Germany' => 'GER',
		'Ghana' => 'GHA',
		'Greece' => 'GRE',
		'Grenada' => 'GRN',
		'Guam' => 'GUM',
		'Guatemala' => 'GUA',
		'Guinea' => 'GUI',
		'Guinea-Bissau' => 'GBS',
		'Guyana' => 'GUY',
		'Haiti' => 'HAI',
		'Honduras' => 'HON',
		'Hong Kong' => 'HKG',
		'Hungary' => 'HUN',
		'Iceland' => 'ISL',
		'India' => 'IND',
		'Indonesia' => 'INA',
		'Iran' => 'IRI',
		'Iraq' => 'IRQ',
		'Ireland' => 'IRL',
		'Israel' => 'ISR',
		'Italy' => 'ITA',
		'Ivory Coast' => 'CIV',
		'Jamaica' => 'JAM',
		'Japan' => 'JPN',
		'Jordan' => 'JOR',
		'Kazakhstan' => 'KAZ',
		'Kenya' => 'KEN',
		'Kiribati' => 'KIR',
		'Korea' => 'KOR',
		'Kuwait' => 'KUW',
		'Kyrgyzstan' => 'KGZ',
		'Laos' => 'LAO',
		'Latvia' => 'LAT',
		'Lebanon' => 'LIB',
		'Lesotho' => 'LES',
		'Liberia' => 'LBR',
		'Libya' => 'LBA',
		'Liechtenstein' => 'LIE',
		'Lithuania' => 'LTU',
		'Luxembourg' => 'LUX',
		'Macedonia' => 'MKD',
		'Malawi' => 'MAW',
		'Malaysia' => 'MAS',
		'Mali' => 'MLI',
		'Malta' => 'MLT',
		'Mauritania' => 'MTN',
		'Mauritius' => 'MRI',
		'Mexico' => 'MEX',
		'Moldova' => 'MDA',
		'Monaco' => 'MON',
		'Mongolia' => 'MGL',
		'Montenegro' => 'MNE',
		'Morocco' => 'MAR',
		'Mozambique' => 'MOZ',
		'Myanmar' => 'MYA',
		'Namibia' => 'NAM',
		'Nauru' => 'NRU',
		'Nepal' => 'NEP',
		'Netherlands' => 'NED',
		'New Zealand' => 'NZL',
		'Nicaragua' => 'NCA',
		'Niger' => 'NIG',
		'Nigeria' => 'NGR',
		'Norway' => 'NOR',
		'Oman' => 'OMA',
		'Other Countries' => 'OTH',
		'Pakistan' => 'PAK',
		'Palau' => 'PLW',
		'Palestine' => 'PLE',
		'Panama' => 'PAN',
		'Paraguay' => 'PAR',
		'Peru' => 'PER',
		'Philippines' => 'PHI',
		'Poland' => 'POL',
		'Portugal' => 'POR',
		'Puerto Rico' => 'PUR',
		'Qatar' => 'QAT',
		'Romania' => 'ROM',  // actually ROU
		'Russia' => 'RUS',
		'Rwanda' => 'RWA',
		'Samoa' => 'SAM',
		'San Marino' => 'SMR',
		'Saudi Arabia' => 'KSA',
		'Senegal' => 'SEN',
		'Serbia' => 'SCG',  // actually SRB
		'Sierra Leone' => 'SLE',
		'Singapore' => 'SIN',
		'Slovakia' => 'SVK',
		'Slovenia' => 'SLO',
		'Somalia' => 'SOM',
		'South Africa' => 'RSA',
		'Spain' => 'ESP',
		'Sri Lanka' => 'SRI',
		'Sudan' => 'SUD',
		'Suriname' => 'SUR',
		'Swaziland' => 'SWZ',
		'Sweden' => 'SWE',
		'Switzerland' => 'SUI',
		'Syria' => 'SYR',
		'Taiwan' => 'TWN',
		'Tajikistan' => 'TJK',
		'Tanzania' => 'TAN',
		'Thailand' => 'THA',
		'Togo' => 'TOG',
		'Tonga' => 'TGA',
		'Trinidad and Tobago' => 'TRI',
		'Tunisia' => 'TUN',
		'Turkey' => 'TUR',
		'Turkmenistan' => 'TKM',
		'Tuvalu' => 'TUV',
		'Uganda' => 'UGA',
		'Ukraine' => 'UKR',
		'United Arab Emirates' => 'UAE',
		'United Kingdom' => 'GBR',
		'United States of America' => 'USA',
		'Uruguay' => 'URU',
		'Uzbekistan' => 'UZB',
		'Vanuatu' => 'VAN',
		'Venezuela' => 'VEN',
		'Vietnam' => 'VIE',
		'Yemen' => 'YEM',
		'Zambia' => 'ZAM',
		'Zimbabwe' => 'ZIM',
		);

		if (array_key_exists($country, $nations)) {
			$nation = $nations[$country];
		} else {
			$nation = "OTH";
		}
		return $nation;
	}


	function file_contents($url){

		if ($this->curlAvailable && !($this->config->fetchMethod==2)){

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result =curl_exec($ch);
			curl_close ($ch);
			if (stristr($result, '<!DOCTYPE HTML')) $this->errorCode = -8; else return $result;

		} else {
			if ($this->allowURLfopen && !($this->config->fetchMethod==1)){
				$page = @file_get_contents($url);
				if (!$page) $this->errorCode = -8; else return $page;
			} else {
				switch ($this->config->fetchMethod){
					case 1: $this->errorCode = -6; break;
					case 2: $this->errorCode = -5; break;
					default: $this->errorCode = -7; break;
				}
			}

		}

	}
}

class TMFDataFetcherConfig{
	static private $instance = NULL;
	var $statsUser, $statsPassword, $mysqlServer, $mysqlLogin, $mysqlPassword, $mysqlDatabase, $fetchMethod, $refreshAfterMidnight;
	static public function getInstance(){
		if (self::$instance === NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	function init($settings){
		$this->statsUser = $settings['stats_user'];
		$this->statsPassword = $settings['stats_password'];
		$this->mysqlServer = $settings['mysql_server'];
		$this->mysqlLogin = $settings['mysql_login'];
		$this->mysqlPassword = $settings['mysql_password'];
		$this->mysqlDatabase = $settings['mysql_database'];
		$this->fetchMethod = $settings['fetch_method'];
		$this->refreshAfterMidnight = $settings['refresh_after_midnight'];

	}

	private function __construct(){}
	private function __clone(){}
}

require_once('tmfdatafetcher.config.php');
$dfconfig = TMFDataFetcherConfig::getInstance();
$dfconfig->init($fetcherSettings);

?>