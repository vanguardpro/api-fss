<?php

class RequestHandeler {
    private $settings, $device, $project;
    function __construct() {
        if (!isset($_GET['api_key'])) {
            new Error('Forbidden', 403);
        } else {
            $getaction = isset($_GET['action']) ? $_GET['action'] : NULL;
            $getcountry = isset($_GET['country']) ? $_GET['country'] : NULL;
            $getapi_key = isset($_GET['api_key']) ? $_GET['api_key'] : NULL;
            $getproject_id = isset($_GET['project_id']) ? $_GET['project_id'] : NULL;
            $this->settings = new ApiSettings();
            $this->device = $this->settings->getDevice($getapi_key);
            
            
$this->project =  $this->settings->getProject('id', $getproject_id);
            
 if(!$this->project){
$country = $this->settings->checkCountry($getcountry);
 }
else{
$country=$this->project['country'];
}
 
            $action = $this->settings->checkAction($getaction);
            if ($this->device == NULL) {
                new Error('Forbidden. Device Type is not correct', 403);
            }
            if (!$country) {
                new Error('Forbidden. Project is not correct', 403);
            }
            if (!$action) {
                new Error('Forbidden. Unsupported Method Request', 403);
            }
            $params = $this->getParams($country);
            $class = ucfirst($_GET['action']) . "Request";
            $request = new $class($params);
        }
    }
    private function getParams($country) {
        $params['device'] = $this->device;
        $reguest = $this->settings->getRequest();
        $default = NULL;
        foreach ($reguest as $key => $arr) {
        foreach ($arr as $a_key => $a_val) {
        foreach ($a_val as $aa_key => $aa_val) {
                
                if ($aa_key == 'name') {
                    if(isset($arr[$a_key]['default'])){
                    $default = $arr[$a_key]['default'];
                    }
                    if (isset($_GET[$aa_val])) {
                        $default = $_GET[$aa_val];
                    }
                    if ($aa_val == 'project_country') {
                        $default = $country;
                    }
                    $params[$aa_val] = $default;
                    $default= NULL;
                }
            }
          }
        }
        return $params;
    }
}
?>