###############################################################################
#
#      Image Watermarking Package v2.1
#      by Richard L. Trethewey
#      rick@rainbo.net
#
#      Installation Instructions and Documentation
#
###############################################################################

Copyright (C) December 15, 2010 by Richard L. Trethewey.  All rights reserved.

NOTICE: This software is published without warranty of any kind.  By
using the software you agree to assume all risks and to hold Richard L.
Trethewey and his agents harmless for any damages. 

This software is provided at no charge.  If you use and enjoy this software,
please place the following link on your site:

<a href="http://www.rainbodesign.com/pub/">Rainbo Design Scripts and Tutorials for Webmasters</a><br>

Links like this help other users find our website both from your site and
when they use search engines, and I really appreciate the help.  If one my
scripts helped you make your site better, helped you understand something,
or just saved you some time, please repay me with a link. Thank-you!

###############################################################################

The software included in this package allows you to easily add text or image
watermarks to your images to help you to protect your images from theft and
hotlinking.  The goal is to make it easy and automatic so that you don't have
to edit your original images just to add a watermark.

###########################################################################
#
#          PHP Version Installation Instructions
#
###########################################################################

Select the version of my software that you prefer.  Either "wm.php" for the
version that uses the GD Library, or "wm_magick.php" which relies on
Image Magick.  The GD version is the best choice because the software is much
more versatile and the GD Library is almost always installed along with PHP.
If the GD Library is not available to you, but you do have Image Magick installed
on the server that hosts you site then, by all means, use "wm_magick.php".

Before you select a version, be sure the appropriate graphics library is
installed and working on your server.

The script "gdinfo.php" will tell you if the GD Library is installed and
working on your server.  The script "magick_exists.php" will tell you if
Image Magick is installed and working on your server.  Just upload them to
your site and load them in your browser, one by one.

Open the file holding the version you select in a text editor like "Notepad".
Near the start of the file is a section labeled "User Settings" where you
need to make some changes before using the software.

All Versions
------------
For image overlay watermarks, create your overlay image in your favorite
graphics editor.  Keep it simple and small in proportion to most of your
images.  Upload it to your server in the same directory where you intend
to install the PHP script or above that level  (ie. deeper).


GD Version Only
---------------
To use text message watermarks, you'll need to have a True Type font file
installed on your server.  Most servers have a common set of these fonts
already installed.  If you can determine the directory path to these font
files on your server, then set the value of the variable $fontPath to
that path.  If you can't find the fonts on your server, you can copy one
or more font files from your computer and upload them to your website.
The best way is to create a directory for these fonts in the same directory
that holds your image files or at the same level as that directory.  So
the value for $fontPath would be either "images/fonts/" or "fonts/",
respectively.

For image watermarks, set the value of the variable $wm_image to point to
the overlay image.  The example setting should guide you here.  Note that
it starts with $basePath, which is the path to the directory where wm.php
is located on your server, so (again) the value for $wm_image should reside at or
above that directory level on your server.

Image Magick Version
--------------------
Change the value of the variable $wm_font to the name of the font that you
want to use for text message watermarks.

All Versions
------------
You can also change the values in the "Default Settings" section.  Changing
the defaults to your preferred settings is helpful when using an .htaccess
file to automatically watermark your images because it keeps the URI simple.

Select a font site for your text watermarks by setting the value of the
variable $fontSize.  10, 11, or 12 is usually best.  Then select the
message to be used for your watermark by setting the value of the
variable $message.  Again, simple is best.

Set $wm_factor to 1 if you want your image overlay displayed at full size.
Otherwise, set it to 1/2, 1/3, or 1/4 to reduce the overlay image by that ratio.

Set $wm_bg to 1 if you want to add a contrasting background to your text message
watermarks.  The background will be white unless the font color is also white.
In that case, the background will be black.

$wm_opacity adjusts the opacity of the watermark.  That is, the percentage of
how much of the original image will bleed through your watermark - or, more
precisely, the inverse percentage.  The default setting of 70 would allow 30% of
the underlying original image to bleed through.  A setting of 100 would make the
watermark completely opaque.  A setting of 10 or 20 would mean that the watermark
image would be barely visible.

$wm_rotate controls the rotation of the watermark.  This function is only available
in the GD version.  The angle of offset is counter-clockwise like you used in making
graphs for trigonometry.  See?  They told you that you'd use that stuff someday!

Upload the version of my software that you intend to use to the your website.  It's
easiest if you upload these files to either the same directory that holds your
image files, or one level below that directory.

