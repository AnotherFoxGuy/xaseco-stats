<p class="tabletitle"><?php echo $lng_stats_g_headline ?></p>
<table width="100%" bgcolor="<?php echo $bg4 ?>" border="0" align="center">
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td width="25%" class="celltext-rb"><?php echo $lng_stats_g_mode ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $GameMode ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_g_chattime ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $chattime ?> <?php echo $lng_stats_g_seconds ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_g_challenges ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $GameInfo['NbChallenge'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <?php
	if($GameMode=="TimeAttack") {
	  echo "<td class=\"celltext-rb\">".$lng_stats_g_tl."&nbsp;</td><td class=\"celltext-l\">&nbsp;".$GameInfo['TimeAttackLimit']/60000 ." minutes</td>";
	} elseif($GameMode=="Rounds") {
	  echo "<td class=\"celltext-rb\">".$lng_stats_g_rpl."&nbsp;</td><td class=\"celltext-l\">&nbsp;".$GameInfo['RoundsPointsLimit']."</td>";
	} elseif($GameMode=="Laps") {
	  echo "<td class=\"celltext-rb\">".$lng_stats_g_nol."&nbsp;</td><td class=\"celltext-l\">&nbsp;".$GameInfo['LapsNbLaps']."</td>";
	}
	?>
  </tr>
</table>