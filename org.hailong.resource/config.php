<?php

global $thumbSizes;
global $library;

$library = "..";

$thumbSizes = array(80,160,300,600,800,1024);

require_once "$library/org.hailong.functions/functions.php";

date_default_timezone_set("PRC");

function getThumbSize($size){
	global $thumbSizes;
	$ps = $size;
	$c = count($thumbSizes);
	for($i=0;$i<$c;$i++){
		$s = $thumbSizes[$i];
		if($size ==$s){
			return $s;
		}
		else if($s > $size){
			if($s - $size < $size - $ps){
				return $s;
			}
			else{
				return $ps;
			}
		}
		else{
			$ps = $s;
		}
	}
	return $ps;
}

function mkdirs($path){
	if(!is_dir($path)){
		mkdirs(dirname($path));
		mkdir($path);
	}
}

function buildThumbs($image){
	global $thumbSizes;
	$imageSize =  getimagesize($image);
	$width = $imageSize[0];
	$c = count($thumbSizes);
	for($i=0;$i<$c;$i++){
		
		$s = $thumbSizes[$i];
		
		$thumbDir = dirname(__FILE__)."/thumb/".$s."/";
		$thumbFile = $thumbDir.$image;
		$thumbDir = dirname($thumbFile);
		mkdirs($thumbDir);
		$file  = dirname(__FILE__)."/".$image;
			
		if($s <= $width){
			
			if(!file_exists($thumbFile)){
				$Iheight= ceil($s * $imageSize[1]/$imageSize[0]);
				makethumb($file,$thumbFile,$s,$Iheight);
			}
		}
		else {
			if(!file_exists($thumbFile)){
				copy($file, $thumbFile);
			}
		}
	}
}


function makethumb($srcfile,$dstfile,$thumbwidth,$thumbheight,$maxthumbwidth=0,$maxthumbheight=0,$src_x=0,$src_y=0,$src_w=0,$src_h=0)
{
	if (!is_file($srcfile) || !file_exists($srcfile)) {
		return false;
	}
	$tow = (int) $thumbwidth;
	$toh = (int) $thumbheight;
	if($tow < 30) {
		$tow = 30;
	}
	if($toh < 30) {
		$toh = 30;
	}
	$make_max = 0;
	$maxtow = (int) $maxthumbwidth;
	$maxtoh = (int) $maxthumbheight;
	if($maxtow >= 300 && $maxtoh >= 300)
	{
		$make_max = 1;
	}

	$im = '';
	if($data = getimagesize($srcfile)) {
		if($data[2] == 1) {
			$make_max = 0;			if(function_exists("imagecreatefromgif")) {
				$im = imagecreatefromgif($srcfile);
			}
		} elseif($data[2] == 2) {
			if(function_exists("imagecreatefromjpeg")) {
				$im = imagecreatefromjpeg($srcfile);
			}
		} elseif($data[2] == 3) {
			if(function_exists("imagecreatefrompng")) {
				$im = imagecreatefrompng($srcfile);
			}
		}
	}
	if(!$im) return '';

	$srcw = ($src_w ? $src_w : imagesx($im));
	$srch = ($src_h ? $src_h : imagesy($im));

	$towh = $tow/$toh;
	$srcwh = $srcw/$srch;
	if($towh <= $srcwh)
	{
		$ftow = $tow;
		$ftoh = round($ftow*($srch/$srcw),2);
	}
	else
	{
		$ftoh = $toh;
		$ftow = round($ftoh*($srcw/$srch),2);
	}


	if($make_max)
	{
		$maxtowh = $maxtow/$maxtoh;
		if($maxtowh <= $srcwh)
		{
			$fmaxtow = $maxtow;
			$fmaxtoh = round($fmaxtow*($srch/$srcw),2);
		}
		else
		{
			$fmaxtoh = $maxtoh;
			$fmaxtow = round($fmaxtoh*($srcw/$srch),2);
		}

		if($srcw <= $maxtow && $srch <= $maxtoh)
		{
			$make_max = 0;
		}
	}


	$maxni = '';
	//if($srcw >= $tow || $srch >= $toh) {
	if(function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && ($ni = imagecreatetruecolor($ftow, $ftoh))) {
		imagecopyresampled($ni, $im, 0, 0, $src_x, $src_y, $ftow, $ftoh, $srcw, $srch);
		if($make_max && ($maxni = imagecreatetruecolor($fmaxtow, $fmaxtoh))) {
			imagecopyresampled($maxni, $im, 0, 0, $src_x, $src_y, $fmaxtow, $fmaxtoh, $srcw, $srch);
		}
	} elseif(function_exists("imagecreate") && function_exists("imagecopyresized") && ($ni = imagecreate($ftow, $ftoh))) {
		imagecopyresized($ni, $im, 0, 0, $src_x, $src_y, $ftow, $ftoh, $srcw, $srch);
		if($make_max && ($maxni = imagecreate($fmaxtow, $fmaxtoh))) {
			imagecopyresized($maxni, $im, 0, 0, $src_x, $src_y, $fmaxtow, $fmaxtoh, $srcw, $srch);
		}
	} else {
		return '';
	}
	if(function_exists('imagejpeg')) {
		imagejpeg($ni, $dstfile, 100);
		if($make_max && $maxni) {
			imagejpeg($maxni, $srcfile, 100);
		}
	} elseif(function_exists('imagepng')) {
		imagepng($ni, $dstfile);
		if($make_max && $maxni) {
			imagepng($maxni, $srcfile);
		}
	}
	imagedestroy($ni);
	if($make_max && $maxni) {
		imagedestroy($maxni);
	}
	//}
	imagedestroy($im);

	if(!is_file($dstfile) || !file_exists($dstfile)) {
		return false;
	} else {
		return $dstfile;
	}
}

?>