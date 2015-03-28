<?php

class ApiSettings {

    private $settings;

    public function __construct() {
        $this->settings['api_key'] = array('android' => '1qA34-80', 'android_web' => '1qA34-800','android_new' => '1qA34-803',
		    'tablet' => '1qA34-801','tablet_web' => '1qA34-802',
            'iphone' => '1qA34-81', 'iphone_web' => '1qA34-810', 'ipad' => '1qA34-811', 'ipad_web' => '1qA34-812', 'iphone_new' => '1qA34-813',
            'desktop' => '1Qa34-82', 'mobile' => '1Qa34-83',);
        $this->settings['form_settings'] = array('android' => array('api_key' => '1qA34-80',
                'country' => array(
                    'us' => array('url' => 'http://womanfreebies.com', 'site_id' => 3, 'form_id' => 4))),
            'iphone' => array('api_key' => '1qA34-81',
                'country' => array(
                    'us' => array('url' => 'http://womanfreebies.com', 'site_id' => 4, 'form_id' => 5))),
            'desktop' => array('api_key' => '1Qa34-82',
                'country' => array(
                    'us' => array('url' => 'http://womanfreebies.com', 'site_id' => 5, 'form_id' => 6))),
            'mobile' => array('api_key' => '1Qa34-83',
                'country' => array(
                    'us' => array('url' => 'http://m.womanfreebies.com', 'site_id' => 6, 'form_id' => 7)))
        );
        $this->settings['action'] = array('get', 'set', 'tracking', 'registration');
        $this->settings['url'] = array('us' => 'http://freesweetsweeps.com',
            'ca' => 'http://freesweetsweeps.ca');
        
         //param country is temp and will be depricated:
        $this->settings['project'] = array(
            array('id' => 1, 'url' => 'http://freesweetsweeps.com', 'name' => 'FSS', 'country' => 'us'),
            array('id' => 2, 'url' => 'http://freesweetsweeps.ca', 'name' => 'FSSCA', 'country' => 'ca'),
            array('id' => 3, 'url' => 'http://freesweetsweeps.com.local', 'name' => 'FSSLOCAL', 'country' => 'us'),



        );
        $this->settings['request'] = array(
            'project' => array(
                array('name' => 'project_id'),
                array('name' => 'project_country'),
                array('name' => 'action'),
            ),
            'stats' => array(
                array('name' => 'stats', 'default' => 0),
            ),
            'post' => array(
                array('name' => 'post_id', 'default' => NULL),
            ),
            'page' => array(
                array('name' => 'page_id', 'default' => NULL),
            ),
            'search' => array(
                array('name' => 'search', 'default' => NULL),
            ),
            'taxonomy' => array(
                array('name' => 'category'),
                array('name' => 'topics'),
                array('name' => 'brands'),
                array('name' => 'brand'),
                array('name' => 'contests'),
                array('name' => 'coupons'),
            ),
            'form' => array(
                array('name' => 'fname'),
                array('name' => 'lname'),
                array('name' => 'zip'),
                array('name' => 'gender'),
                array('name' => 'city'),
                array('name' => 'state'),
                array('name' => 'country'),
                array('name' => 'lat'),
                array('name' => 'lng'),
                array('name' => 'reg_id'),
                array('name' => 'reg_id_na'),
                array('name' => 'reg_country'),
                array('name' => 'reg_state_full'),
                array('name' => 'reg_state_short'),
                array('name' => 'device_type'),
                array('name' => 'device_model'),
                array('name' => 'device_provider'),
                array('name' => 'device_os'),
                array('name' => 'device_os_v'),
                array('name' => 'device_provider_name'),
                array('name' => 'device_provider'),
            ),
            'identyfier' => array(
                array('name' => 'limit', 'default' => 10),
                array('name' => 'skip', 'default' => 0),
                array('name' => 'debug', 'default' => 0),
                array('name' => 'error', 'default' => 0),
                array('name' => 'fletter', 'default' => 0),
                array('name' => 'exclusive', 'default' => 0),
                array('name' => 'save_tracks', 'default' => 0),
                array('name' => 'update_blogs', 'default' => 0),
                array('name' => 'related'),
                array('name' => 'start_date'),
                array('name' => 'end_date'),
                array('name' => 'url'),
                array('name' => 'shares'),
                array('name' => 'views'),
                array('name' => 'clicks'),
                array('name' => 'social_network'),
                array('name' => 'tyapp', 'default' => 0),
            ),
        );
        $this->settings['scope'] = array('limit' => 300, 'skip' => 0);
        $this->settings['TTL'] = array('post' => 7200, 'feed' => 300, 'page' => 8640);
        $this->settings['cache'] = array('enabled' => TRUE,
            'filetype' => '.json',
            'maindir' => '../jsoncache',
            'devicecountrybased' => TRUE,
            'DS' => DIRECTORY_SEPARATOR);
        $this->settings['tracking_api_url'] ='http://system.adjump.com/beta/tracking/api/v1/';
	$this->settings['test'] = TRUE;	
    }

    public function getSettings() {
        $this->settings = new stdClass();
        return (object) $this->settings;
    }

