<?php 

class Coordinates{
    public $latlng;
      function __construct() {
        $this->latlng = $_GET['lat'].','. $_GET['lng'];           
      }


 public function getGeoCounty() {
        
        $url = 'http://maps.google.com/maps/api/geocode/json?latlng=' . $this->latlng . '&sensor=true';
        $get = file_get_contents($url);
       /* echo "<pre>";
         echo print_r($get, TRUE); 
        echo "<pre>";*/
        $geoData = json_decode($get);
        //echo '<pre>' ; print_r ($geoData->results[0]->address_components) ; echo '</pre>';exit();
        if (isset($geoData->results[0])) {
            foreach ($geoData->results[0]->address_components as $addressComponet) {
                if (in_array('country', $addressComponet->types)) {
                            $data['country'] = strtolower($addressComponet->short_name); 
                }
                if (in_array('administrative_area_level_1', $addressComponet->types)) {
                            $data['state'] = strtolower($addressComponet->short_name); 
                }
                if (in_array('postal_code', $addressComponet->types)) {
                            $data['zip'] = $addressComponet->long_name; 
                }
                if (in_array('locality', $addressComponet->types) || in_array('sublocality', $addressComponet->types)) {
                            $data['city'] = $addressComponet->short_name; 
                }
                $data['address'] = $geoData->results[0]->formatted_address;                 
            }
            return $data;
        }
        return null;
    }

    
    
                
   
       
    
}


new Coordinates(); 

?>
