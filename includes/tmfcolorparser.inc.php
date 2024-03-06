<?php
/**
 * TMFColorParser v1.1 by oorf|fuckfish (fish@stabb.de)
 *
 */
class TMFColorParser{

	var $links, $manialinks;
	
	function getStyledString($string, $match, $col, $wide, $narrow, $caps, $italic, $stripColors){
		$string = substr($string, strlen($match));
		$string = trim ($string);

		if ($caps) $string = strtoupper($string);

		if (($col||$wide||$narrow||$italic)&&($string)){
			$start1 = "<span style='";
			$start2 = "'>";
			$styles = "";
			$end = "</span>";
			if ($col && !$stripColors){
				$styles.="color:#".$col.";";
			}
			if ($italic){
				$styles.="font-style:italic;";
			}
			if ($wide){
				$styles.="font-weight:bold;";
			}
			if ($narrow){
				$styles.="letter-spacing: -0.1em;font-size:smaller";
			}
			$string = $start1.$styles.$start2.$string.$end;
		}
		return $string;
	}

	function parseLinks($str, $showLinks = true){
		$str = $this->parseManialinks($str, $showLinks);
		$str = str_replace('$L', '$l', $str);
		$this->links=array();
		$linkidx = 0;
		$chunks = explode('$l', $str);
		for ($i=0; $i<count($chunks); $i++){
			$text = $chunks[$i];
			if ($i%2==1){
				$id = '{LINK'.$linkidx.'}';
				$linkidx++;
				if (preg_match('/\A\[(.*)\](.*)\Z/', $text)) {
					$url = substr($text, strpos($text, '[')+1);
					$url = substr($url, 0, strpos($url, ']'));
					$this->links[$id] = $url;
					if ($showLinks) $chunks[$i] = '$a'.$id.'$x'.substr($text, strpos($text, ']')+1).'$a{/LINK}$x';
					else $chunks[$i] = substr($text, strpos($text, ']')+1);
				} else {
					$this->links[$id]= $text;
					if ($showLinks) $chunks[$i] = '$a'.$id.'$x'.$text.'$a{/LINK}$x';
				}
			}
		}
		$this->fixWWWLinks();
		return implode('', $chunks);
	}
	
	function fixWWWLinks(){
		foreach ($this->links as $key => $value){
			$value = trim($value);
			if (substr(strtolower($value), 0, 4) == 'www.'){
				$value = 'http://'.$value;
				$this->links[$key] = $value;
			}
		}
	}
	
	function parseManialinks($str, $showLinks = true){
		$str = str_replace('$H', '$h', $str);
		$this->manialinks=array();
		$linkidx = 0;
		$chunks = explode('$h', $str);
		for ($i=0; $i<count($chunks); $i++){
			$text = $chunks[$i];
			if ($i%2==1){
				$id = '{MLINK'.$linkidx.'}';
				$linkidx++;
				if (preg_match('/\A\[(.*)\](.*)\Z/', $text)) {
					$url = substr($text, strpos($text, '[')+1);
					$url = substr($url, 0, strpos($url, ']'));
					$this->manialinks[$id] = $url;
					if ($showLinks) $chunks[$i] = '$a'.$id.'$x'.substr($text, strpos($text, ']')+1).'$a{/LINK}$x';
					else $chunks[$i] = substr($text, strpos($text, ']')+1);
				} else {
					$this->manialinks[$id]= $text;
					if ($showLinks) $chunks[$i] = '$a'.$id.'$x'.$text.'$a{/LINK}$x';
				}
			}
		}
		return implode('', $chunks);
	}
	
	
	function insertLinks($str){
		foreach ($this->manialinks as $key => $value){
			$str = str_replace($key,  '<a href ="tmtp:///:'.$value.'"', $str);
		}
		foreach ($this->links as $key => $value){
			$str = str_replace($key, '<a href="'.$value.'">', $str);
		}
		$str = str_replace('{/LINK}', '</a>', $str);
		return $str;
	}