    public function getFormId($device, $country) {
        if (isset($this->settings['form_settings'][$device]['country'][$country]['form_id'])) {
            return $this->settings['form_settings'][$device]['country'][$country]['form_id'];
        }
    }

    public function getTrackingApiUrl() {
        if (isset($this->settings['tracking_api_url'])) {
            return $this->settings['tracking_api_url'];
        }
    }

    public function getSiteId($device, $country) {
        if (isset($this->settings['form_settings'][$device]['country'][$country]['site_id'])) {
            return $this->settings['form_settings'][$device]['country'][$country]['site_id'];
        }
    }

    public function getDevice($api_key) {
        foreach ($this->settings['api_key'] as $device => $key) {
            if ($api_key == $key) {
                return $device;
            }
        }
    }

    public function getURL($country_abbr) {
        foreach ($this->settings['url'] as $country => $url) {
            if ($country == $country_abbr) {
                return $url;
            }
        }
    }
public function getRequest() {
        return $this->settings['request'];
    }

    public function getRequestType($params) {
        $request = $this->settings['request'];
        if (isset($params['action']) && $params['action'] == 'get') {
            foreach ($request as $keys => $val) {
                foreach ($val as $val_key => $val_val) {
                    foreach ($val_val as $aa_key => $aa_val) {
                        if ($keys == 'stats') {
                            if ($aa_key == 'name') {
                                if (!empty($params[$aa_val])) {
                                    return 'stats';
                                }
                            }
                        } else if ($keys == 'post') {
                            if ($aa_key == 'name') {
                                if (!empty($params[$aa_val])) {
                                    return 'post';
                                }
                            }
                        } else if ($keys == 'page') {
                            if ($aa_key == 'name') {
                                if (!empty($params[$aa_val])) {
                                    return 'page';
                                }
                            }
                        } else if ($keys == 'taxonomy') {
                            if ($aa_key == 'name') {
                                if (!empty($params[$aa_val])) {
                                    return 'taxonomy';
                                }
                            }
                        } else if ($keys == 'search') {
                            if ($aa_key == 'name') {
                                if (!empty($params[$aa_val])) {
                                    return 'search';
                                }
                            }
                        }
                    }
                }
            }
            return 'feed';
        }
        return FALSE;
    }
    
    public function getLimit($limit) {
        if ($this->settings['scope']['limit'] >= $limit && $limit > 0) {
            return $limit;
        } else {
            return $this->getLimitDefault();
        }
    }
    public function getLimitDefault() {
        $default = NULL;
        if (isset($this->settings['request']['identyfier'])) {
            $identyfier = $this->settings['request']['identyfier'];
            foreach ($identyfier as $key => $val) {
                foreach ($val as $akey => $aval) {
                    if ($akey == 'name') {
                        if ($aval == 'limit') {
                            return $identyfier[$key]['default'];
                        }
                    }
                }
            }
        }
    }
    public function getSkip($skip) {
        if ($skip > 0) {
            return $skip;
        } else {
            return $this->settings['scope']['skip'];
        }
    }

    public function getTTL($post_id = NULL) {
        if ($post_id != NULL) {
            $page = 'post';
        } else {
            $page = 'feed';
        }
        return $this->settings['TTL'][$page];
    }

    public function isCacheDeviceCountrybased() {
        return $this->settings['cache']['devicecountrybased'];
    }

    public function isCacheEnabled() {
        return $this->settings['cache']['enabled'];
    }

    public function getDS() {
        return $this->settings['cache']['DS'];
    }

    public function getMainDir() {
        return $this->settings['cache']['maindir'];
    }

    public function getFileType() {
        return $this->settings['cache']['filetype'];
    }

    public function checkCountry($name) {
        if (array_key_exists($name, $this->settings['url'])) {
            return TRUE;
        }
    }

    public function checkAction($name) {
        if (in_array($name, $this->settings['action'])) {
            return TRUE;
        }
    }

    public function checkTest() {
        if ($this->settings['test']){
            return TRUE;
        }
    }
    
       public function getProject($key, $value) {
        foreach ($this->settings['project'] as $projects => $project) {


            if (array_key_exists($key, $project)) {

                if ($project[$key] == $value) {
                    return $project;
                }
            }
        }
        return FALSE;
    }
    public function isJson($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
    public function returnOutput($result, $http_response_header){
        if (isset($http_response_header[0])) {
            $pos = strrpos($http_response_header[0], '200');
        
        if ($pos !== FALSE) {
            return $result;
        } else {
            $error = array();
            $errors = explode(" ", $http_response_header[0]);
            if (isset($errors[1]) && is_numeric($errors[1])) {
                $error["error"]["code"] = $errors[1];
                $error["error"]["message"] = $http_response_header[0];
            } else {
                $error["error"]["code"] = 500;
                $error["error"]["message"] = 'Something Went Wrong';
            }
           return json_encode($error);
            
        }
        }else{
           $error["error"]["code"] = 404;
           $error["error"]["message"] = 'Content Not Found';
           return json_encode($error);
        }
    }
}

?>
