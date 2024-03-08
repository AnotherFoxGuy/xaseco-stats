XAseco + RASP Stats for TMF
=============================
version 3.7 (8-3-2024) (D-M-Y)

written by oS.Cypher 2007-2008
visit www.os-gaming.de
thx to (OoR-F)~fuckfish for the ColorParser and the DataFetcher!
thx to SaKrifieD for BB-Code to HTML Funtion!
thx to Assembler Maniac for the Banners Fix!
thx to Xymph for the tmx_get update!
thx to w1lla for Colorparser & DataFetcher Fix!
thx to Assembler Maniac for the Banners Fix (again since v2.0)!
thx to Ant for Flags Update!
thx to W1lla for the TMX Author Comments Fix!
thx to sn3p for multi server support and new layout!
thx to W1lla for Updates after 2.3 (Updates for TMX and Dedimania)!
thx to lidor5353 for the Server Banners Tab!
thx to W1lla for Current Ladder Mode & Number of CP's info cells!
thx to Ant for Stunts mode support!
thx to Assembler Maniac for the v3.4 Banners Fix!
thx to W1lla for adding the TMX World Record into the TMX info page!
thx to Assembler Maniac for the ColorParser fix!
thx to W1lla and TheM for the updated Dedimania info page!
thx to W1lla for fixing the search results pages!


## Installation


1. Copy the whole content to a folder on your webserver.  
Example: www.yourwebsite.com/xaseco-stats  
The easiest way is to put the stats on the same server where your TM-Server and your MySQL 
database are running!

2. Modify config.php and fill in your settings!

3. Read the NOTE in the Player Status Picture section below and enter your settings into 
tmfdatafetcher.config.php.

4. Open the stats in your web browser.  
Example: www.yourwebsite.com/xaseco-stats


### Server Status Picture


If you would like to set a little stats picture on your website,
you can use the stats-box which is implemented in this package.

The size of the biggest image is 500 x 101 pixels.
The size of the medium image is 160 x 182 pixels.
The size of the smallest image is 160 x 64 pixels.

To implement the stats box, you need to implement a picture in your website.

To see the stats boxes of your Stats with the HTML and BB-Code,
click on the word "Stats" in the footer of the statspage, or click on the "Server Stats" tab
then click on the "Server Banners" tab.

### Player Status Picture

If you would like to set a little stats picture on your website or as a signature in a forum,
you can use the playerstats-box which is implemented in this package.

To see the stats boxes of the players with the HTML and BB-Code,
click on the Banner icon at the end of the row in the "Players" page.

If you search for a player, you can reach the banner that way as well.

NOTE:
Player stats banners still do not work unless you have access to the NADEO Stats Database.
The login must be entered into tmfdatafetcher.config (includes/tmfdatafetcher.config.php).
Read the tmfdatafetcher.readme (includes/tmfdatafetcher.readme.txt) for more info.

MISSING HOMEZONE FLAGS:
Some homezone flags seem to be missing on the tmf webserver. Missing homezone flags can be
added manually in the /img/flags/zone directory. Make sure they are in PNG format and have the
same name as the zone they represent, IE: London.png. For the best result, make sure the flags
are square images with dimensions of 12x12 pixels, if not they will be automaticly resized.
If a flag is missing and not found in this directory, banners show the unknown flag "?".


## Changelog

#### v3.7 8-3-2024

Updated the entire thing to work with PHP 8


#### v3.6 Update Information (06-06-2011)

pages\dedimania_info.php (Updated with an easier to read layout and links to selected players
                          Dedimania stats) Thanks W1lla and TheM
pages\search_players.php (Added result limits per page and next & previous buttons) Thanks W1lla
pages\search_tracks.php  (Added result limits per page and next & previous buttons) Thanks W1lla
All language files       (Updated translations for the new Dedimania info page)
index.php                (Version changes and download link)			
config.php               (version change)
			   

#### v3.5 Update Information (10-05-2011)

pages\tmx_info.php and includes\tmx_get.php (Updated to show TMX World Record) Thanks W1lla
All language files (Updated with World Record and No record found translations as well as a
                    blank space to be used in player banners)
playerstats.php (Removed the word "server" from statsboxes 2 and 3, then added a blank space					
                 into statsbox 2 to make these banners look nicer) Thanks for the tip AM :) 
includes\ColorParser.php (Added isset checks for certain array items) Thanks AM
index.php (Version changes and download link)			
config.php (version change)
img\statsbox\serverstats.png (changed image for TMF and renamed the old image to 
                              serverstats_tmn.png)	 


