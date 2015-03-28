<?php
// ImageMagick Watermark in PHP
// Copyright 2010-2011 by Richard L. Trethewey rick@rainbo.net
// http://www.rainbodesign.com/pub/watermark/
// 10/28/11 RLT
// If you use this script, I'd appreciate a link!  Thanks!

// 05/16/11 Updated to support text rotation, top/bottom/center
// 05/28/11 Updated to sanitize inputs
// 10/25/11 Added 'auto' color, inspired by Mikko's Blog http://valokuva.org/?p=59
//          RGB to HSL code by EasyRGB.com and http://serennu.com/colour/rgbtohsl.php

// Constants
$copyrightSymbol = 'Â©';  // Â escaped Copyright Symbol '©' for IM on Windows.  Omit for Linux/*ix systems.
$bContent = "Content-type: image/bmp";   // for BMP files
$jContent = "Content-type: image/jpeg";  // for JPEG files
$gContent = "Content-type: image/gif";   // for GIF files
$pContent = "Content-type: image/png";   // for PNG files
$tContent = "Content-type: image/tiff";  // for TIFF files
$old_image = 'images/slide1.jpg';	// Default base image file.

// User Settings and Defaults
$message = $copyrightSymbol . ' Rainbo Design'; // Default watermark text.
$wm_image = 'images/rainbodesign.jpg';		// Default watermark image file (include a path/URL, if necessary)
$wm_font = 'Arial';				// Font family name for message watermarks
$wm_type = 'msg';				// Set to 'msg' for text watermark.  Set to 'img' for image watermark.
$fontColor = 'white';	// Default font color White
$fontSize = '14';       // Default font size
$autoThreshold = 128;	// Auto font color light/dark threshold setting
$fontColorLight = 'white'; // Default light colored font for 'auto'
$fontColorDark = 'black';  // Default dark colored font for 'auto'
$wm_bg = 0;		// Fill background for message text watermarks flag (true/false || opaque/transparent)
$position = 'bottom';   // Default watermark position - bottom left corner
$wm_factor = (1/3);	// Default watermark image overlay reduction factor (multiplier) - set to 1 to retain original size
$wm_opacity = 70;	// Default watermark image overlay opacity
$wm_rotate = 0;		// Default watermark image overlay rotation (in degrees)
$gravity = 'SouthWest'; // Default watermark Imagemagick gravity - bottom left corner
$padding = 4;		// Default padding in pixels for watermark positioning.  Both top/bottom and left/right.

// Image Magick Geometry: [width]x[height][+-][x][+-][y]

// Handle Query String option parameters
// src, type, color, size, pos, wmsize, op, rot, x, m
 if (isset($_GET['src'])) { $old_image = $_GET['src']; }
 if (isset($_GET['type'])) { $wm_type = $_GET['type']; }
 if (isset($_GET['color'])) { $fontColor = strtolower($_GET['color']); }
 if (isset($_GET['size'])) { $fontSize = $_GET['size']; }
 if (isset($_GET['bg'])) { $wm_bg = $_GET['bg']; }
 if (isset($_GET['pos'])) { $position = $_GET['pos']; }
 if (isset($_GET['wmsize'])) { $wm_factor = 1/($_GET['wmsize']); }
 if (isset($_GET['op'])) { $wm_opacity = $_GET['op']; }
 if (isset($_GET['rot'])) { $wm_rotate = $_GET['rot']; }
 if (isset($_GET['m'])) { $message = $_GET['m']; }
 if (isset($_GET['wmimg'])) { $wm_image = $_GET['wmimg']; }

// Block script hacking.
 $matches = array();
  if (preg_match('/([;\n\r])/i', $old_image, $matches)) { die; }

  switch(strtolower($position)) {
   case('bottom'):
    $gravity = 'SouthWest';
    break;
   case('bottomcenter'):
    $gravity = 'South';
    break;
   case('top'):
    $gravity = 'NorthWest';
    break;
   case('topcenter'):
    $gravity = 'North';
    break;
   case('center'):
    $gravity = 'Center';
    break;
 } // end switch($position)

$dot = strrpos($old_image, '.');	// Set image type based on file name extension.  I know. Sorry, but it's fastest.
$extension = substr($old_image, $dot);
  switch($extension) {
   case('.jpg'):
   case('.jpeg'):
   $theContent = $jContent;
   $theWMType = 'jpg:-';
   break;
   case('.gif'):
   $theContent = $gContent;
   $theWMType = 'gif:-';
   break;
   case('.png'):
   $theContent = $pContent;
   $theWMType = 'png:-';
   break;
   case('.bmp'):
   $theContent = $bContent;
   $theWMType = 'bmp:-';
   break;
   case('.tif'):
   $theContent = $tContent;
   $theWMType = 'tif:-';
   break;
 } // end switch($extension)

