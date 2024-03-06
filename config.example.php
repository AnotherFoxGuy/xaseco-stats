<?php
/*************************************************
*                                                *
*   Stats for XAseco + RASP                      *
*   __________________________________________   *
*                                                *
*   Version 3.6                                  *
*   Copyright (c) 2007-2008 by oS.Cypher         *
*   Developed as a Project of Old School Gaming  *
*                                                *
*   http://www.os-gaming.de                      *
*                                                *
*************************************************/

// SERVERS LIST

// Server 1
$i = 0;
$server_name[$i] = "";						// Paste your Servername here
$db_host[$i] = "localhost";				    // Your database host. most times localhost
$db_name[$i] = "";							// The Name of the Database
$db_user[$i] = "";							// The User for your database
$db_pass[$i] = "";							// The db password for the defined User
$ip2[$i] = "";								// Here you MUST enter your Gameserver IP Adress
$ip[$i] = "localhost";						// If the script is on the same Server as the Gameserver set "localhost" if not enter your Gameserver IP again!
$port[$i] = 5000;							// The XML_RPC Port of your TMU Server
$server_port[$i] = 2350;					// The GameServer Port to check for On or Offline Status
$user[$i] = "SuperAdmin";					// SuperAdmin Login
$pass[$i] = "";                             // SuperAdmin Password
$stunt[$i] = 0;								// 0 = false; 1 = true;		

/*
// Server 2 --- Uncomment or copy/paste this block for listing multiple servers.
$i++;
$server_name[$i] = "";
$db_host[$i] = "127.0.0.1";
$db_name[$i] = "";
$db_user[$i] = "";
$db_pass[$i] = "";
$ip2[$i] = "";
$ip[$i] = "127.0.0.1";
$port[$i] = 5000;
$server_port[$i] = 2350;
$user[$i] = "SuperAdmin";
$pass[$i] = "";
$stunt[$i] = 0;
*/


// MISC SETTINGS

$def_language = "ENG";						// Default GUI Language (ENG, GER, FRA, NED)
$count = 25;								// Results per Page
$tmx_info = 1;								// 0 = TMX info deactivated; 1 = TMX active with check if TMX is available; 2 = TMX active without check if TMX is available
$show_server_banners = 1;					// 0 = false; 1 = true; Displays a "Stats" link in the footer to the server banners page.
$show_player_banners = 1;					// 0 = false; 1 = true; Player stats banners will not work unless you have access to the NADEO Stats Database (see readme.txt)
$show_php_errors = 1;						// 0 = false; 1 = true; Shows PHP errors and warnings (handy for debugging)


// BASIC STYLES

// Menu Style
$s_menufont= "#FFFFFF";						// Font Color of the main menu text
$sh_menufont= "#FFFFFF";					// Hover font Color of the main menu text
$s_menuback= "#336699";						// Background Colour of the main menu
$s_menubord= "#000000";						// Border Colour of the main menu
$s_menuback2= "#669966";					// Background Colour of the sub menu
$s_menubord2= "#000000";					// Border Colour of the sub menu

// Result table styles
$bg1= "#e4e4e4";							// Cell Background1 from Result Tables
$bg2= "#FFFFFF";							// Cell Background2 from Result Tables
$ttcolour= "#4e4e4e";						// Colour of the Headline for the result tables
$s_tablehead= "#CCCCCC";					// Colour of the result table name row font
$s_tableheadb= "#CCCCCC";					// Colour of the result table name row font (For tables with bigger headline)
$s_celltext= "#000000";						// Font colour of the results
$s_celltextrb= "#000000";					// Font colour of the result tables (bold right 12px)
$s_celltextl= "#000000";					// Font colour of the result tables (normal left 9px)
$sh_celltext= "#000000";					// Hover Font colour of the results
$bg4= "#FFFFFF";							// result table bg colour
$bg3= "#c1e4b1";							// row hover colour on result tables
$resultbg= "#4e4e4e";						// Background of Result Table Titles

// Page styles
$menubg= "#CCCCCC";							// Background of server Stats Submenu
$s_pagetitle= "#000000";					// Colour of the Page title
$pagebg= "#FFFFFF";							// Background colour of the page
$s_footfont= "#FFFFFF";						// Colour of the footer text
$s_footback= "#336699";						// Footer Background Colour
$s_footbord= "#000000";						// Footer Border Colour
$s_error=	"#FF0000";						// Colour for Error messages

?>