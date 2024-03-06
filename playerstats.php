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
require('includes/functions.php');
include('languages/' . $lang . '.php');
require('includes/ColorParser.php');
require('includes/tmfdatafetcher.inc.php');
include('includes/db_connect.php');

$cp = new ColorParser();
$arial = 'fonts/tahoma';
$arial2 = 'fonts/arial.ttf';

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

function stripColors($str, $for_tm_drawing = true)
{
    $str2 = str_replace('$', "\001", preg_replace("`[\001\002]`", '', 'a' . $str) );

    if($for_tm_drawing)
        $str2 = str_replace("\001\001", '$$', $str2);
    else
        $str2 = str_replace("\001\001", '$', $str2);

    $str2 = preg_replace("`\001[hlHL]`","\002",$str2);
    $str2 = preg_replace("`\002\[([^\]]*)\]([^\002]*)\002`","$2",$str2);
    $str2 = preg_replace("`\002\[([^\]]*)\]`","",$str2);
    $str2 = str_replace("\002", '', $str2);
    $str2 = str_replace('{/LINK}', '</a>', $str2);
    $str2 = preg_replace("`\001([0-9a-fA-F][0-9a-zA-Z][0-9a-zA-Z]|[^\001])`","",$str2);
    $str2 = str_replace("\001", '$$', substr($str2,1) );

    return $str2;
}

$timeout = 5;
$fp = @fsockopen($ip2[$serv], $server_port[$serv], $errno, $errstr, $timeout);
if($fp) {
    fclose($fp);
    require('includes/GbxRemote.inc.php');
    $client = new IXR_Client_Gbx;
    if (!$client->InitWithIp($ip[$serv], $port[$serv])) {
           die('An error occurred - ' . $client->getErrorCode() . ':' . $client->getErrorMessage());
    }
    if (!$client->query('Authenticate', $user[$serv], $pass[$serv])) {
        Error($client->getErrorMessage(), $client->getErrorCode());
        print 'login failed !<br/>';
    }
    if($client->query('GetServerOptions')) {
        $ServerOptions = $client->getResponse();
        $sname_tmtags = $ServerOptions['Name'];
        $sname_stripped = stripFormatting($sname_tmtags);
//        $sname_stripped = preg_replace("`\\$(w|s|n|i|z|g|m|r|>|<|o|l|h)`", "", $sname_stripped);
//        $sname_stripped = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`", "", $sname_stripped);
//        $sname_stripped = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\-[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`", "", $sname_stripped);
//        $sname_stripped = preg_replace("`\\$[a-f0-9]{3}`",  "", $sname_stripped);
//        $sname_stripped = preg_replace("`\\$[a-f0-9]{2}`","", $sname_stripped);
//        $sname_stripped = preg_replace("`\\$[a-f0-9]{1}`", "", $sname_stripped);
    }
}

$sql = 'SELECT * FROM rs_rank ORDER BY avg ASC';
$result = mysql_query($sql);
$rank = 1;

while($data = mysql_fetch_array($result)){
    if($data['playerID'] == $plid) {
        // Player is ranked
        $playerrank = $rank;
        $avg = $data['avg']/10000;
        $avg = sprintf('%.1f', $avg);
        break;
    } else {
        $rank++;
    }
}

if (!isset($playerrank)) {
    // Player is not ranked
    $playerrank = '-';
    $avg = '-';
}

