<?php
// PHP Image Watermark Script v2.0
// by Richard L. Trethewey http://www.rainbodesign.com/pub/watermark/
// functions validHexColor and hex2int from http://www.sitepoint.com/article/watermark-images-php/
// 11/23/10 RLT

// Constants
$lineHeight = 17;
$maxLineLength = 50;
$copyrightSymbol = 169;  // ASCII value for the Copyright Symbol

// User Settings
$fontPath = 'fonts/';
$fontArial = $fontPath . 'arial.ttf';
$fontTahoma = $fontPath . 'Tahoma.ttf';
$message = chr($copyrightSymbol) . " Rainbo Design";  // set message text for watermark
$wm_image = 'rainbodesign.jpg';  // watermark logo image file name (include a path, if necessary)

// Defaults
$wm_type = 'msg';	// default watermarking method/type
$fontColor = 'white';   // default font color White
$fontSize = '11';       // default font size (in pixels for GD v1.x, in point size for GD v2.x+.  Use gdinfo.php to check your server.)
$position = 'bottom';   // default watermark position
$wm_factor = (1/3);	// default watermark image overlay reduction factor (multiplier)
$wm_opacity = 70;	// default watermark image overlay opacity
$wm_rotate = 0;		// default watermark image overlay rotation (in degrees)
$dest_x = 4;		// default watermark x/horizontal position (in pixels)

// Handle Query String option parameters
// type, color, size, pos, wmsize, op, rot, x
 if (isset($_GET['type'])) { $wm_type = $_GET['type']; }
 if (isset($_GET['color'])) { $fontColor = $_GET['color']; }
 if (isset($_GET['size'])) { $fontSize = $_GET['size']; }
 if (isset($_GET['pos'])) { $position = $_GET['pos']; }
 if (isset($_GET['wmsize'])) { $wm_factor = 1/($_GET['wmsize']); }
 if (isset($_GET['op'])) { $wm_opacity = $_GET['op']; }
 if (isset($_GET['rot'])) { $wm_rotate = $_GET['rot']; }
 if (isset($_GET['x'])) { $dest_x = $_GET['x']; }

// Get info about image to be watermarked
$old_image = $_GET['src'];
$dot = strrpos($old_image, '.');
$extension = substr($old_image, $dot);
$size = getimagesize($old_image);

// Create GD Image Resource from original image
  switch($extension) {
   case('.jpg'):
    $image = imagecreatefromjpeg($old_image);
    break;
   case('.gif'):
    $image = imagecreatefromgif($old_image);
    break;
   case('.bmp'):
    $image = imagecreatefrombmp($old_image);
    break;
   case('.png'):
    $image = imagecreatefrompng($old_image);
    break;
  }

// Change/Set watermark vertical position (in pixels)
  if ($position == 'bottom') {
$dest_y = $size[1] - ($watermark_height + 4);
   } else {
$dest_y = 15;
  }

  if ($wm_type == 'img') {

$watermark = imagecreatefromjpeg($wm_image);	// Create a GD image resource from overlay image
$wm_width = imagesx($watermark);		// get the original width
$wm_height = imagesy($watermark);		// get the original height
$watermark_rWidth = $wm_width * $wm_factor;	// calculate new, reduced width
$watermark_rHeight = $wm_height * $wm_factor;	// calculate new, reduced height
$watermark_r = @imagecreatetruecolor($watermark_rWidth, $watermark_rHeight);
// Reduce the size of the overlay image GD resource
// imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
 imagecopyresampled($watermark_r, $watermark, 0, 0, 0, 0, $watermark_rWidth, $watermark_rHeight, $wm_width, $wm_height);

// Handle rotation
  if ($wm_rotate != 0) {
    $watermark_r = imagerotate($watermark_r, $wm_rotate, 0);
    $watermark_rWidth = imagesx($watermark_r);
    $watermark_rHeight = imagesy($watermark_r);
  } // endif $wm_rotate !=0

// Calculate overlay image position
  if ($position == 'bottom') {
  $dest_y = $size[1] - ($watermark_rHeight + 4);
   } else {
  $dest_y = 4;
  } // endif $position

// Overlay the logo watermark image on the original image
// int imagecopymerge ( resource dst_im, resource src_im, int dst_x, int dst_y, int src_x, int src_y, int src_w, int src_h, int pct );
 imagecopymerge($image, $watermark_r, $dest_x, $dest_y, 0, 0, $watermark_rWidth, $watermark_rHeight, $wm_opacity);
 imagedestroy($watermark);
  } // endif $wm_image

 if ($wm_type == 'msg') {
$watermark_width = 250;
$watermark_height = $fontSize + 2;

   switch($fontColor) {
   case('black'):
 $txtColorBk = hex2int(validHexColor('000000','ffffff'));
 $txtColor = imageColorAllocate($image, $txtColorBk['r'], $txtColorBk['g'], $txtColorBk['b']);
  break;
   case('red'):
 $txtColorG = hex2int(validHexColor('800000','ffffff'));
 $txtColor = imageColorAllocate($image, $txtColorG['r'], $txtColorG['g'], $txtColorG['b']);
  break;
   case('green'):
 $txtColorG = hex2int(validHexColor('008000','ffffff'));
 $txtColor = imageColorAllocate($image, $txtColorG['r'], $txtColorG['g'], $txtColorG['b']);
  break;
   case('blue'):
 $txtColorBl = hex2int(validHexColor('000080','ffffff'));
 $txtColor = imageColorAllocate($image, $txtColorG['r'], $txtColorG['g'], $txtColorG['b']);
  break;
   case('white'):
 $txtColorWht = hex2int(validHexColor('ffffff','ffffff'));
 $txtColor = imageColorAllocate($image, $txtColorWht['r'], $txtColorWht['g'], $txtColorWht['b']);
  break;
 } // end switch($fontColor)

// array imageTTFText  ( resource image, int size, int angle, int x, int y, int color, string fontfile, string text)
 imageFTText($image, $fontSize, 0, $dest_x, $dest_y, $txtColor, $fontTahoma, $message);

 } // endif $wm_type == 'msg'

// always output in jpeg for simplicity
header('content-type: image/jpeg');
imagejpeg($image);  
imagedestroy($image);

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
