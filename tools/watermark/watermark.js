
 function doWatermark()  {
   var watermarkText = '<p class="wmText">&copy; Rainbo Design</p>';
   var theDIVs = new Array;
   var myHTML = new String;

     theDIVs = document.getElementsByTagName('div');
   var numDIVs = theDIVs.length;

    for (i=0; i<numDIVs; i++) {
      if (theDIVs[i].id.substr(0,5) == 'wmBox') {
         myHTML = theDIVs[i].innerHTML;
         theDIVs[i].innerHTML = watermarkText + myHTML;
      } // endif wmBox DIV
    } // end for i

 } // end doWatermark


