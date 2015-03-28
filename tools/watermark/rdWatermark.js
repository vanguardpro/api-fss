// rdWatermark.js  v1.2  Pseudo-watermarking for images using JavaScript by Richard L. Trethewey rick@rainbo.net
// Copyright 2009-2010 - All Rights Reserved
// Permission is granted to use this code as long as this copyright notice
// is left intact.  For more information, see http://www.rainbodesign.com/pub/

   var watermarkText = '<p class="wmText">&copy; Rainbo Design</p>';
   var myHTML = new String;

   var theDIVs = document.getElementsByTagName('div');
   var numDIVs = theDIVs.length;

 function rdClassWatermark(wmClass)  {
   for (i=0; i<theDIVs.length; i++) {
   if (theDIVs[i].className) {
   tClass = theDIVs[i].className.split(' ');  // get class names on all DIVs
    for (j=0; j<tClass.length; j++) {
    if (tClass[j] == wmClass) {
         myHTML = theDIVs[i].innerHTML;
         theDIVs[i].innerHTML = watermarkText + myHTML;
    } // endif
    } // end for j
    } // endif DIV has a className
   } // end for i
}

// Original depreciated version.
 function doWatermark()  {
    for (i=0; i<numDIVs; i++) {
      if (theDIVs[i].id.substr(0,5) == 'wmBox') {
         myHTML = theDIVs[i].innerHTML;
         theDIVs[i].innerHTML = watermarkText + myHTML;
      } // endif wmBox DIV
    } // end for i

 } // end doWatermark


