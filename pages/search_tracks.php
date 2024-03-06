<?php 
require("config.php"); 

print "<p class=\"tabletitle\">$lng_tracks_headline</p>"; 

if (!$pos) $pos = 0; 
if (is_null($orderby)) $orderby = "Id"; 
if (is_null($sort)) $sort = "ASC"; 

$sorturl = "?serv=$serv&lang=$lang&page=$page&orderby=%s&sort=".(($sort == "ASC") ? "DESC" : "ASC"); 

if ($stunt[$serv]){ 
print "<table width=\"100%\" border=\"0\" bgcolor=\"$bg4\" align=\"center\"> 
  <tr bgcolor=\"$resultbg\"> 
    <td width=\"80\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Id")."\">$lng_tracks_id</a></td> 
    <td width=\"150\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Name")."\">$lng_tracks_trackname</a></td> 
    <td width=\"150\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Author")."\">$lng_tracks_author</a></td> 
    <td width=\"100\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Environment")."\">$lng_tracks_envi</a></td> 
    <td width=\"50\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "votes")."\">$lng_tracks_votes</a></td> 
    <td width=\"50\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "karma")."\">$lng_tracks_karma</a></td>"; 
    if ($tmx_info) print "<td width=\"50\" class=\"tablehead\">".$lng_tracks_tmx."</td>"; 
    print "</tr>"; 
    } else { 
print "<table width=\"100%\" border=\"0\" bgcolor=\"$bg4\" align=\"center\"> 
  <tr bgcolor=\"$resultbg\"> 
    <td width=\"80\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Id")."\">$lng_tracks_id</a></td> 
    <td width=\"150\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Name")."\">$lng_tracks_trackname</a></td> 
    <td width=\"150\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Author")."\">$lng_tracks_author</a></td> 
    <td width=\"100\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "Environment")."\">$lng_tracks_envi</a></td> 
    <td width=\"50\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "votes")."\">$lng_tracks_votes</a></td> 
    <td width=\"50\" class=\"tablehead\"><a href=\"".sprintf($sorturl, "karma")."\">$lng_tracks_karma</a></td> 
    <td width=\"50\" class=\"tablehead\">".$lng_tracks_dedi."</a></td>"; 
    if ($tmx_info) print "<td width=\"50\" class=\"tablehead\">".$lng_tracks_tmx."</td>"; 
    print "</tr>"; 
    } 

if(!$pos) $pos = 0; 

$sql = "SELECT c.*, ( 
            SELECT COUNT(*) FROM rs_karma k WHERE k.ChallengeId = c.Id 
        ) votes, ( 
            SELECT COALESCE(SUM(Score),0) FROM rs_karma k WHERE k.ChallengeId = c.Id 
        ) karma 
        FROM challenges c 
        WHERE Name LIKE '%$search%' OR Author LIKE '%$search%' 
        ORDER BY Id ASC 
        LIMIT $pos, $count"; 

$result = mysql_query($sql); 
$found = mysql_num_rows(mysql_query("SELECT * 
FROM  `challenges` 
WHERE  `Name` LIKE  '%$search%' OR Author LIKE '%$search%'")); 
if ($found < 1) { 
    print "<tr><td class=\"celltext\" colspan=\"10\">No results</td></tr>"; 
} else { 
    $new_pos_next = $pos + $count; 
    $new_pos_prev = $pos - $count; 
    $link_next = ($new_pos_next >= $found) ? "" : "?search=$search&page=search_tracks&lang=$lang&serv=$serv&pos=$new_pos_next"; 
    $link_prev = ($new_pos_prev < 0) ? "" : "?search=$search&page=search_tracks&lang=$lang&serv=$serv&pos=$new_pos_prev"; 
}
$bg = $bg1; 
while($data = mysql_fetch_array($result)) 
{ 
    $bg = ($bg == $bg1) ? $bg2 : $bg1; 
if ($stunt[$serv]){ 
    print "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\"> 
            <td class=\"celltext\">".$data['Id']."</td> 
            <td class=\"celltext\"><a href=\"?serv=".$serv."&lang=".$lang."&page=sttrecords&trid=".$data['Id']."\" class=\"celltext\">".$cp->toHTML($data['Name'])."</a></td> 
            <td class=\"celltext\">".$data['Author']."</td> 
            <td class=\"celltext\">".$data['Environment']."</td> 
            <td class=\"celltext\">".$data['votes']."</td> 
            <td class=\"celltext\">".$data['karma']."</td>"; 
    if ($tmx_info) echo "<td class=\"celltext\"><a href=\"?serv=$serv&lang=".$lang."&page=tmx_info&UId=".$data['Uid']."\" class=\"celltext\">info</td>"; 
    print "</tr>"; 
    } else { 
    print "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\"> 
            <td class=\"celltext\">".$data['Id']."</td> 
            <td class=\"celltext\"><a href=\"?serv=".$serv."&lang=".$lang."&page=trecords&trid=".$data['Id']."\" class=\"celltext\">".$cp->toHTML($data['Name'])."</a></td> 
            <td class=\"celltext\">".$data['Author']."</td> 
            <td class=\"celltext\">".$data['Environment']."</td> 
            <td class=\"celltext\">".$data['votes']."</td> 
            <td class=\"celltext\">".$data['karma']."</td> 
            <td class=\"celltext\"><a href=\"?serv=$serv&lang=".$lang."&page=dedimania_info&UId=".$data['Uid']."\" class=\"celltext\">Dedi_info</td>"; 
    if ($tmx_info) echo "<td class=\"celltext\"><a href=\"?serv=$serv&lang=".$lang."&page=tmx_info&UId=".$data['Uid']."\" class=\"celltext\">info</td>"; 
    print "</tr>"; 
    } 
     
}  
?> 

</table> 
<br> 