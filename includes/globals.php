<?php

$serv = isset($_GET['serv']) ? $_GET['serv'] : NULL;
$page = isset($_GET['page']) ? $_GET['page'] : "home";
$lang = isset($_GET['lang']) ? $_GET['lang'] : $def_language;
$plid = isset($_GET['plid']) ? $_GET['plid'] : NULL;
$trid = isset($_GET['trid']) ? $_GET['trid'] : NULL;
$pos = isset($_GET['pos']) ? $_GET['pos'] : NULL;
$orderby = isset($_GET['orderby']) ? $_GET['orderby'] : NULL;
$sort = isset($_GET['sort']) ? $_GET['sort'] : NULL;
$UId = isset($_GET['UId']) ? $_GET['UId'] : NULL;
$statsbox = isset($_GET['statsbox']) ? $_GET['statsbox'] : NULL;
$search = isset($_GET['search']) ? $_GET['search'] : NULL;
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : NULL;
$subladder_env = isset($_GET['subladder_env']) ? $_GET['subladder_env'] : NULL;

if(strstr($page, "..") || strstr($lang, "..") || strstr($search, "..")) die("bad boy");

?>