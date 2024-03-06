<?php
	$servername = preg_replace("`\\$(w|s|n|i|z|g|m|r|>|<|o|l|h)`","",$servername);
	$servername = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`","", $servername);
	$servername = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\-[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`","", $servername);
if($p_colour==0){
	$servername = preg_replace("`\\$[a-f0-9]{3}`","", $servername);
	$servername = preg_replace("`\\$[a-f0-9]{2}`","", $servername);
	$servername = preg_replace("`\\$[a-f0-9]{1}`","", $servername);
} else {
$servername = preg_replace("`\\$(w|s|n|i|z|g|>|<|o|l|h)`","",$servername);
$snamei=0;
while($snamei<=5){
   if (preg_match_all('/(\\$[a-f0-9 ]{3})([^\\$]+)/i',$servername,$match)) {
     $Replace = $match[0]; $Color = $match[1]; $Text = $match[2];

     for ($n = 0; $n < count($Replace); $n++) {
      $servername = str_replace($Replace[$n],'<span style="color:'.str_replace('$','#',$Color[$n]).'">'.$Text[$n].'</span>',$servername);
     }
   }
   $snamei++;
}
$snamei=0;
while($snamei<=5){
   if (preg_match_all('/(\\$[a-f0-9]{1})([^\\$]+)/',$servername,$match)) {
     $Replace = $match[0]; $Color = $match[1]; $Text = $match[2];

     for ($n = 0; $n < count($Replace); $n++) {
      $servername = str_replace($Replace[$n],'<span style="color:'.str_replace('$','#',$Color[$n]).'">'.$Text[$n].'</span>',$servername);
     }
   }
$snamei++;
}   
	$servername = preg_replace("`\\$[a-f0-9]{3}`","", $servername);
	$servername = preg_replace("`\\$[a-f0-9]{2}`","", $servername);
	$servername = preg_replace("`\\$[a-f0-9]{1}`","", $servername);
}
?>