	function toHTML($str, $stripColors = false, $stripLinks = false){
		$col = false;
		$wide = false;
		$narrow = false;
		$caps = false;
		$italic = false;

		$str = str_replace('$$','[DOLLAR]',$str);
		$str = str_replace(' ','&nbsp;',$str);
		$str = $this->parseLinks($str, !$stripLinks);
		$chunks = explode('$',$str);

		for ($i = 1; $i < count($chunks); $i++){
			if (preg_match("/^[0-9a-f]{2,3}/i",$chunks[$i], $matches)){
				$col = $matches[0];
				if (strlen($col)<3) $col.="8";
				$col = $col[0].$col[0].$col[1].$col[1].$col[2].$col[2];
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			} else if (preg_match("/^(i)/i",$chunks[$i], $matches)){
				$italic = true;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			} else if (preg_match("/^(w)/i",$chunks[$i], $matches)){
				$narrow = false;
				$wide = true;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			} else if (preg_match("/^(o)/i",$chunks[$i], $matches)){
				$narrow = false;
				$wide = true;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			} else if (preg_match("/^(n)/i",$chunks[$i], $matches)){
				$wide = false;
				$narrow = true;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			} else if (preg_match("/^(s)/i",$chunks[$i], $matches)){
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			} else if (preg_match("/^(t)/i",$chunks[$i], $matches)){
				$caps = true;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			} else if (preg_match("/^(m)/i",$chunks[$i], $matches)){
				$wide = false;
				$bold = false;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			}  else if (preg_match("/^(g)/i",$chunks[$i], $matches)){
				$col = false;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			}  else if (preg_match("/^(a)/i",$chunks[$i], $matches)){
				
				$colSave= $col;
				$wideSave = $wide;
				$narrowSave = $narrow;
				$capsSave = $caps;
				$italicSave = $italic;
				$col = false;
				$wide = false;
				$narrow = false;
				$caps = false;
				$italic = false;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			}  else if (preg_match("/^(x)/i",$chunks[$i], $matches)){

				$col = $colSave;
				$wide = $wideSave;
				$narrow = $narrowSave;
				$caps = $capsSave;
				$italic = $italicSave;
				
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			}  else if (preg_match("/^(z)/i",$chunks[$i], $matches)){
				$col = false;
				$wide = false;
				$narrow = false;
				$caps = false;
				$italic = false;
				$chunks[$i] = $this->getStyledString($chunks[$i], $matches[0], $col, $wide, $narrow, $caps, $italic, $stripColors);
			}

		}

		for ($i = 1; $i < count($chunks); $i++){
			$chunks[$i] = str_replace('[DOLLAR]','$',$chunks[$i]);
			$chunks[$i] = str_replace('&NBSP;','&nbsp;',$chunks[$i]);
			
		}


		$str = implode($chunks);
		$str = $this->insertLinks($str);
		return $str;
	}
	function toArray($str){
		$col = false;
		$wide = false;
		$narrow = false;
		$caps = false;
		$italic = false;
		$shadow = false;

		$str = str_replace('$$','[DOLLAR]',$str);
		$str = $this->parseLinks($str, false);
		$chunks = explode('$',$str);

		$result = array();
		if ($chunks[0]) $result[0]["text"]=str_replace('[DOLLAR]','$',$chunks[0]);


		for ($i = 1; $i < count($chunks); $i++){
			$match = "";
			if (preg_match("/^[0-9a-f]{2,3}/i",$chunks[$i], $matches)){
				$col = $matches[0];
				$match = $col;
				if (strlen($col)<3) $col.="8";
				$col = $col[0].$col[0].$col[1].$col[1].$col[2].$col[2];
			} else if (preg_match("/^(i)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$italic = true;
			} else if (preg_match("/^(w)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$narrow = false;
				$wide = true;
			} else if (preg_match("/^(o)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$narrow = false;
				$wide = true;
			} else if (preg_match("/^(n)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$wide = false;
				$narrow = true;
			} else if (preg_match("/^(s)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$shadow = true;
			} else if (preg_match("/^(t)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$caps = true;
			} else if (preg_match("/^(m)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$wide = false;
				$bold = false;
			}  else if (preg_match("/^(g)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$col = false;
			}  else if (preg_match("/^(x)/i",$chunks[$i], $matches)){
				$match = $matches[0];
			}  else if (preg_match("/^(z)/i",$chunks[$i], $matches)){
				$match = $matches[0];
				$shadow = false;
				$col = false;
				$wide = false;
				$narrow = false;
				$caps = false;
				$italic = false;
			}

			$chunks[$i] = substr($chunks[$i], strlen($match));
			if ($chunks[$i]){
				$a = count($result);
				$result[$a]["text"]=str_replace('[DOLLAR]','$',$chunks[$i]);
				$result[$a]["italic"]=$italic;
				$result[$a]["narrow"]=$narrow;
				$result[$a]["wide"]=$wide;
				$result[$a]["caps"]=$caps;
				$result[$a]["shadow"]=$shadow;
				$result[$a]["color"]=$col;
			}

		}

		return $result;
	}

	function get_rgb($hex) {
		$hex_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
		'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14,
		'F' => 15);
		$hex = str_replace('#', '', strtoupper($hex));
		if (($length = strlen($hex)) == 3) {
			$hex = $hex{0}.$hex{0}.$hex{1}.$hex{1}.$hex{2}.$hex{2};
			$length = 6;
		}
		if ($length != 6 or strlen(str_replace(array_keys($hex_array), '', $hex)))
		return NULL;
		$rgb['r'] = $hex_array[$hex{0}] * 16 + $hex_array[$hex{1}];
		$rgb['g'] = $hex_array[$hex{2}] * 16 + $hex_array[$hex{3}];
		$rgb['b']= $hex_array[$hex{4}] * 16 + $hex_array[$hex{5}];
		return $rgb;
	}


	/**
     * draws a TMN color coded string onto the given picture
     *
     * @param imageHandle $src_img The picture to be drawn on
     * @param int $size	The fontsize
     * @param int $x The X-Position
     * @param int $y The Y-Position
     * @param color $color The color for letters that are not otherwise color coded (default color)
     * @param String $font The used font
     * 					   Attention: You need 4 fonts for the different styles like
     * 								tahoma.ttf		-- normal style
     * 								tahomait.ttf	-- italic style
     * 								tahomawd.ttf    -- wide style
     * 								tahomawdit.ttf	-- wide and italic style
     * 					   (narrow style not implemented yet)
     * @param String $text The TMN color coded Text you want to display
     * @param boolean $stripcolors Strip out the colors or not.
     */
	function drawStyledString($src_img,$size, $x, $y , $color, $font, $text, $stripcolors = false){

		$chunks = $this->toArray($text);
		//var_dump($text);
		$x_offset = 0;
		$black = imagecolorallocate($src_img, 50,50,50);

		for ($i = 0; $i < count ($chunks); $i++){


			$fontUsed = "./".$font;
			if ($chunks[$i]["wide"]) $fontUsed .= "wd";
			if ($chunks[$i]["italic"]) $fontUsed .= "it";
			if ($chunks[$i]["caps"]) $chunks[$i]["text"] = strtoupper($chunks[$i]["text"]);
			$fontUsed.=".ttf";

			if ($chunks[$i]["color"] && (!$stripcolors)){
				$colRGB = $this->get_rgb("#".$chunks[$i]["color"]);
				$usedCol = imagecolorallocate($src_img, $colRGB['r'], $colRGB['g'], $colRGB['b']);
			} else {
				$usedCol = $color;
			}

			imagettftext($src_img, $size, 0, $x + $x_offset + 1, $y + 1, $black, $fontUsed, $chunks[$i]["text"]);
			imagettftext($src_img, $size, 0, $x + $x_offset, $y, $usedCol, $fontUsed, $chunks[$i]["text"]);

			$bbox = imagettfbbox($size, 0, $fontUsed, $chunks[$i]["text"]);
			$x_offset += $bbox[2]+2;

		}
	}

}