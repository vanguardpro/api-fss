<?php
// PHP Image Watermark Script v2.3
// by Richard L. Trethewey http://www.rainbodesign.com/pub/watermark/
// functions validHexColor and hex2int from http://www.sitepoint.com/article/watermark-images-php/
// If you use this script, I'd appreciate a link!  Thanks!
// 11/26/10 RLT

// Constants
$copyrightSymbol = 169;  // ASCII value for the Copyright Symbol
$padding = 4;		 // offset for positioning and appearance
$basePath = getcwd() . '/';

// User Settings
$fontPath = 'fonts/';	// IMPORTANT! Set this to the path to the fonts on your server or a local child/sub directory.
$fontArial = $fontPath . 'arial.ttf';
$fontTahoma = $fontPath . 'Tahoma.ttf';
$wm_font = $fontTahoma;	// See the note above!
$message = chr($copyrightSymbol) . "This offer has been expired";  // Message text for watermark


// Defaults
$wm_type = 'msg';	// Default watermarking method/type
$fontColor = 'white';   // Default font color White
$fontSize = 11;         // Default font size (in pixels for GD v1.x, in point size for GD v2.x+.  Use gdinfo.php to check your server.)
$wm_bg = 0;		// Fill background for message text watermarks flag (true/false)
$position = 'bottom';   // Default watermark vertical position
$wm_factor = (1/3);	// Default watermark image overlay reduction factor (multiplier) - set to 1 to retain original size
$wm_opacity = 70;	// Default watermark image overlay opacity
$wm_rotate = 0;		// Default watermark image overlay rotation (in degrees)
$dest_x = 0;		// Default watermark x/horizontal position (in pixels)

// Handle Query String option parameters
// type, color, size, pos, wmsize, op, rot, x, m
 if (isset($_GET['type'])) { $wm_type = $_GET['type']; }
 if (isset($_GET['color'])) { $fontColor = $_GET['color']; }
 if (isset($_GET['size'])) { $fontSize = $_GET['size']; }
 if (isset($_GET['bg'])) { $wm_bg = $_GET['bg']; }
 if (isset($_GET['pos'])) { $position = $_GET['pos']; }
 if (isset($_GET['wmsize'])) { $wm_factor = 1/($_GET['wmsize']); }
 if (isset($_GET['op'])) { $wm_opacity = $_GET['op']; }
 if (isset($_GET['rot'])) { $wm_rotate = $_GET['rot']; }
 if (isset($_GET['x'])) { $dest_x = $_GET['x']; }
 if (isset($_GET['m'])) { $message = $_GET['m']; }
 
 $wm_type="img";
 $wm_factor=1;
 $wm_rotate=0;
 $position='top';
 $wm_opacity=70;
 
 if (isset($_GET['lang'])) { $lang = $_GET['lang']; }
 if($lang=='fr'){
 $wm_image = $basePath . 'images/expired_fr.png';    // Watermark image file name (include a path/URL, if necessary)
 }
 else if($lang=='free'){
 $wm_image = $basePath . 'images/expired_free.png';
 $wm_type="img";
 $wm_factor=1;
 $wm_rotate=0;
 $position='top';
 $wm_opacity=100;
 }
 else if($lang=='salesfree'){
 $wm_image = $basePath . 'images/salespull_free.png';
 $wm_type="img";
 $wm_factor=1;
 $wm_rotate=0;
 $position='top';
 $wm_opacity=100;
 }
 else {
 $wm_image = $basePath . 'images/expired.png';
 }
 
 
// Get info about image to be watermarked
$old_image = $_GET['src'];

$size = getimagesize($old_image);

$imagetype = $size[2];

// Create GD Image Resource from original image
  switch($imagetype) {
  case(1):
    $image = imagecreatefromgif($old_image);
	
	break;

  case(2):
    $image = imagecreatefromjpeg($old_image);
	break;

  case(3):
    $image = imagecreatefrompng($old_image);
	
	break;

  case(6):
    $image = imagecreatefrombmp($old_image);
	break;

  } // end switch($imagetype)

