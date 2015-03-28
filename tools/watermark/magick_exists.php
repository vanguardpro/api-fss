<?php
// Test for availability of ImageMagick (6.0.7 on CentOS)
// ------------------------------------
// If ImageMagick is available, this script displays the version information
// as well as all of the available fonts.
// locate -r '\.ttf$'


// You may need to set the path to the 'convert' command by un-commenting
// the next line.  Consult with your hosting service for the path information.
// Be sure to include the trailing slash.
//  $im_path="/path/to/imagemagick/";

$im_path = '';
  header('Content-Type: text/plain');
  system($im_path . "convert -version");
  system($im_path . "convert -list type");
  system($im_path . "convert -list font");
//  system('identify images/slide1.jpg');

?>