// Text Message Watermark
 if ($wm_type == 'msg') {

  if ($fontColor == 'auto') {
  $result = array();
  $metrics = getFontMetrics();  // calculate space required for $message
  $textWidth = $metrics['width'];
  $textHeight = $metrics['height'];
  $tmpFile = 'temp' . $extension;
// Get image file specs - Width and Height - for sample crop boundary calculations
  $wmCmd = "convert $old_image -identify null: 2>&1 &";
  exec($wmCmd, $result);  // Use exec() to capture output data in the script only
  $parts = explode(' ',$result[0]);
  list($imgWidth, $imgHeight) = explode('x', $parts[2]);
  switch($gravity) {
    case('North'):
    $cropLeft = $imgWidth - ($textWidth + ($padding*2));
    $cropTop = $padding;
    break;
    case('NorthWest'):
    $cropLeft = $padding;
    $cropTop = $padding;
    break;
    case('South'):
    $cropLeft = $imgWidth - ($textWidth + ($padding*2));
    $cropTop = ($imgHeight - $textHeight) - $padding;
    break;
    case('SouthWest'):
    $cropLeft = $padding;
    $cropTop = ($imgHeight - $textHeight) - $padding;
    break;
    case('Center'):
    $cropLeft = $imgWidth - ($textWidth + ($padding*2));
    $cropTop = $imgHeight - ($textHeight + ($padding*2));
    break;
  } // end switch($gravity)

// Resize original image to 1x1 for average sample of overlay area, and get color of resulting pixel
  $wmCmd = "convert $old_image -crop " . $textWidth . "x" . $textHeight . "+$cropLeft+$cropTop -scale 1x1 ";
  $wmCmd .= " txt:-";		//  was $wmCmd .= "-format \"%[pixel: u.p{0,0}]\" info:";
  exec($wmCmd, $result);	// use exec() to capture output data in the script only
  $pColor = $result[2];
  $matches = array();
  preg_match('/\((.*)\)/', $result[2], $matches);
  list($var_r, $var_g, $var_b) = explode(',',$matches[1]);
// Now calculate luminance
    $var_min = min($var_r,$var_g,$var_b);
    $var_max = max($var_r,$var_g,$var_b);
    $l = ($var_max + $var_min) / 2;	// Lightness is average of brightest and darkest of RGB values.
// Select $fontColor based on $l
    ($l >= $autoThreshold) ? $fontColor = $fontColorDark : $fontColor = $fontColorLight;

 } // endif $fontColor == 'auto'

// Create image of text message with transparent background
  $wmCmd = "convert -background transparent -font $wm_font -fill $fontColor -pointsize $fontSize -rotate $wm_rotate";
  $wmCmd .= " label:\"$message\" miff:- |";
// then composite the text message image over the original
  $wmCmd .= " composite -gravity $gravity -geometry +$padding+$padding -blend " . $wm_opacity . "%";
  $wmCmd .= "  - $old_image $theWMType";

 } // endif $wm_type == 'msg'

// Image Overlay Watermark
 if ($wm_type == 'img') {
  $wmCmd = "convert $wm_image -background transparent -rotate $wm_rotate";
  $wmCmd .= " -scale " . floor($wm_factor * 100) . "%";
  $wmCmd .= " miff:- |";
  $wmCmd .= " composite -gravity $gravity -geometry +0+0 -blend " . $wm_opacity . "%";
  $wmCmd .= "  - $old_image $theWMType";
  } // endif $wm_type == 'img'

$test = 0; // set to 1 to view ImageMagick command being executed
 if ($test) {
 header('Content-type: text/plain');
 echo("$wmCmd\n");
 die;
}

  header($theContent);
  passthru($wmCmd);  // use passthru() to output binary data

exit;

 function getFontMetrics() {
   global  $wmCmd, $wm_font, $message, $fontColor, $fontSize, $wm_rotate;
  $wmCmd = "convert xc: -font $wm_font -pointsize $fontSize ";
  $wmCmd .= " -debug annotate -annotate 0 \"$message\" null: 2>&1 &";
  $result = array();
  exec($wmCmd, $result);  // use exec() to capture output data in the script only
  $metrics = explode(';', substr($result[3], strpos($result[3],'text:')));
    foreach ($metrics as $metric) {
      list($name,$value) = explode(':', $metric);
        if (trim($name) == 'width') { $result['width'] = trim($value); }
        if (trim($name) == 'height') { $result['height'] = trim($value); }
    } // end foreach $metrics
// header('Content-type: text/plain');
// print_r($metrics);
// echo("$message\n");
// die;
  return $result;

 } // end getFontMetrics()

?>
