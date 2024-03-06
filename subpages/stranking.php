<?php
if($Ranking){
echo "<p class=\"tabletitle\">".$lng_stats_r_headline."</p>
<table width=\"100%\" border=\"0\" align=\"center\" bgcolor=\"".$bg4."\" >
  <tr bgcolor=\"".$resultbg."\" class=\"tablehead\">
          <td width=\"15%\" class=\"tablehead\">".$lng_stats_r_rank."</td>
          <td width=\"35%\" class=\"tablehead\">".$lng_stats_r_nick."</td>
          <td width=\"25%\" class=\"tablehead\">".$lng_stats_r_login."</td>
          <td width=\"25%\" class=\"tablehead\">".$lng_stats_r_stbest."</td>
        </tr>";
		foreach ($Ranking as $player) {
		$playername=$player['NickName'];
		//include("includes/playername.php");
		$bg = $bg1;
		if ($bg==$bg1) {
		$bg=$bg2;
		} else {
		$bg=$bg1;
		}
		$MWTime = $player['Score'];
		$time=sprintf("%02d", $MWTime);
		$ranking=$player['Rank'];
		if ($time=="-1") {
			$time="not ranked";
			$ranking="---";
			}
		echo "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\">
          		<td class=\"celltext\">".$ranking."</td>
				<td class=\"celltext\">".$cp->toHTML($playername)."</td>
          		<td class=\"celltext\">".$player['Login']."</td>
          		<td class=\"celltext\">".$time."</td>
        	</tr>";
		}
		echo "</table>";
}else {
 echo "<p class=\"tmxoffline\">".$lng_stats_r_status."</p>";
}
?>