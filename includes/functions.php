<?php

function pingSite($webserver)
{
   foreach ($webserver as $key => $host) {
        $fp = curl_init($host['url']);
        curl_setopt($fp,CURLOPT_TIMEOUT,10);
        curl_setopt($fp,CURLOPT_FAILONERROR,1);
        curl_setopt($fp,CURLOPT_RETURNTRANSFER,1);
        curl_exec($fp);
        if (curl_errno($fp) != 0) {
            $webserver[$key]['status'] = false;
        } else {
            $webserver[$key]['status'] = true;
        }
        curl_close($fp);
    }
    return $webserver;
}

function getUrlPath()
{
	$url = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$url .= "s";}
	$url .= "://";
	$url .= ($_SERVER["SERVER_PORT"] == "80") ? $_SERVER["SERVER_NAME"] : $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	$url .= $_SERVER['SCRIPT_NAME'];
	$pathinfo = pathinfo($url);
	$url = $pathinfo['dirname']."/";
	return $url;
}

function getNationFlag($url, $nation = false)
{
	if (@fopen($url, "r")) {
		$img = imagecreatefromgif($url);
	} else if ($nation && @fopen($url = "img/flags/".$nation.".png", "r")) {
		$img = imagecreatefrompng($url);
	} else {
		//return false;
		//$img = imagecreatefrompng("img/flags/UKN.png");
		$img = imagecreatefrompng("img/flags/OTH.png");
	}
	return $img;
}

function getZoneFlag($url, $zone = 0)
{
	if (!@fopen($url, "r"))
	{
		$url = false;
		// additional urls if flag ($url) doesn't exist
		$links = array("img/flags/zone/%s.png",
						"img/flags/OTH.png",
						"img/flags/UKN.png");

		foreach ($links as $link) {
			if (@fopen($u = sprintf($link, $zone), "r") ||
				@fopen($u = sprintf($link, rawurlencode($zone)), "r")) {
					$url = $u;
					break;
			}
		}
		if (!$url) return false;
	}

	$width = 18;
	$height = 12;
	$img = imagecreatetruecolor($width, $height);
	list($w, $h, $type) = getimagesize($url);
	if ($type === 2) $flag = imagecreatefromjpeg($url); // jpg
	else if ($type === 3) $flag = imagecreatefrompng($url); // png
	else if ($type === 1) $flag = imagecreatefromgif($url); // gif
	else return false;
	imagecopyresampled($img, $flag, 0, 0, 0, 0, $width, $height, $w, $h);
	imagedestroy($flag);
	return $img;
}

?>