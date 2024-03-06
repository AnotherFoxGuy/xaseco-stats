<?php
	$comment = preg_replace("`\\$(w|s|n|i|z|g|m|r|>|<|o|l|h)`","",$comment);
	$comment = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`","", $comment);
	$comment = preg_replace("`\\[[A-Za-z0-9]+\.[A-Za-z0-9]+\-[A-Za-z0-9]+\.[A-Za-z0-9]+\.[A-Za-z0-9]+\]`","", $comment);
if($p_colour==0){
	$comment = preg_replace("`\\$[a-f0-9]{3}`","", $comment);
	$comment = preg_replace("`\\$[a-f0-9]{2}`","", $comment);
	$comment = preg_replace("`\\$[a-f0-9]{1}`","", $comment);
} else {
$comment = preg_replace("`\\$(w|s|n|i|z|g|>|<|o|l|h)`","",$comment);
$i=0;
while($i<=5){
   if (preg_match_all('/(\\$[a-f0-9 ]{3})([^\\$]+)/i',$comment,$match)) {
     $Replace = $match[0]; $Color = $match[1]; $Text = $match[2];

     for ($n = 0; $n < count($Replace); $n++) {
      $comment = str_replace($Replace[$n],'<span style="color:'.str_replace('$','#',$Color[$n]).'">'.$Text[$n].'</span>',$comment);
     }
   }
   $i++;
}
$i=0;
while($i<=5){
   if (preg_match_all('/(\\$[a-f0-9]{1})([^\\$]+)/',$comment,$match)) {
     $Replace = $match[0]; $Color = $match[1]; $Text = $match[2];

     for ($n = 0; $n < count($Replace); $n++) {
      $comment = str_replace($Replace[$n],'<span style="color:'.str_replace('$','#',$Color[$n]).'">'.$Text[$n].'</span>',$comment);
     }
   }
$i++;
}   
	$comment = preg_replace("`\\$[a-f0-9]{3}`","", $comment);
	$comment = preg_replace("`\\$[a-f0-9]{2}`","", $comment);
	$comment = preg_replace("`\\$[a-f0-9]{1}`","", $comment);
}
?>