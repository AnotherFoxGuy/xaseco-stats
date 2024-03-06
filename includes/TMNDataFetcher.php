<?php
/* vim: set noexpandtab tabstop=2 softtabstop=2 shiftwidth=2: */

/**
 * TMNDataFetcher - Fetch TMN ladder/nation/server stats for a login
 * Created by (OoR-F)~fuckfish (fish@stabb.de)
 * Updated by Xymph <tmn@gamers.org>
 *
 * v1.4: Fixed get_file return value checks; fixed $nationrank check
 * v1.3: Optimized get_file URL parsing
 * v1.2: Added get_file function to handle master server timeouts
 * v1.1: General code cleanup; added more comments; added $lastmatch,
 *       $totalplayers, $nationplayers, $nationpos, $nationpoints,
 *       $totalnations, $servernick, $serverdesc, $servernation;
 *       renamed $actualserver to $serverlogin
 * v1.0: Initial release
 */
class TMNDataFetcher {

	var $version, $extended,
		$login, $nickname, $worldrank, $totalplayers,
		$points, $lastmatch, $wins, $losses, $draws,
		$stars, $stardays, $teamname, $teamrank, $totalteams,
		$nation, $nationrank, $nationplayers,
		$nationpos, $nationpoints, $totalnations,
		$online, $serverlogin, $servernick, $serverdesc, $servernation;

	/**
	 * Fetches a hell of a lot of data about a TMN login
	 *
	 * @param String $login
	 *        The TMN login to search for
	 * @param Boolean $extendedInfo
	 *        If true, the script also searches for the server that the
	 *        player is on at the moment (also determines online-state)
	 * @return TMNDataFetcher
	 *        If $nickname is empty, login was not found
	 */
	function TMNDataFetcher($login, $extendedInfo) {

		$this->version ='0.1.7.9';
		$this->extended = $extendedInfo;
		$this->login = strtolower($login);
		$this->getData();
	}  // TMNDataFetcher

	private function getData() {

		$line = $this->get_file('http://game.trackmanianations.com/online_game/getplayerinfos.php?ver=' . $this->version . '&lang=en&login=' . $this->login);
		if ($line === false || $line == -1)
			return false;

		$array = explode(';', $line);
		// 0 = $array[0];
		// login = $array[1];
		$this->nickname = urldecode($array[2]);
		$this->nation = $array[3];
		// empty = $array[4];
		$this->stars = $array[5];
		$this->stardays = $array[6];

		$line = $this->get_file('http://ladder.trackmanianations.com/ladder/getstats.php?ver=' . $this->version . '&laddertype=g&login=' . $this->login);
		if ($line === false || $line == -1)
			return false;

		$array = explode(';', $line);
		// 0 = $array[0];
		$this->totalplayers = $array[1];
		$this->wins = $array[2];
		$this->losses = $array[3];
		$this->draws = $array[4];
		$this->worldrank = $array[5];
		$this->points = $array[6];
		$this->lastmatch = $array[7];
		$this->teamname = urldecode($array[8]);
		$this->teamrank = $array[9];
		$this->totalteams = $array[10];

		$line = $this->get_file('http://ladder.trackmanianations.com/ladder/getstats.php?ver=' . $this->version . '&laddertype=g&login=' . $this->login . '&country=' . $this->nation);
		if ($line === false || $line == -1)
			return false;

		$array = explode(';', $line);
		// 0 = $array[1];
		if (isset($array[5]))
			$this->nationrank = $array[5];
		else
			$this->nationrank = '';
		// the remaining fields are the same as the world stats above

		$line = $this->get_file('http://ladder.trackmanianations.com/ladder/getrankings.php?ver=' . $this->version . '&laddertype=g&start=0&limit=0&country=' . $this->nation);
		if ($line === false || $line == -1)
			return false;

		$array = explode(';', $line);
		// 0 = $array[1];
		$this->nationplayers = $array[1];
		// 1;login;nickname;nation;points = $array[2-6];
		// 2;login;nickname;nation;points = $array[7-11];  etc.etc.

		$line = $this->get_file('http://ladder.trackmanianations.com/ladder/getcountriesrankings.php?ver=' . $this->version . '&laddertype=g&lang=en&start=0&limit=100');
		if ($line === false || $line == -1)
			return false;

		$array = explode(';', $line);

		// 0 = $array[1];
		$this->totalnations = $array[1];
		// 1;nation;points = $array[2-4];
		// 2;nation;points = $array[5-7];  etc.etc.
		$i = 2;
		while ($array[$i] != '') {
			if ($array[$i+1] == $this->nation) {
				$this->nationpos = $array[$i];
				$this->nationpoints = $array[$i+2];
				break;
			}
			$i += 3;
		}

		$this->online = false;
		// check online status too?
		if ($this->extended) {
			$page = $this->get_file('http://game.trackmanianations.com/online_game/www_serverslist.php');
			if ($line === false || $line == -1)
				return false;

			$lines = explode('<host>', $page);
			foreach ($lines as $line) {
				if (stripos($line, '<player>' . $this->login . '</player>') !== false) {
					$this->online = true;
					$this->serverlogin = substr($line, 0, strpos($line, '</host>'));
					break;
				}
			}

			if ($this->online) {
				$page = $this->get_file('http://game.trackmanianations.com/online_game/browse_top.php?ver=0.1.7.9&lang=en&key=XXXX-XXXX-XXXX-XXXX-XXX&nb=100&page=1&flatall=1');
				if ($line === false || $line == -1)
					return false;

				$server = $this->serverlogin;  // can't use object member inside pattern
				if (preg_match("/^${server};([^;]+);([^;]+);([A-Z]+);/m", $page, $fields)) {
					$this->serverdesc = $fields[1];
					$this->servernick = ($fields[2] != 'x' ? urldecode($fields[2]) : '');
					$this->servernation = $fields[3];
				}
			}
		}
	}  // getData

	// Simple HTTP Get function with timeout
	// ok: return string || error: return false || timeout: return -1
	private function get_file($url) {

		$url = parse_url($url);
		$port = isset($url['port']) ? $url['port'] : 80;
		$query = isset($url['query']) ? "?" . $url['query'] : "";

		$fp = @fsockopen($url['host'], $port, $errno, $errstr, 4);
		if (!$fp)
			return false;

		fwrite($fp, 'GET ' . $url['path'] . $query . " HTTP/1.0\r\n" .
		            'Host: ' . $url['host'] . "\r\n\r\n");
		stream_set_timeout($fp, 2);
		$res = '';
		$info['timed_out'] = false;
		while (!feof($fp) && !$info['timed_out']) {
			$res .= fread($fp, 512);
			$info = stream_get_meta_data($fp);
		}
		fclose($fp);

		if ($info['timed_out']) {
			return -1;
		} else {
			if (substr($res, 9, 3) != '200')
				return false;
			$page = explode("\r\n\r\n", $res, 2);
			return trim($page[1]);
		}
	}  // get_file
}  // class TMNDataFetcher
?>