Usage Instructions
------------------
Using the software is as simple as modifying the <img> tags on your webpages to
point the 'src' attribute to point to the PHP script "wm.php" or "wm_magick.php".
You can test the software by just trying to load it in your browser with:
http://www.yoursite.com/wm.php?src=some_image.jpg

Controlling the options is done by adding parameters in the query string of the
URL used to call the watermarking script.  That's the stuff that comes after the
question mark.  The available options are:

src - The relative path to the image to be watermarked.
type - The type of watermark to be done.  'msg' for a text message, or 'img' for an image overlay.
pos - The position of the watermark - 'top' or 'bottom'.
color - The color for the font used for text message watermarks.
size - The size of the font used for text message watermarks.
bg - Whether or not to add a colored background for text message watermarks (1=yes, 0=no).
wmsize - The size reduction factor for image watermarks (2=one half, 3=one third, 4 = one quarter).
op - The opacity setting for the watermark.  See the installation instructions for details.  GD Version Only!
rot - The angle of rotation for the watermark.  GD Version Only!  And best used only if running PHP v5.1+.
m - Replace the default message for text message watermarks.
x - Horizontal offset for watermarks. GD Version Only!

###########################################################################
#
#         Using .htaccess for Watermarking Images
#
###########################################################################

If you use an Apache-based server, then you can use the .htaccess server control
file to automatically watermark selected images.  I included an example file
named "htaccess.txt" that includes the following:

  RewriteEngine On
  RewriteCond %{REQUEST_URI} ^/protected/(.*)([gif|jpe?g|png|bmp])$
  RewriteRule ^(.*) /wm.php?src=$1 [L]

The above instructions assume that the watermarking script "wm.php" is installed
in the root directory of your site.  Just change the word "protected" to the
name of the directory that holds the images you want to watermark.  Then rename
this file ".htaccess" and upload it to the root directory of your site, or copy
and paste these instructions into your existing .htaccess file.  If you only
want to watermark some of your images, you can select subdirectories of your
main images directory, as in:

  RewriteEngine On
  RewriteCond %{REQUEST_URI} ^/protected/subdirectory/(.*)([gif|jpe?g|png|bmp])$
  RewriteRule ^(.*) /wm.php?src=$1 [L]

You can also omit or exclude selected subdirectories from watermarking with:

  RewriteEngine On
  RewriteCond %{REQUEST_URI} ^/protected/(.*)([gif|jpe?g|png|bmp])$
  RewriteCond %{REQUEST_URI} !^/protected/omit_subdirectory/(.*)$
  RewriteRule ^(.*) /wm.php?src=$1 [L]

If you have trouble, there are experts available on Webmaster World at
http://www.webmasterworld.com/apache/


###########################################################################
#
#          Perl Version Installation Instructions
#
###########################################################################

At the moment, no Perl version is available, but I'm seriously considering
adding one in the near future.  Let me know if you're interested.  I update my
software frequently, so be sure to check in at http://www.rainbodesign.com/pub/watermark/
for updates.

###########################################################################
#
#          JavaScript Version Installation Instructions
#
###########################################################################

The JavaScript version is mostly for show.  It's only effective on your website,
but if you just want to watermark your images as a deterrant or just to annotate
some images, then it will do the job.

Open the file "rdWatermark.js" in a text editor, and change the value of the
variable 'watermarkText' to whatever you want to use for your watermarks.  You can
add text or an image.  Save the changes.

Then open the file 'watermark.css' in the text editor.  Do not change any of the
settings for 'position' or 'z-index'.  You can add settings to the definition for 'wmBox',
or you can alter the definition of '.wmText' to change the
position and appearance of the watermark text.

Install the script "rdWatermark.js" and the stylesheet file "watermark.css" in the root directory
of your site.  Then you'll need to modify the <head> of your webpages to include those files.  Construct
your <img> tags like the following:

<div class="wmBox">
<img class="wmImg" id="wmImg1" src="images/some_image.jpg" width="xx" height="yy" alt="image description" /><br />
</div><!-- end wmBox -->

using the class name 'wmBox' on the DIVs that enclose the images that you want to watermark.
Then change the <body> tag on your page to read:

<body onload="rdClassWatermark('wmBox');">

You can use multiple class names on your DIV tags and the script will still work, so its very flexible
and should not interfere with your page design.  But it's necessary to enclose your <img> tags
in <div>s with whatever class name you choose to use for images to be watermarked in order for
this script to work.

###########################################################################

If you use and enjoy this software, please remember to include a link to my
site at "http://www.rainbodesign.com/pub/" on your website.
Thanks and good luck!

Sincerely,
Richard L. Trethewey
rick@rainbo.net

