<?php
if($Players){
echo "<p class=\"tabletitle\">".$lng_stats_p_headline."</p>
<table width=\"100%\" border=\"0\" align=\"center\" bgcolor=\"".$bg4."\">
  <tr bgcolor=\"".$resultbg."\" class=\"tablehead\">
          <td width=\"30%\" class=\"tablehead\">".$lng_stats_p_nick."</td>
          <td width=\"20%\" class=\"tablehead\">".$lng_stats_p_login."</td>
          <td width=\"20%\" class=\"tablehead\">".$lng_stats_p_ladder."</td>
          <td width=\"15%\" class=\"tablehead\">".$lng_stats_p_stat."</td>
          <td width=\"15%\" class=\"tablehead\">".$lng_stats_p_mode."</td>
        </tr>";
		foreach ($Players as $player) {
		$playername=$player['NickName'];
		//include("includes/playername.php");
		if($player['IsSpectator']==1){
			$playermode=$lng_stats_p_spec;
		} else {
			$playermode=$lng_stats_p_play;
		}
		if($player['IsInOfficialMode']==1){
			$playerstatus=$lng_stats_p_off;
		} else {
			$playerstatus=$lng_stats_p_ioff;
		}

		$bg = $bg1;
		if ($bg==$bg1) {
			$bg=$bg2;
		} else {
			$bg=$bg1;
		}

		echo "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\">
				<td class=\"celltext\">".$cp->toHTML($playername)."</td>
          		<td class=\"celltext\">".$player['Login']."</td>
          		<td class=\"celltext\">".$player['LadderRanking']."</td>
          		<td class=\"celltext\">".$playerstatus."</td>
          		<td class=\"celltext\">".$playermode."</td>
        	</tr>";
		}
		?>
</table>
<?php
}else {
 echo "<p class=\"tmxoffline\">".$lng_stats_p_status."</p>";
}
?>