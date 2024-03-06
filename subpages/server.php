
<p class="tabletitle"><?php echo $lng_stats_s_headline ?></p>

<table width="100%" border="0" bgcolor="<?php echo $bg4 ?>" align="center">
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_s_version ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $Version['Version'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td width="25%" class="celltext-rb"><?php echo $lng_stats_s_build ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $Version['Build'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_s_game ?>&nbsp;</td>
    <td colspan="2" class="celltext-l">&nbsp;<?php echo $Version['Name'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td bgcolor="<?php echo $bg2 ?>" class="celltext-rb"><?php echo $lng_stats_s_status ?>&nbsp;</td>
    <td colspan="2" bgcolor="<?php echo $bg2 ?>" class="celltext-l">&nbsp;<?php echo $Status['Name'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_s_name ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $servername ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_s_comment ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $comment ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_s_players ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $ServerOptions['CurrentMaxPlayers'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_s_spectators ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $ServerOptions['CurrentMaxSpectators'] ?></td>
  </tr>
    <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_s_ladder ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php if ($ServerOptions['CurrentLadderMode'] = 1) {
    echo $lng_stats_p_off;
    } else {
    echo $lng_stats_p_ioff;
    }
    ?></td>
	</tr>
</table>
