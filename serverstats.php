<?php
/*************************************************
*                                                *
*   Stats for XAseco+RASP                        *
*   __________________________________________   *
*                                                *
*   Copyright (c) 2007-2008 by oS.Cypher         *
*   Developed as a Project of Old School Gaming  *
*                                                *
*   http://www.os-gaming.de                      *
*                                                *
*************************************************/

require('config.php');
require('includes/globals.php');
include('languages/' . $lang . '.php');
require('includes/ColorParser.php');

$cp = new ColorParser();
$arial = 'fonts/tahoma';
$arial2 = 'fonts/arial.ttf';

$timeout = 5;
$fp = @fsockopen($ip2[$serv], $server_port[$serv], $errno, $errstr, $timeout);
if($fp) {
	fclose($fp);
	require('includes/GbxRemote.inc.php');
	$client = new IXR_Client_Gbx;
	if (!$client->InitWithIp($ip[$serv], $port[$serv])) {
   		die('An error occurred - '.$client->getErrorCode().':'.$client->getErrorMessage());
	}
	if (!$client->query('Authenticate', $user[$serv], $pass[$serv])) {
		Error($client->getErrorMessage(), $client->getErrorCode());
		print 'login failed !<br/>';
		return;
	}
	if($client->query('GetStatus')) {
		$Status = $client->getResponse();
	}
	if($client->query('GetPlayerList', 500, 0)) {
		$Players = $client->getResponse();
	}
	if($client->query('GetServerOptions')) {
		$ServerOptions = $client->getResponse();
		$sname_tmtags = $ServerOptions['Name'];
		$sname_stripped = stripFormatting($sname_tmtags);
//		$sname_stripped = preg_replace("`\\$(w|s|n|i|z|g|m|r|>|<|o|l|h)`","",$sname_stripped);
//		$sname_stripped = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`","", $sname_stripped);
//		$sname_stripped = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\-[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`","", $sname_stripped);
//		$sname_stripped = preg_replace("`\\$[a-f0-9]{3}`","", $sname_stripped);
//		$sname_stripped = preg_replace("`\\$[a-f0-9]{2}`","", $sname_stripped);
//		$sname_stripped = preg_replace("`\\$[a-f0-9]{1}`","", $sname_stripped);
	}

	if($client->query('GetCurrentChallengeInfo')) {
		$CurrentChallengeInfo = $client->getResponse();
	}
	$tname_tmtags = $CurrentChallengeInfo['Name'];
	$tname_stripped = stripFormatting($tname_tmtags);
//	$tname_stripped = preg_replace("`\\$(w|s|n|i|z|g|>|<|o|l|h)`","",$tname_stripped);
//	$tname_stripped = preg_replace("`\\$[a-f0-9]{3}`","", $tname_stripped);
//	$tname_stripped = preg_replace("`\\$[a-f0-9]{2}`","", $tname_stripped);
//	$tname_stripped = preg_replace("`\\$[a-f0-9]{1}`","", $tname_stripped);

	$pl = count($Players);
	$connectedplayers = $pl . ' / ' . $ServerOptions['CurrentMaxPlayers'];
	if($client->query('GetCurrentGameInfo')) {
		$GameInfo = $client->getResponse();
	}
	if($GameInfo['GameMode']==0) {
		$GameMode='Rounds';
	}elseif($GameInfo['GameMode']==1) {
		$GameMode='TimeAttack';
	}elseif($GameInfo['GameMode']==2) {
		$GameMode='Team';
	}elseif($GameInfo['GameMode']==3) {
		$GameMode='Laps';
	}elseif($GameInfo['GameMode']==4) {
		$GameMode='Stunts';
	}
}

if($statsbox == '1') {
	//create big Statusgraphic
	$im = ImageCreateFromPNG ('img/statsbox/serverstats.png'); // Open of the Master-Picture
	$white = ImageColorAllocate ($im, 255, 255, 255);  // white
	$black = ImageColorAllocate ($im, 0, 0, 0); // black
	ImageTTFText ($im, 8, 0, 3, 39, $white, $arial2, $lng_stats_s_name);
	$cp->drawStyledString($im, 8, 5, 55, $white, $arial, $sname_tmtags);
	//ImageTTFText ($im, 8, 0, 5, 55, $black, $arial2, $sname_stripped);
	ImageTTFText ($im, 8, 0, 3, 69, $white, $arial2, 'IP / Port');
	ImageTTFText ($im, 8, 0, 5, 85, $black, $arial2, $ip2[$serv] . ':' . $server_port[$serv]);
	ImageTTFText ($im, 8, 0, 3, 99, $white, $arial2, $lng_stats_g_mode);
	ImageTTFText ($im, 8, 0, 5, 115, $black, $arial2, $GameMode);
	ImageTTFText ($im, 8, 0, 3, 129, $white, $arial2, $lng_stats_c_track);
	$cp->drawStyledString($im, 8, 5, 145, $white, $arial, $tname_tmtags);
	//ImageTTFText ($im, 8, 0, 5, 145, $black, $arial2, $tname_stripped);
	ImageTTFText ($im, 8, 0, 3, 159, $white, $arial2, $lng_stats_players);
	ImageTTFText ($im, 8, 0, 5, 175, $black, $arial2, $connectedplayers);
	ImageTTFText ($im, 8, 0, 60, 159, $white, $arial2, $lng_stats_s_status);
	ImageTTFText ($im, 8, 0, 62, 175, $black, $arial2, $Status['Name']);
	Header ('Content-type: image/png');
	ImagePng ($im); // Als PNG ausgeben
	ImageDestroy ($im); // Resourcen freigeben
}