if ($wm_type == 'img') {

$watermark = imagecreatefrompng($wm_image);	// Create a GD image resource from overlay image

$wm_width = imagesx($watermark);		// get the original width
$wm_height = imagesy($watermark);		// get the original height
$watermark_rWidth = $wm_width * $wm_factor;	// calculate new, reduced width
$watermark_rHeight = $wm_height * $wm_factor;	// calculate new, reduced height
$watermark_r = @imagecreatetruecolor($watermark_rWidth, $watermark_rHeight);

if($lang=='free'||$lang=='salesfree'){
imagecolortransparent($watermark_r, imagecolorallocate($watermark_r, 0, 0, 0));
}

// Reduce the size of the overlay image GD resource
// imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
 if(@get_resource_type($watermark_r)=='gd'){
 imagecopyresampled($watermark_r, $watermark, 0, 0, 0, 0, $watermark_rWidth, $watermark_rHeight, $wm_width, $wm_height);
 }
 imagedestroy($watermark);  // original watermark image no longer needed

  } // endif $wm_image

 if ($wm_type == 'msg') {

// Calculate size of overlay image for text
// array imagettfbbox ( float $size , float $angle , string $fontfile , string $text )
$wm_bBox = imagettfbbox($fontSize, 0, $wm_font, $message);
$lowerLeftX = $wm_bBox[0];
$lowerLeftY = $wm_bBox[1];
$lowerRightX = $wm_bBox[2];
$lowerRightY = $wm_bBox[3];
$upperRightX = $wm_bBox[4];
$upperRightY = $wm_bBox[5];
$upperLeftX = $wm_bBox[6];
$upperLeftY = $wm_bBox[7];
$watermark_rWidth = ($upperRightX - $upperLeftX) + $padding;
$watermark_rHeight = ($lowerLeftY - $upperLeftY) + $padding;

// Create the overlay image
$watermark_r = imagecreatetruecolor($watermark_rWidth, $watermark_rHeight);

 $txtColorBk = hex2int(validHexColor('000000','ffffff'));  // black
 $txtColorR = hex2int(validHexColor('800000','ffffff'));   // red
 $txtColorG = hex2int(validHexColor('008000','ffffff'));   // green
 $txtColorBl = hex2int(validHexColor('000080','ffffff'));  // blue
 $txtColorYl = hex2int(validHexColor('ffff00','ffffff'));  // yellow
 $txtColorWht = hex2int(validHexColor('ffffff','ffffff')); // white

   switch($fontColor) {
   case('black'):
 $txtColor = imageColorAllocate($watermark_r, $txtColorBk['r'], $txtColorBk['g'], $txtColorBk['b']);
  break;
   case('red'):
 $txtColor = imageColorAllocate($watermark_r, $txtColorR['r'], $txtColorR['g'], $txtColorR['b']);
  break;
   case('green'):
 $txtColor = imageColorAllocate($watermark_r, $txtColorG['r'], $txtColorG['g'], $txtColorG['b']);
  break;
   case('blue'):
 $txtColor = imageColorAllocate($watermark_r, $txtColorBl['r'], $txtColorBl['g'], $txtColorBl['b']);
  break;
   case('yellow'):
 $txtColor = imageColorAllocate($watermark_r, $txtColorYl['r'], $txtColorYl['g'], $txtColorYl['b']);
  break;
   case('white'):
 $txtColor = imageColorAllocate($watermark_r, $txtColorWht['r'], $txtColorWht['g'], $txtColorWht['b']);
  break;
 } // end switch($fontColor)

 if (!isset($txtColor)) {
    $userColor = hex2int(validHexColor($fontColor, 'ffffff'));
    $txtColor = imageColorAllocate($watermark_r, $userColor['r'], $userColor['g'], $userColor['b']);
 } // endif (!isset($txtColor)

// Set an appropriate background color
  if (($fontColor == 'white') || ($fontColor == 'ffffff')) {
	$watermark_rBGColor = imageColorAllocate($watermark_r, $txtColorBk['r'], $txtColorBk['g'], $txtColorBk['b']);
    } else {
	$watermark_rBGColor = imageColorAllocate($watermark_r, $txtColorWht['r'], $txtColorWht['g'], $txtColorWht['b']);
  }

imagefilledrectangle($watermark_r, 0, 0, $watermark_rWidth, $watermark_rHeight, $watermark_rBGColor);


// Make the background transparent?
 if (!$wm_bg) {
 $bg = imagecolortransparent($watermark_r, $watermark_rBGColor);
 }

// array imageTTFText  ( resource image, int size, int angle, int x, int y, int color, string fontfile, string text)
 imageFTText($watermark_r, $fontSize, 0, 1, $watermark_rHeight-$padding, $txtColor, $wm_font, $message);

 } // endif $wm_type == 'msg'

