<?php

$sql = "SELECT NickName FROM players WHERE Id = $plid";
$result = mysql_query($sql);
$data = mysql_fetch_array($result);
$playername = $data['NickName'];

print "<p class=\"tabletitle\">";
if ($show_player_banners)
   print "<img src=\"playerstats.php?serv=$serv&lang=$lang&plid=$plid&statsbox=5\" /><br /><br />";
print $lng_precords_headline." ".$cp->toHTML($playername);
print "</p>";

if (!$pos) $pos = 0;
if (is_null($orderby)) $orderby = "Score";
if (is_null($sort)) $sort = "DESC";

//$sorturl = "?serv=$serv&lang=$lang&page=$page&plid=$plid&orderby=%s&sort=".(($sort == "ASC") ? "DESC" : "ASC");
$sorturl1 = "?serv=$serv&lang=$lang&page=$page&plid=$plid&orderby=%s&sort=".(($sort == "DESC") ? "ASC" : "DESC");

print "<table width=\"100%\" border=\"0\" bgcolor=\"$bg4\" align=\"center\">
  <tr bgcolor=\"$resultbg\">
    <td width=\"100\" class=\"tablehead\"><a href=\"".sprintf($sorturl1, "Rank")."\">$lng_precords_id</a></td>
    <td width=\"300\" class=\"tablehead\"><a href=\"".sprintf($sorturl1, "Name")."\">$lng_precords_track</a></td>
    <td width=\"100\" class=\"tablehead\"><a href=\"".sprintf($sorturl1, "Score")."\">$lng_precords_score</a></td>
  </tr>";

$sql = "SELECT ChallengeId, Score, Rank, (
         SELECT Name FROM challenges c WHERE c.Id = r.ChallengeId
      ) Name
      FROM (
         SELECT a.*, IF(@p=ChallengeId, @r:=@r+1, @r:=1) AS Rank, @p:=ChallengeId
         FROM (
            SELECT PlayerId, ChallengeId, Score FROM records ORDER BY ChallengeId ASC, Score DESC
         ) a, (SELECT @r:=0, @p:=0) t
      ) r
      WHERE r.PlayerId = $plid
      ORDER BY $orderby $sort
      LIMIT $pos, $count";

$result = mysql_query($sql) or die(mysql_error());
$totalrows = mysql_num_rows(mysql_query("SELECT * FROM records WHERE PlayerID=$plid"));

if ($totalrows < 1) {
   print "<tr><td class=\"celltext\" colspan=\"10\">No records found</td></tr>";
} else {
   $new_pos_next = $pos + $count;
   $new_pos_prev = $pos - $count;
   $link_next = ($new_pos_next >= $totalrows) ? "" : "?serv=$serv&lang=$lang&page=$page&plid=$plid&pos=$new_pos_next&orderby=$orderby&sort=$sort";
   $link_prev = ($new_pos_prev < 0) ? "" : "?serv=$serv&lang=$lang&page=$page&plid=$plid&pos=$new_pos_prev&orderby=$orderby&sort=$sort";

   $bg = $bg1;
   while($data = mysql_fetch_array($result))
   {
      $score = $data['Score'];
        $time = sprintf("%02d", $score);
      
      $bg = ($bg == $bg1) ? $bg2 : $bg1;
      echo "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\"><td class=\"celltext\">".$data['Rank']."</td>";
      echo "<td class=\"celltext\"><a href=\"?serv=".$serv."&lang=".$lang."&page=sttrecords&trid=".$data['ChallengeId']."\" class=\"celltext\">".$cp->toHTML($data['Name'])."</a></td>";
      echo "<td class=\"celltext\">".$time."</td></tr>";
   }
}
?>

</table>