if($statsbox == '2') {
	//create small Statusgraphic
	$im = ImageCreateFromPNG ('img/statsbox/serverstats_small.png'); // Open of the Master-Picture
	$white = ImageColorAllocate ($im, 255, 255, 255); // white
	$black = ImageColorAllocate ($im, 0, 0, 0); // black
	ImageTTFText ($im, 8, 0, 30, 16, $black, $arial2, $sname_stripped);
	ImageTTFText ($im, 8, 0, 30, 37, $black, $arial2, $tname_stripped);
	ImageTTFText ($im, 8, 0, 30, 57, $black, $arial2, $connectedplayers . ' ' . $lng_title_players);
	Header ('Content-type: image/png');
	ImagePng ($im); // Als PNG ausgeben
	ImageDestroy ($im); // Resourcen freigeben
}

if($statsbox == '3') {
	//create World-TMN Statusgraphic
	$im = ImageCreateFromPNG ('img/statsbox/signature_server.png'); // Open of the Master-Picture
	$white = ImageColorAllocate ($im, 255, 255, 255);
	$textX = 21;
	$textY = 12;
	$textsize = 9;
	$lineheight = 17;
	$cp->drawStyledString($im, $textsize, $textY, $textX, $white, $arial, $sname_tmtags);
	//imagettftext($im, $textsize, 0, $textY, $textX, $white, $arial2, $sname_stripped);
	imagettftext($im, $textsize, 0, $textY, $textX+=$lineheight, $white, $arial2, 'IP/Port: ' . $ip2[$serv] . ':' . $server_port[$serv]);
	imagettftext($im, $textsize, 0, $textY, $textX+=$lineheight, $white, $arial2, 'Game mode: ' . $GameMode);
	imagettftext($im, $textsize, 0, $textY, $textX+=$lineheight, $white, $arial2, 'Challenge: ' . $tname_stripped);
	imagettftext($im, $textsize, 0, $textY, $textX+=$lineheight, $white, $arial2, 'Status: ' . $Status['Name']);
	Header ('Content-type: image/png');
	ImagePng ($im);
	ImageDestroy ($im);
}

/**
* Summary: Strips all display formatting from an input string, suitable for display
*          within the game ('$$' escape pairs are preserved) and for logging
* Params : $input - The input string to strip formatting from
*          $for_tm - Optional flag to double up '$' into '$$' (default, for TM) or not (for logs, etc)
* Returns: The content portions of $input without formatting
* Authors: Bilge/Assembler Maniac/Xymph/Slig
*/

function stripFormatting($input, $for_tm = true) {
    return
        //Replace all occurrences of a null character back with a pair of dollar
        //signs for displaying in TM, or a single dollar for log messages etc.
        str_replace("\0", ($for_tm ? '$$' : '$'),
            //Replace links (introduced in TMU)
            preg_replace(
                '/
                #Strip TMF H, L & P links by stripping everything between each square
                #bracket pair until another $H, $L or $P sequence (or EoS) is found;
                #this allows a $H to close a $L and vice versa, as does the game
                \\$[hlp](.*?)(?:\\[.*?\\](.*?))*(?:\\$[hlp]|$)
                /ixu',
                //Keep the second and third capturing groups if present
                '$1$2',
                //Replace various patterns beginning with an unescaped dollar
                preg_replace(
                    '/
                    #Match a single dollar sign and any of the following:
                    \\$
                    (?:
                        #Strip color codes by matching any hexadecimal character and
                        #any other two characters following it (except $)
                        [0-9a-f][^$][^$]
                        #Strip any incomplete color codes by matching any hexadecimal
                        #character followed by another character (except $)
                        |[0-9a-f][^$]
                        #Strip any single style code (including an invisible UTF8 char)
                        #that is not an H, L or P link or a bracket ($[ and $])
                        |[^][hlp]
                        #Strip the dollar sign if it is followed by [ or ], but do not
                        #strip the brackets themselves
                        |(?=[][])
                        #Strip the dollar sign if it is at the end of the string
                        |$
                    )
                    #Ignore alphabet case, ignore whitespace in pattern & use UTF-8 mode
                    /ixu',
                    //Replace any matches with nothing (i.e. strip matches)
                    '',
                    //Replace all occurrences of dollar sign pairs with a null character
                    str_replace('$$', "\0", $input)
                )
            )
        )
    ;
}


?>
