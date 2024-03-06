<?php
include('includes/tmx_get.php');
$tmxAuthFavo;
function bbtohtml($wandeln)
{
global $tmxAuthFavo;
include('includes/tmx_get.php');

$wandeln = preg_replace("#\[track=(.*?)\](.*?)\[/track\]#si", '<br></br><img src=http://'.$tmxAuthFavo.'.tm-exchange.com/main.aspx?action=trackscreensmall&id=\2 target="http://'.$tmxAuthFavo.'.tm-exchange.com/main.aspx?action=trackshow&id=\2"><br></br>http://'.$tmxAuthFavo.'.tm-exchange.com/main.aspx?action=trackshow&id=\2</img>', $wandeln);
    $wandeln = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", '<a href=\1 target="_blank">\2</a>', $wandeln);
   $wandeln = preg_replace("#\[url=(.*?)\](.*?)#si", '<a href=\1 target="_blank">\2</a>', $wandeln);
   $pattern = '#(^|[^\"=]{1})(http://|ftp://|mailto:|news:)([^\s<>]+)([\s\n<>]|$)#sm';
   $wandeln = preg_replace($pattern,"\\1<a href=\"\\2\\3\" target=http://tmnforever.tm-exchange.com/main.aspx?action=trackshow&id=\2><u>\\2\\3</u></a>\\4",$wandeln);
   $bbcodes = array("[b]", "[/b]","[u]", "[/u]","[i]", "[/i]");
   $htmlcodes = array("<b>", "</b>", "<u>", "</u>", "<i>", "</i>");
   // Added smileys in author comments ;)
$wandeln = str_replace(';)','<img src="http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/wink.gif" />',$wandeln);
$wandeln = str_replace(':D','<img src="http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/bigsmile.gif" />',$wandeln);
$wandeln = str_replace(':S','<img src="http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/confused.gif" />',$wandeln);
$wandeln = str_replace(':cool:','<img src="http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/cool.gif" />',$wandeln);
$wandeln = str_replace(':P','<img src="http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/tongue.gif" />',$wandeln);
$wandeln = str_replace(":'(",'<img src="http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/cry.gif" />',$wandeln);
$wandeln = str_replace(":(",'<img src="http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/sad.gif" />',$wandeln);
$wandeln = str_replace(":$",'http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/embarrassed.gif" />',$wandeln);
$wandeln = str_replace(":O",'http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/surprised.gif" />',$wandeln);
$wandeln = str_replace(":|",'http://'.$tmxAuthFavo.'.tm-exchange.com/smiles/stunned.gif" />',$wandeln);
   $wandeln = str_replace($bbcodes, $htmlcodes, $wandeln);



   return $wandeln;
}
if($tmx_info==0) {
   echo "<p class=\"tmxoffline\">".$lng_tmx_deactivated."</p>";
} elseif($tmx_info==1) {
$webserver[0]['name'] = $tmxAuthFavo;
$webserver[0]['url'] = 'http://'.$tmxAuthFavo.'.tm-exchange.com/apiget.aspx';
$webserver = pingSite($webserver);
foreach ($webserver as $key => $host)
{
   if(!$host['status']) {
    echo "<p class=\"tmxoffline\">".$lng_tmx_status."</p>";
   } else {

include("includes/tmx_get.php");
if ($recordlist== true){
   $MwTime = $recordlist[0]['time'];
   $minutes = floor($MwTime/(1000*60));
   $seconds = floor(($MwTime-$minutes*60*1000)/1000);
   $mseconds = substr($MwTime,strlen($MwTime)-3,2);
   $time=sprintf("%02d:%02d.%02d", $minutes, $seconds, $mseconds);
}
else
{
   $time = $lng_tmx_no_record;
}
?>
<div align="center"><br>
  <table width="100%" border="0" bgcolor="<?php echo $bg4 ?>" cellpadding="0" cellspacing="0">
    <tr bgcolor="#000000">
      <td height="25" colspan="5" class="tablehead-big"> <div align="center"><?php echo $lng_tmx_headline ?></div></td>
    </tr>
    <tr class="celltext">
      <td width="20%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_trackid ?></td>
      <td width="29%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['id'] ?></td>
      <td width="1%" height="25" rowspan="9" bgcolor="#000000">&nbsp;</td>
      <td height="25" colspan="2" rowspan="6" bgcolor="<?php echo $bg2 ?>">
        <div align="center"><img src="<?php echo $tmx['imgurlsmall'] ?>"></div></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_trackname ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['name'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_author ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['author'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_uploaded ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['uploaded'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_updated ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['updated'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_tracktype ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['type'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_route ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['routes'] ?></td>
      <td width="25%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_daytime ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['mood'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_length ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['length'] ?></td>
      <td width="25%" height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_difficulty ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['difficult'] ?></td>
    </tr>
    <tr>
      <td width="25%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_awards ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx_id['awards'] ?></td>
      <td width="25%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_record ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $time ?></td> 
    </tr>
   <tr bgcolor="#000000">
      <td height="25" colspan="5"> <div align="center"><font color="#CCCCCC"><a href="<?php echo $tmx['viewurl'] ?>" class="menufont"><?php echo $lng_tmx_viewurl ?></a></font></div></td>
    </tr>
   <tr bgcolor="#000000">
      <td height="25" colspan="5"> <div align="center"><font color="#CCCCCC"><a href="<?php echo $replayurl ?>" class="menufont"><?php echo $lng_tmx_replaydown ?></a></font></div></td>
    </tr>
    <tr bgcolor="#000000">
      <td height="25" colspan="5"> <div align="center"><font color="#CCCCCC"><a href="<?php echo $tmx['download'] ?>" class="menufont"><?php echo $lng_tmx_download ?></a></font></div></td>
    </tr>
    <tr bgcolor="<?php echo $bg1 ?>">
      <td height="25" colspan="5" class="celltext"><br>
        <?php echo $lng_tmx_comment ?><br> <table width="490" border="0" align="center">
          <tr>
            <td class="celltext"><?php echo bbtohtml($tmx['comment'])?></td>
          </tr>
        </table>
        <br> </td>
    </tr>
  </table>
</div>
<?php
   }
}
} elseif($tmx_info==2) {
include("includes/tmx_get.php");
if ($recordlist== true){
   $MwTime = $recordlist[0]['time'];
   $minutes = floor($MwTime/(1000*60));
   $seconds = floor(($MwTime-$minutes*60*1000)/1000);
   $mseconds = substr($MwTime,strlen($MwTime)-3,2);
   $time=sprintf("%02d:%02d.%02d", $minutes, $seconds, $mseconds);
}
else
{
   $time = $lng_tmx_no_record;
}
?>
<div align="center"><br>
  <table width="100%" border="0" bgcolor="<?php echo $bg4 ?>" cellpadding="0" cellspacing="0">
    <tr bgcolor="#000000">
      <td height="25" colspan="5" class="tablehead-big"> <div align="center"><?php echo $lng_tmx_headline ?></div></td>
    </tr>
    <tr class="celltext">
      <td width="20%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_trackid ?></td>
      <td width="29%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['id'] ?></td>
      <td width="1%" height="25" rowspan="9" bgcolor="#000000">&nbsp;</td>
      <td height="25" colspan="2" rowspan="6" bgcolor="<?php echo $bg2 ?>">
        <div align="center"><img src="<?php echo $tmx['imgurlsmall'] ?>"></div></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_trackname ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['name'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_author ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['author'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_uploaded ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['uploaded'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_updated ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['updated'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_tracktype ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['type'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_route ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['routes'] ?></td>
      <td width="25%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_daytime ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['mood'] ?></td>
    </tr>
    <tr>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_length ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['length'] ?></td>
      <td width="25%" height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_tmx_difficulty ?></td>
      <td height="25" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx['difficult'] ?></td>
    </tr>
    <tr>
      <td width="25%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_awards ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $tmx_id['awards'] ?></td>
      <td width="25%" height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-rb"><?php echo $lng_tmx_record ?></td>
      <td height="25" bgcolor="<?php echo $bg1 ?>" class="celltext-l">&nbsp;&nbsp;<?php echo $time ?></td> 
    </tr>
   <tr bgcolor="#000000">
      <td height="25" colspan="5"> <div align="center"><font color="#CCCCCC"><a href="<?php echo $tmx['viewurl'] ?>" class="menufont"><?php echo $lng_tmx_viewurl ?></a></font></div></td>
    </tr>
   <tr bgcolor="#000000">
      <td height="25" colspan="5"> <div align="center"><font color="#CCCCCC"><a href="<?php echo $replayurl ?>" class="menufont"><?php echo $lng_tmx_replaydown ?></a></font></div></td>
    </tr>
    <tr bgcolor="#000000">
      <td height="25" colspan="5"> <div align="center"><font color="#CCCCCC"><a href="<?php echo $tmx['download'] ?>" class="menufont"><?php echo $lng_tmx_download ?></a></font></div></td>
    </tr>
    <tr bgcolor="<?php echo $bg1 ?>">
      <td height="25" colspan="5" class="celltext"><br>
        <?php echo $lng_tmx_comment ?><br> <table width="490" border="0" align="center">
          <tr>
            <td class="celltext"><?php echo bbtohtml($tmx['comment'])?></td>
          </tr>
        </table>
        <br> </td>
    </tr>
  </table>
</div>
<?php
   }
?>