$sql = 'SELECT * FROM players WHERE Id=' . $plid;
$result = mysql_query($sql);
$data = mysql_fetch_array($result);
$playerlogin = $data['Login'];
$fetcher = new TMFDataFetcher($playerlogin);
$pname_tmtags = $data['NickName'];
$pname_stripped = stripFormatting($pname_tmtags);
//$pname_stripped = preg_replace("`\\$(w|s|n|i|z|g|m|r|>|<|o|l|h)`", "", $pname_stripped);
//$pname_stripped = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`", "", $pname_stripped);
//$pname_stripped = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\-[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`", "", $pname_stripped);
//$pname_stripped = preg_replace("`\\$[a-f0-9]{3}`", "", $pname_stripped);
//$pname_stripped = preg_replace("`\\$[a-f0-9]{2}`", "", $pname_stripped);
//$pname_stripped = preg_replace("`\\$[a-f0-9]{1}`", "", $pname_stripped);
$nation = $data['Nation'];
$flag = ($nation ? 'img/flags/' . $nation . '.png' : 'img/flags/UKN.png');
$MwTime = $data['TimePlayed'];
$hours = floor($MwTime/3600);
$minutes = floor(($MwTime/60-$hours*60));
$seconds = substr($MwTime-$minutes*60-$hours*3600,-2);
$time = sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);

// STATS BANNERS
if($statsbox == 1) {
    //create small Statusgraphic
    $im = ImageCreateFromPNG ('img/statsbox/playerstats_big.png'); // Open of the Master-Picture
    $white = ImageColorAllocate ($im, 255, 255, 255);  // white
    $black = ImageColorAllocate ($im, 0, 0, 0); // black
    $cp->drawStyledString($im, 10, 85, 14, $white, $arial, $pname_tmtags . ' @ ' . $sname_tmtags);
    //imagettftext($im, 10, 0, 300, 14, $white, $arial2, '@ '.$sname_stripped);
    //$cp->drawStyledString($im, 10, 310, 14, $white, $arial, $sname_tmtags);
    imagettftext($im, 10, 0, 60, 34, $black, $arial2, $lng_stats_server . ' ' . $lng_trecords_id . ': ' . $playerrank . '    ' . $lng_players_avg . ': ' . $avg);
    imagettftext($im, 10, 0, 60, 54, $black, $arial2, $lng_players_timeplayed . ': ' . $time);
    $nationflag = imagecreatefrompng($flag);
    imagealphablending($im, 1);
    imagealphablending($nationflag, 1);
    imagecopy($im, $nationflag, 60,4,0,0,18,11);
    Header ('Content-type: image/png');
    ImagePng ($im); // Als PNG ausgeben
    ImageDestroy ($im); // Resourcen freigeben
}

if($statsbox == 2){
    //create small Statusgraphic
    $im = ImageCreateFromPNG ('img/statsbox/playerstats_middle.png'); // Open of the Master-Picture
    $white = ImageColorAllocate ($im, 255, 255, 255);  // white
    $black = ImageColorAllocate ($im, 0, 0, 0); // black
    imagettftext($im, 8, 0, 10, 12, $white, $arial2, $pname_stripped);
    imagettftext($im, 8, 0, 10, 25, $white, $arial2, $lng_stats_blank . ' @ ' . $sname_stripped);
    imagettftext($im, 8, 0, 5, 32, $white, $arial2, '--------------------------------------------------');
    imagettftext($im, 8, 0, 10, 42, $white, $arial2, $lng_stats_server . ' ' . $lng_trecords_id . ': ' . $playerrank);
    imagettftext($im, 8, 0, 10, 55, $white, $arial2, $lng_players_avg . ': ' . $avg);
    Header ('Content-type: image/png');
    ImagePng ($im); // Als PNG ausgeben
    ImageDestroy ($im); // Resourcen freigeben
}

if($statsbox == 3){
    //create small Statusgraphic
    $im = ImageCreateFromPNG ('img/statsbox/playerstats_small.png'); // Open of the Master-Picture
    $white = ImageColorAllocate ($im, 255, 255, 255);  // white
    $black = ImageColorAllocate ($im, 0, 0, 0); // black
    imagettftext($im, 7, 0, 110, 9, $white, $arial2, $pname_stripped . ' @ ' . $sname_stripped);
    imagettftext($im, 6, 0, 110, 18, $white, $arial2, $lng_stats_server . ' ' . $lng_trecords_id . ': ' . $playerrank .
					'  --  ' . $lng_players_avg . ': ' . $avg . '  --  ' . $lng_players_timeplayed . ': ' . $time);
    Header ('Content-type: image/png');
    ImagePng ($im); // Als PNG ausgeben
    ImageDestroy ($im); // Resourcen freigeben
}

