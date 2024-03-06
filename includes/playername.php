<?php
	$playername = preg_replace("`\\$(w|s|n|i|z|g|m|r|>|<|o|l|h)`","",$playername);
	$playername = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`","", $playername);
	$playername = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\-[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`","", $playername);
if($p_colour==0){
	$playername = preg_replace("`\\$[a-f0-9]{3}`","", $playername);
	$playername = preg_replace("`\\$[a-f0-9]{2}`","", $playername);
	$playername = preg_replace("`\\$[a-f0-9]{1}`","", $playername);
} else {
$playername = preg_replace("`\\$(w|s|n|i|z|g|>|<|o|l|h)`","",$playername);
$pnamei=0;
while($pnamei<=5){
   if (preg_match_all('/(\\$[a-f0-9 ]{3})([^\\$]+)/i',$playername,$match)) {
     $Replace = $match[0]; $Color = $match[1]; $Text = $match[2];

     for ($n = 0; $n < count($Replace); $n++) {
      $playername = str_replace($Replace[$n],'<span style="color:'.str_replace('$','#',$Color[$n]).'">'.$Text[$n].'</span>',$playername);
     }
   }
   $pnamei++;
}
$pnamei=0;
while($pnamei<=5){
   if (preg_match_all('/(\\$[a-f0-9]{1})([^\\$]+)/',$playername,$match)) {
     $Replace = $match[0]; $Color = $match[1]; $Text = $match[2];

     for ($n = 0; $n < count($Replace); $n++) {
      $playername = str_replace($Replace[$n],'<span style="color:'.str_replace('$','#',$Color[$n]).'">'.$Text[$n].'</span>',$playername);
     }
   }
$pnamei++;
}   
	$playername = preg_replace("`\\$[a-f0-9]{3}`","", $playername);
	$playername = preg_replace("`\\$[a-f0-9]{2}`","", $playername);
	$playername = preg_replace("`\\$[a-f0-9]{1}`","", $playername);
}
?>