<?php
$karmasql="SELECT * FROM rs_karma WHERE ChallengeId=$challengeid";
$karmaresult=mysql_query($karmasql);
$k=0;
$v=0;
while($karmadata=mysql_fetch_array($karmaresult)){
	$k += $karmadata['Score'];
	$v++;
}
?>