#### Assembler Maniac's v3.4 Update (09-03-2011)

playerstats.php
serverstats.php

Changed both files to use ONE stripping function (stripFormatting) for all player, track and 
server name variables.

Code is also easier to read (spaces added for clarity within string manipulation and assigns).

A few vars renamed to reflect their content:
$servername -> $sname_stripped
$servername1 -> $sname_tmtags
(same for track & player)


#### Ant's v3.3 Update (08-02-2011)

playerstats.php (stripFormatting Function added)
includes/tmfdatafetcher.inc.php (Added v1.5b)
pages/search_players.php (Updated so the tables match the players page)
pages/search_tracks.php  (Updated so the tables match the tracks page)


#### Ant's v3.2 Update (19-01-2011)

Changed deprecated ereg_replace and eregi_replace functions to preg_replace in serverstats.php
and playerstats.php
Updated all language files so there is now no missing translations.
Updated includes/tmx_get.php to fix broken links on the TMX info page (Thanks W1lla)
Updated pages/tmx_info.php to show correct page layout if $tmx_info = 2; is used in config.php
Removed checkpoints column in pages/trecords.php because cp times were not able to be displayed
correctly.
The following files were also updated although i'm not sure they're still being used, but
they were using deprecated ereg_replace and eregi_replace functions which have now been 
replaced with preg_replace functions:
includes/playername.php
includes/servername.php
includes/comment.php
includes/trackname.php


#### Ant's v3.1 Update (06-01-2011)

Now includes support for stunts mode.  For the stats to show Stunts scores properly, 
$stunt[$i] needs to be set to 1 in config.php for each stunt server - otherwise it should be
set to 0.
Stunts mode stats are visible under Players, Tracks & Live Rankings.
Also includes a couple of new info cells under Server Stats (Added by W1lla) - they are: 
Current Ladder Mode under the Server tab & Number of CP's under the challenge tab.
Another tab also added under Server Stats is Server Banners - made by lidor5353.


#### W1lla's v3.0 Update (07-12-2010)

Updates made by W1lla are:
TMX Smilies in author Comments.
Fixed hyperlinks where in author comments stood Link and not the real url.
Added how many awards a track has on TMX.
Visit TMX track on TMX webpage.
Edited ENG.php in languages folder for updates.
Download First offline record on TMx of the occuring track.
Dedimania read-only access:
Shows first 10 world records and on which server it is made.


#### sn3p's v2.3 Update (07-08-2010)

Biggest changes are support for multiple servers/databases and changes in the layout.
Also merged the Rankings and Players pages and made the tables sortable.
Rewrote parts of the code, fixed some bugs and added some error handling.


#### W1lla's TMX Author Comments Fix

2 files have now been updated (tmx_info.php & tmx_get.php) now all links and images work
properly in the TMX info section for tracks, before this update, there were no images
(if any applied by author) and links did not work properly from comments section.

This now qualifies as a v2.2 release !!


#### Ant's v2.1 Update

A lot of flags were missing from this, I found them and updated it as necessary.  Now since
all these fixes have been applied - this is due a v2.1 release !


#### Assembler Maniac's Banners Fix

The StripColors function had been placed INSIDE a while loop. My guess is that the PHP parser
was crashing silently due to a stack fault OR a function redefinition (zero errors even with
error reporting turned on).

I also added a break to the while loop that was calculating ranks. Once you find the player
you want, you don't need to continue the loop, just get out and keep on going.


#### w1lla's Update (Colorparser & DataFetcher)

Colorparser now works properly again for tm colours on the stats site.
See the readme thats located in the includes folder for the DataFetcher Configuration.
These stats are now fully compatible with XAseco as from v1.10.


#### Ant's Temporary Update (For XAseco v1.10)

Fixed all sorts of php errors, but have lost the ability to parse colour names.  All the
stats will work perfectly for your server though :)

Banners also do not work properly but this isn't too important as they are only server
banners and not global banners.

Also added in a homepage button to navigate to the stats front page more easily.
I have added a new home picture also, if you want to use it, open the img folder and rename
home 1 to home.


#### Xymph's Update

This version now has the ability to read info about tracks from any part of tm-exchange.
The updated file is tmx_get and is located in the includes folder.

This means that these stats will now work for tmnforever, nations, united, original and
sunrise.

If you have a server running on united for example and have tracks on your server from
other games, you will still be able to see all stats and info for these tracks !!

Also updated is the TMNDataFetcher found in the includes folder and is taken from Xymph's
Aseco v1.04.  (Created by (OoR-F)~fuckfish & updated by Xymph).

