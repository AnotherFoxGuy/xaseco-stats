<?php 

$sql = "SELECT Name FROM challenges WHERE Id=$trid"; 
$result = mysql_query($sql); 
$data = mysql_fetch_array($result); 
$trackname = $cp->toHTML($data['Name']); 

print "<p class=\"tabletitle\">$lng_trecords_headline $trackname</p>"; 

if (!$pos) $pos = 0; 
if (is_null($orderby)) $orderby = "Rank"; 
if (is_null($sort)) $sort = "ASC"; 

$sorturl = "?serv=$serv&lang=$lang&page=$page&trid=$trid&orderby=%s&sort=".(($sort == "ASC") ? "DESC" : "ASC"); 

print "<table width=\"100%\" border=\"0\" bgcolor=\"$bg4\" align=\"center\"> 
  <tr bgcolor=\"$resultbg\"> 
    <td width=\"100\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Rank")."\">$lng_trecords_id</a></td> 
    <td width=\"300\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "NickName")."\">$lng_trecords_player</a></td> 
    <td width=\"100\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Rank")."\">$lng_trecords_time</a></td> 
  </tr>"; 

$sql = "SELECT r.*, p.NickName FROM ( 
            SELECT *, @r:=@r+1 AS Rank 
            FROM ( 
                SELECT * 
                FROM records 
                WHERE ChallengeId=$trid 
                ORDER BY Score ASC 
            ) a, (SELECT @r:=0) t 
        ) r 
        LEFT JOIN players p 
        ON p.Id = r.PlayerId 
        ORDER BY $orderby $sort 
        LIMIT $pos, $count"; 

$result = mysql_query($sql); 
$totalrows = mysql_num_rows(mysql_query("SELECT * FROM records WHERE ChallengeId=$trid")); 

if ($totalrows < 1) { 
    print "<tr><td class=\"celltext\" colspan=\"10\">No records found</td></tr>"; 
} else { 
    $new_pos_next = $pos + $count; 
    $new_pos_prev = $pos - $count; 
    $link_next = ($new_pos_next >= $totalrows) ? "" : "?serv=$serv&lang=$lang&page=$page&trid=$trid&pos=$new_pos_next&orderby=$orderby&sort=$sort"; 
    $link_prev = ($new_pos_prev < 0) ? "" : "?serv=$serv&lang=$lang&page=$page&trid=$trid&pos=$new_pos_prev&orderby=$orderby&sort=$sort"; 

    $bg = $bg1; 
    while($data = mysql_fetch_array($result)) 
    { 
        $score = $data['Score']; 
        $minutes = floor($score/(1000*60)); 
        $seconds = floor(($score-$minutes*60*1000)/1000); 
        $mseconds = substr($score,strlen($score)-3,2); 
        $time = sprintf("%02d:%02d.%02d", $minutes, $seconds, $mseconds); 
        $bg = ($bg == $bg1) ? $bg2 : $bg1; 
        echo "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\"><td class=\"celltext\">".$data['Rank']."</td>"; 
        echo "<td class=\"celltext\"><a href=\"?serv=".$serv."&lang=".$lang."&page=precords&plid=".$data['PlayerId']."\" class=\"celltext\">".$cp->toHTML($data['NickName'])."</a></td>"; 
        echo "<td class=\"celltext\">".$time."</td>"; 

    } 
} 
?> 

</table>