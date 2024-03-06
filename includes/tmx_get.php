<?php
$tmx = false;
$tmx_id = false;
$recordlist;
$records;
$prefixes = array('tmnforever', 'nations', 'united', 'sunrise', 'original');
foreach ($prefixes as $prefix) {
        $file = @file('http://'.$prefix.'.tm-exchange.com/apiget.aspx?action=apitrackinfo&uid=' . $UId);
        if ($file[0] && strpos($file[0], chr(27)) === false) {
                $file = explode(chr(9), $file[0]);
         $search = array(chr(31));
         $replace = array('<br />', '<i>', '</i>');
                $tmx = array(   'prefix'        => $prefix,
                                'id'            => $file[0],
                                'name'          => $file[1],
                                'userid'        => $file[2],
                                'author'        => $file[3],
                                'uploaded'      => $file[4],
                                'updated'       => $file[5],
                                'type'          => $file[7],
                                'envir'         => $file[8],
                                'mood'          => $file[9],
                                'style'         => $file[10],
                                'routes'        => $file[11],
                                'length'        => $file[12],
                                'difficult'     => $file[13],
                                'records'       => $file[14],
                                'comment'       => str_replace($search, $replace, $file[16]),
                                'imgurl'        => 'http://'.$prefix.'.tm-exchange.com/get.aspx?action=trackscreen&id=' . $file[0],
                                'imgurlsmall'   => 'http://'.$prefix.'.tm-exchange.com/get.aspx?action=trackscreensmall&id=' . $file[0],
                                'download'      => 'http://'.$prefix.'.tm-exchange.com/get.aspx?action=trackgbx&id=' . $file[0],
                                'viewurl'       => 'http://'.$prefix.'.tm-exchange.com/main.aspx?action=trackshow&id=' . $file[0]
  );
               $tmxAuthFavo = $prefix;
$records = $file[14];
$tmxid = $file[0];
               // get misc. track info
        $files = @file('http://'.$prefix.'.tm-exchange.com/apiget.aspx?action=apisearch&trackid=' . $tmxid);
                if ($files[0] && strpos($files[0], chr(27)) === false) {

        // separate columns on Tabs
        $fields = explode(chr(9), $files[0]);
        $tmx_id = array(
         'id'           => $fields[0],
        // name         = $fields[1];
        // userid       = $fields[2];
         'author'       => $fields[3],
        // type         = $fields[4];
        // envir        = $fields[5];
        // mood         = $fields[6];
        // style        = $fields[7];
        // routes       = $fields[8];
        // length       = $fields[9];
        // diffic       = $fields[10];
        // lbrating     = ($fields[11] > 0 ? $fields[11] : 'Classic!');
        'awards'   => $fields[12],
        'comments' => $fields[13],
        'custimg'  => (strtolower($fields[14]) == 'true'),
        // game         = $fields[15];
        'replayid' => $fields[16],
        // unknown      = $fields[17-21];
        // uploaded     = $fields[22];
        // updated      = $fields[23];
        );
        $replayid = $fields[16];
if ($tmx_id['replayid'] > 0) {
            $replayurl = 'http://'.$prefix.'.tm-exchange.com/get.aspx?action=recordgbx&id=' . $replayid;
        } else {
            $replayurl = '';
        }
		
	    // fetch records too?
            $file = @file('http://' .$prefix . '.tm-exchange.com/apiget.aspx?action=apitrackrecords&id=' . $tmxid);
			if ($file === false || $file == -1 || $file == '')
				return false;
			$file = explode("\r\n", $file[0]);
			$recordlist = array();
			$i = 0;
			while ($i < 10 && isset($file[$i]) && $file[$i] != '') {
				// separate columns on Tabs
				$fields = explode(chr(9), $file[$i]);
				$recordlist[$i++] = array(
				                            'replayid' => $fields[0],
				                            'userid'   => $fields[1],
				                            'name'     => $fields[2],
				                            'time'     => $fields[3],
				                            'replayat' => $fields[4],
				                            'trackat'  => $fields[5],
				                            'approved' => $fields[6],
				                            'score'    => $fields[7],
				                            'expires'  => $fields[8],
				                            'lockspan' => $fields[9],
				                          );
									//var_dump($recordlist[0]['time']);
			}
             }
          }	
		
        }  // getData
?>