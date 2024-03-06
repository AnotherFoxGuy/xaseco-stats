<?php

$fetcherSettings = array();

$fetcherSettings['stats_user']     = '';
$fetcherSettings['stats_password'] = '';
$fetcherSettings['mysql_server']   = '';
$fetcherSettings['mysql_login']    = '';
$fetcherSettings['mysql_password'] = '';
$fetcherSettings['mysql_database'] = '';

//fetch method settings
//0..Autodetect best method
//1..Force script to use cURL
//2..Force script to use fopen/file_get_contents

$fetcherSettings['fetch_method'] = 0;

//caching settings - refresh after midnight
//false..Refresh data from NADEO if the last update was 24h or longer ago
//true..Refresh data from NADEO on the first access of the day (might be useful if you plan to get the data per nightly cronjobs)

$fetcherSettings['refresh_after_midnight'] = false;

?>