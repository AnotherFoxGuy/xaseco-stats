<p class="tabletitle"><?php echo $lng_stats_c_headline ?></p>
<table width="100%" bgcolor="<?php echo $bg4 ?>" border="0" align="center">
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td width="25%" class="celltext-rb"><?php echo $lng_stats_c_track ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<a href="?serv=<?php echo $serv ?>&lang=<?php echo $lang ?>&page=tmx_info&UId=<?php echo $CurrentChallengeInfo['UId'] ?>" class="celltext"><?php echo $trackname ?></a></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_author ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $CurrentChallengeInfo['Author'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_envi ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $CurrentChallengeInfo['Environnement'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_mood ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $CurrentChallengeInfo['Mood'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_coppers ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $CurrentChallengeInfo['CopperPrice'] ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_statime ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $atimest ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_stgtime ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $gtimest ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_ststime ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $stimest ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg1." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg1."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_stbtime ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $btimest ?></td>
  </tr>
  <tr valign="middle" <?php echo "bgcolor=".$bg2." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg2."'" ?>">
    <td class="celltext-rb"><?php echo $lng_stats_c_cp ?>&nbsp;</td>
    <td class="celltext-l">&nbsp;<?php echo $CurrentChallengeInfo['NbCheckpoints'] ?></td>
  </tr>
</table>
