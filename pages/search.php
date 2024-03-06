
<p class="tabletitle"><?php echo $lng_title_search ?></p>

<table width="100%" border="0" cellspacing="15" cellpadding="0" bgcolor="<?php echo $bg4 ?>" align="center">
  <tr bgcolor=<?php echo $resultbg ?>>
    <td class="tablehead"><?php echo $lng_title_players ?></td>
    <td class="tablehead"><?php echo $lng_title_tracks ?></td>
  </tr>
  <tr bgcolor=<?php echo $bg1 ?>>
    <td width="50%" height="35" align="center">
		<form id="form1" name="form1" method="get" action="index.php">
			<input name="search" type="text" id="player" size="35" />&nbsp;
	        <input name="page" type="hidden" id="page" value="search_players" />
	        <input name="lang" type="hidden" id="lang" value="<?php echo $lang ?>" />
	        <input name="serv" type="hidden" id="serv" value="<?php echo $serv ?>" />
	        <input type="submit" name="do" id="do" value="<?php echo $lng_title_search ?>" />
        </form>
    </td>
    <td width="50%" height="35" align="center">
		<form id="form2" name="form2" method="get" action="index.php">
			<input name="search" type="text" id="player" size="35" />&nbsp;
	        <input name="page" type="hidden" id="page" value="search_tracks" />
	        <input name="lang" type="hidden" id="lang" value="<?php echo $lang ?>" />
	        <input name="serv" type="hidden" id="serv" value="<?php echo $serv ?>" />
	        <input type="submit" name="do" id="do" value="<?php echo $lng_title_search ?>" />
        </form>
    </td>
  </tr>
</table>