if($statsbox == 4){
    //create World-TMN Statusgraphic
    $im = ImageCreateFromPNG ('img/statsbox/signature.png'); // Open of the Master-Picture
    $white = ImageColorAllocate ($im, 255, 255, 255);  // white
    $black = ImageColorAllocate ($im, 0, 0, 0); // black
    imagettftext($im, 10, 0, 29, 18, $white, $arial2, 'Nick: ' . $pname_stripped);
    imagettftext($im, 10, 0, 29, 33, $white, $arial2, 'Nationrank: ' . $fetcher->nationrank);
    imagettftext($im, 10, 0, 29, 48, $white, $arial2, 'Worldrank: ' . $fetcher->worldrank);
    $zonetext = 'Homezone: ' . $fetcher->homezone;
    $box = imagettfbbox(10, 0, $arial2, $zonetext);
    imagettftext($im, 10, 0, 29, 63, $white, $arial2, $zonetext);
    imagettftext($im, 10, 0, 29, 80, $white, $arial2, 'LP: ' . $fetcher->ladderpoints);
    imagettftext($im, 10, 0, 29, 95, $white, $arial2, $fetcher->nationshort);
    if ($homeflag = getZoneFlag($fetcher->homezoneflagURL, $fetcher->homezone))
        imagecopy($im, $homeflag, 34 + abs($box[2] - $box[0]), 53, 0, 0, 18, 12);
    if ($nationflag = getNationFlag($fetcher->nationiconURL, $fetcher->nationshort))
        imagecopy($im, $nationflag, 60, 85, 0, 0, 18, 12);
    Header ('Content-type: image/png');
    ImagePng ($im);
    ImageDestroy ($homeflag);
    ImageDestroy ($nationflag);
    ImageDestroy ($im);
}

if($statsbox == 5){
    $im = ImageCreateFromPNG ('img/statsbox/signature_player.png'); // Open of the Master-Picture
    $white = ImageColorAllocate ($im, 255, 255, 255);
//    $name = stripFormatting($fetcher->nickname);
    $textX = 21;
    $textY = 12;
    $textsize = 9;
    $lineheight = 17;
    imagettftext($im, $textsize, 0, $textY, $textX, $white, $arial2, 'Nickname: ' . $pname_stripped);
    imagettftext($im, $textsize, 0, $textY, $textX+=$lineheight, $white, $arial2, 'Nationrank: ' . $fetcher->nationrank);
    imagettftext($im, $textsize, 0, $textY, $textX+=$lineheight, $white, $arial2, 'Worldrank: ' . $fetcher->worldrank);
    imagettftext($im, $textsize, 0, $textY, $textX+=$lineheight, $white, $arial2, 'Ladderpoints: ' . $fetcher->ladderpoints);
    imagettftext($im, $textsize, 0, $textY, $textX+=$lineheight, $white, $arial2, 'Country: ' . $fetcher->nationshort);
    //imagettftext($im, $textsize, 0, 10, $textpos+=$lineheight, $white, $arial2, 'Zone: ' . $fetcher->homezone);
    if ($nationflag = $nationflag = getNationFlag($fetcher->nationiconURL, $fetcher->nationshort))
        imagecopy($im, $nationflag, 93, 78, 0, 0, 18, 12);
    //if ($homeflag = $homeflag = getZoneFlag($fetcher->homezoneflagURL, $fetcher->homezone))
    //    imagecopy($im, $homeflag, 118, 78, 0, 0, 12, 12);
    Header ('Content-type: image/png');
    ImagePng ($im);
    //ImageDestroy ($homeflag);
    ImageDestroy ($nationflag);
    ImageDestroy ($im);
}

?>
