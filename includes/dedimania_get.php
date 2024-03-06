<?php
$recstxt = file_get_contents('http://dedimania.net:8000/TMX?uid=' . $UId);
$recs = explode("\n",$recstxt);
foreach($recs as &$rec){
        $rec = explode(',',trim($rec));
}
?>