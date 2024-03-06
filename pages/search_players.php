<?php 
require("config.php"); 

print "<p class=\"tabletitle\">$lng_players_headline</p>"; 

if (!$pos) $pos = 0; 
if (is_null($orderby)) $orderby = "rank"; 
if (is_null($sort)) $sort = "ASC"; 

$sorturl = "?serv=$serv&lang=$lang&page=$page&orderby=%s&sort=".(($sort == "ASC") ? "DESC" : "ASC"); 

print "<table width=\"100%\" border=\"0\" bgcolor=\"$bg4\" align=\"center\"> 
  <tr bgcolor=\"$resultbg\"> 
    <td width=\"60\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "rank")."\">$lng_players_rank</a></td> 
    <td width=\"150\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "NickName")."\">$lng_players_nickname</a></td> 
    <td width=\"150\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Login")."\">$lng_players_login</a></td> 
    <td width=\"80\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "rank")."\">$lng_players_avg</a></td> 
    <td width=\"120\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "TimePlayed")."\">$lng_players_timeplayed</a></td> 
    <td width=\"120\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "UpdatedAt")."\">$lng_players_lastvisit</a></td> 
    <td width=\"60\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Id")."\">$lng_players_id</a></td> 
    <td width=\"50\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Nation")."\">$lng_players_nation</a></td>"; 
    if ($show_player_banners) print "<td width=\"50\" class=\"tablehead\">$lng_players_banner</td>"; 
print "</tr>"; 

if(!$pos) $pos = 0; 

$sql = "SELECT * FROM ( 
            SELECT t1.*, @rownum:=@rownum+1 AS rank 
            FROM ( 
                SELECT p.*, r.avg, r.avg IS NULL AS norank 
                FROM players p 
                LEFT JOIN rs_rank r ON p.Id = r.playerID 
                ORDER BY norank ASC, r.avg ASC 
            ) t1, (SELECT @rownum:=0) t2 
        ) t3 
        WHERE Login LIKE '%$search%' OR NickName LIKE '%$search%'        
        LIMIT $pos, $count"; 
     
$result = mysql_query($sql); 
$found = mysql_num_rows(mysql_query("SELECT * 
FROM  `players` 
WHERE  `Login` LIKE  '%$search%' OR NickName LIKE '%$search%'"));
if ($found < 1) { 
    print "<tr><td class=\"celltext\" colspan=\"10\">No results</td></tr>"; 
} else { 
    $new_pos_next = $pos + $count; 
    $new_pos_prev = $pos - $count; 
    $link_next = ($new_pos_next >= $found) ? "" : "?search=$search&page=search_players&lang=$lang&serv=$serv&pos=$new_pos_next"; 
    $link_prev = ($new_pos_prev < 0) ? "" : "?search=$search&page=search_players&lang=$lang&serv=$serv&pos=$new_pos_prev"; 
}
$bg = $bg1; 
while($data = mysql_fetch_array($result)) 
{ 
    $MwTime = $data['TimePlayed']; 
    $lastvisited = $data['UpdatedAt']; 
    $hours = floor($MwTime/3600); 
    $minutes = floor(($MwTime/60-$hours*60)); 
    $seconds = substr($MwTime-$minutes*60-$hours*3600,-2); 
    $time = sprintf("%02dh%02dm%02ds", $hours, $minutes, $seconds); 
    if (!is_null($data['avg'])) { 
        $avg = sprintf ("%.1f", $data['avg']/10000); 
        $rank = $data['rank']; 
    } else { 
        $avg = "-"; 
        $rank = "-"; 
    } 

    $bg = ($bg == $bg1) ? $bg2 : $bg1; 
    if ($stunt[$serv]){ 
    echo "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\">"; 
    echo "<td class=\"celltext\">".$rank."</td>"; 
    echo "<td class=\"celltext\"><a href=\"?serv=".$serv."&lang=".$lang."&page=stprecords&plid=".$data['Id']."\" class=\"celltext\">".$cp->toHTML($data['NickName'])."</a></td></td>"; 
    echo "<td class=\"celltext\">".$data['Login']."</td>"; 
    echo "<td class=\"celltext\">".$avg."</td>"; 
    echo "<td class=\"celltext\">".$time."</td>"; 
    echo "<td class=\"celltext\">".$lastvisited."</td>"; 
    echo "<td class=\"celltext\">".$data['Id']."</td>"; 
    if($data['Nation']){ 
        echo "<td class=\"celltext\"><img src=\"img/flags/".$data['Nation'].".png\" alt=\"".$data['Nation']."\" title=\"".$data['Nation']."\"></td>"; 
    } else { 
        echo "<td class=\"celltext\"><img src=\"img/flags/UKN.png\" alt=\"Unknown\" title=\"Unknown\"></td>"; 
    } 
    if ($show_player_banners) print "<td class=\"celltext\"><a href=\"?serv=".$serv."&lang=".$lang."&page=playerstatsbox&plid=".$data['Id']."\"><img src=\"img/stats.gif\" border=\"0\"></a></td>"; 
    echo "</tr>"; 
    } else { 
    echo "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\">"; 
    echo "<td class=\"celltext\">".$rank."</td>"; 
    echo "<td class=\"celltext\"><a href=\"?serv=".$serv."&lang=".$lang."&page=precords&plid=".$data['Id']."\" class=\"celltext\">".$cp->toHTML($data['NickName'])."</a></td></td>"; 
    echo "<td class=\"celltext\">".$data['Login']."</td>"; 
    echo "<td class=\"celltext\">".$avg."</td>"; 
    echo "<td class=\"celltext\">".$time."</td>"; 
    echo "<td class=\"celltext\">".$lastvisited."</td>"; 
    echo "<td class=\"celltext\">".$data['Id']."</td>"; 
    if($data['Nation']){ 
        echo "<td class=\"celltext\"><img src=\"img/flags/".$data['Nation'].".png\" alt=\"".$data['Nation']."\" title=\"".$data['Nation']."\"></td>"; 
    } else { 
        echo "<td class=\"celltext\"><img src=\"img/flags/UKN.png\" alt=\"Unknown\" title=\"Unknown\"></td>"; 
    } 
    if ($show_player_banners) print "<td class=\"celltext\"><a href=\"?serv=".$serv."&lang=".$lang."&page=playerstatsbox&plid=".$data['Id']."\"><img src=\"img/stats.gif\" border=\"0\"></a></td>"; 
    echo "</tr>"; 
    } 
}  
?> 

</table> 
<br>