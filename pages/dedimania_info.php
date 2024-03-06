<?php 
include('includes/dedimania_get.php'); 
/*print_r($recs, false); 
$recs[0][0];//uid 
 $recs[0][1];//envir 
$recs[0][2];//author 
$recs[0][3];//nb_times_played 
$recs[0][4];//name 
$recs[1][0];// [first] recordtime 
$recs[1][5];//[first] nickname 
$recs[1][6];//[first] servername 
$recs[2][0];// [2] recordtime 
$recs[2][5];//[2] nickname 
$recs[2][6];//[2] servername 
$recs[3][0];// [3] recordtime 
$recs[3][5];//[3] nickname 
$recs[3][6];//[3] servername 
$recs[4][0];// [4] recordtime 
$recs[4][5];//[4] nickname 
$recs[4][6];//[4] servername 
$recs[5][0];// [5] recordtime 
$recs[5][5];//[5] nickname 
$recs[5][6];//[5] servername 
$recs[6][0];// [6] recordtime 
$recs[6][5];//[6] nickname 
$recs[6][6];//[6] servername 
$recs[7][0];// [7] recordtime 
$recs[7][5];//[7] nickname 
$recs[7][6];//[7] servername 
$recs[8][0];// [8] recordtime 
$recs[8][5];//[8] nickname 
$recs[8][6];//[8] servername 
$recs[9][0];// [9] recordtime 
$recs[9][5];//[9] nickname 
$recs[9][6];//[9] servername 
$recs[10][0];// [10] recordtime 
$recs[10][5];//[10] nickname 
$recs[10][6];//[10] servername 
*/ 

$sql = "SELECT Name FROM challenges WHERE UId='$UId'";       
$result = mysql_query($sql);  
$data = mysql_fetch_array($result);  
$trackname = $cp->toHTML($data['Name']); 

print "<p class=\"tabletitle\">$lng_dedi_headline $trackname</p>";  

if (!$pos) $pos = 0;  
if (is_null($orderby)) $orderby = "Rank";  
if (is_null($sort)) $sort = "ASC";  

print "<table width=\"100%\" border=\"0\" bgcolor=\"$bg4\" align=\"center\">  
  <tr bgcolor=\"$resultbg\">  
    <td width=\"100\" class=\"tablehead\">".$lng_dedi_rank."</td>  
    <td width=\"300\" class=\"tablehead\">".$lng_dedi_nickname."</td>  
    <td width=\"100\" class=\"tablehead\">".$lng_dedi_time."</td>  
  </tr>"; 
   
  $bg = $bg1;  

$numrecs = 10; 

for($i = 1; $i <= $numrecs; $i++) { 
    $login = $recs[$i][3]; 
    $login = substr($login, 4); 
	$bg = ($bg == $bg1) ? $bg2 : $bg1; 
    echo "<tr bgcolor=".$bg." onMouseOver=\"this.style.backgroundColor='".$bg3."'\" onMouseOut=\"this.style.backgroundColor='".$bg."'\"><td class=\"celltext\">".$i."</td>";   
    echo "<td class=\"celltext\"><a href=http://www.dedimania.com/tmstats/?do=stat&Login=".$login."&Show=RECORDS target=\"_blank\"class=\"celltext\">".$cp->toHTML($recs[$i][5])."</a></td>"; 
    $score = $recs[$i][0]; 
    $minutes = floor($score/(1000*60));  
    $seconds = floor(($score-$minutes*60*1000)/1000);  
    $mseconds = substr($score,strlen($score)-3,2);  
    $time = sprintf("%02d:%02d.%02d", $minutes, $seconds, $mseconds);         
    echo "<td class=\"celltext\">".$time."</td>"; 
}    
?> 

</table> 
<br> 