// Handle rotation
  if ($wm_rotate != 0) {
  if (phpversion() >= 5.1) { 
    $bg = imagecolortransparent($watermark_r);
    $watermark_r = imagerotate($watermark_r, $wm_rotate, $bg, 1);
  } else {
    $watermark_r = imagerotate($watermark_r, $wm_rotate, 0);
 } // endif phpversion()

    $watermark_rWidth = imagesx($watermark_r);
    $watermark_rHeight = imagesy($watermark_r);
  } // endif $wm_rotate !=0


// Calculate overlay image position
  if ($position == 'bottom') {
  $dest_y = $size[1] - ($watermark_rHeight + $padding);
   } else {
  $dest_y = 0;
  } // endif $position

// Overlay the logo watermark image on the original image
// int imagecopymerge ( resource dst_im, resource src_im, int dst_x, int dst_y, int src_x, int src_y, int src_w, int src_h, int pct );
if(@get_resource_type($image)=='gd'){
 imagecopymerge($image, $watermark_r, $dest_x, $dest_y, 0, 0, $watermark_rWidth, $watermark_rHeight, $wm_opacity);
}

  switch($imagetype) {
  case(1):
	header('Content-type: image/gif');
	imagegif($image);  
	break;

  case(2):
	header('Content-type: image/jpeg');
	imagejpeg($image);  
	break;

  case(3):
	header('Content-type: image/png');
	imagepng($image);  
	break;

  case(6):
	header('Content-type: image/bmp');
	imagewbmp($image);  
	break;

  } // end switch($imagetype)
echo $image;
if(@get_resource_type($image)=='gd'){
imagedestroy($image);
}


exit;

/**
 * @param    $hex string        6-digit hexadecimal color
 * @return    array            3 elements 'r', 'g', & 'b' = int color values
 * @desc Converts a 6 digit hexadecimal number into an array of
 *       3 integer values ('r'  => red value, 'g'  => green, 'b'  => blue)
 */
function hex2int($hex) {
        return array( 'r' => hexdec(substr($hex, 0, 2)), // 1st pair of digits
                      'g' => hexdec(substr($hex, 2, 2)), // 2nd pair
                      'b' => hexdec(substr($hex, 4, 2))  // 3rd pair
                    );
}

/**
 * @param $input string     6-digit hexadecimal string to be validated
 * @param $default string   default color to be returned if $input isn't valid
 * @return string           the validated 6-digit hexadecimal color
 * @desc returns $input if it is a valid hexadecimal color, 
 *       otherwise returns $default (which defaults to black)
 */
function validHexColor($input = '000000', $default = '000000') {
    // A valid Hexadecimal color is exactly 6 characters long
    // and eigher a digit or letter from a to f
    return (eregi('^[0-9a-f]{6}$', $input)) ? $input : $default ;
}

// function imageRotate courtesy beau@dragonflydevelopment.com on www.php.net
function rotateImage($img, $rotation) {
  $width = imagesx($img);
  $height = imagesy($img);
  switch($rotation) {
    case 90: $newimg= @imagecreatetruecolor($height , $width );break;
    case 180: $newimg= @imagecreatetruecolor($width , $height );break;
    case 270: $newimg= @imagecreatetruecolor($height , $width );break;
    case 0: return $img;break;
    case 360: return $img;break;
  }
  if($newimg) {
    for($i = 0;$i < $width ; $i++) {
      for($j = 0;$j < $height ; $j++) {
        $reference = imagecolorat($img,$i,$j);
        switch($rotation) {
          case 90: if(!@imagesetpixel($newimg, ($height - 1) - $j, $i, $reference )){return false;}break;
          case 180: if(!@imagesetpixel($newimg, $width - $i, ($height - 1) - $j, $reference )){return false;}break;
          case 270: if(!@imagesetpixel($newimg, $j, $width - $i, $reference )){return false;}break;
        }
      }
    } return $newimg;
  }
  return false;
} // end rotateImage()

?>
