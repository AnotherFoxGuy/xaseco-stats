<?php
$trackname = preg_replace("`\\$(w|s|n|i|z|g|>|<|o|l|h)`","",$trackname);
if($t_colour==0){
$trackname = preg_replace("`\\$[a-f0-9]{3}`","", $trackname);
$trackname = preg_replace("`\\$[a-f0-9]{2}`","", $trackname);
$trackname = preg_replace("`\\$[a-f0-9]{1}`","", $trackname);
} else {
$trackname = preg_replace("`\\$(w|s|n|i|z|g|>|<|o|l|h)`","",$trackname);
$tnamei=0;
while($tnamei<=5){
   if (preg_match_all('/(\\$[a-f0-9 ]{3})([^\\$]+)/i',$trackname,$match)) {
     $Replace = $match[0]; $Color = $match[1]; $Text = $match[2];

     for ($n = 0; $n < count($Replace); $n++) {
      $trackname = str_replace($Replace[$n],'<span style="color:'.str_replace('$','#',$Color[$n]).'">'.$Text[$n].'</span>',$trackname);
     }
   }
   $tnamei++;
}
$tnamei=0;
while($tnamei<=5){
   if (preg_match_all('/(\\$[a-f0-9]{1})([^\\$]+)/',$trackname,$match)) {
     $Replace = $match[0]; $Color = $match[1]; $Text = $match[2];

     for ($n = 0; $n < count($Replace); $n++) {
      $trackname = str_replace($Replace[$n],'<span style="color:'.str_replace('$','#',$Color[$n]).'">'.$Text[$n].'</span>',$trackname);
     }
   }
$tnamei++;
}   
$trackname = preg_replace("`\\$[a-f0-9]{3}`","", $trackname);
$trackname = preg_replace("`\\$[a-f0-9]{2}`","", $trackname);
$trackname = preg_replace("`\\$[a-f0-9]{1}`","", $trackname);
}
?>