<?php

/* Form Submit Based on API created for tracking system */

class SendData {

    private $paramQuery, $apiKey, $method, $apiURL, $apiAction;

    function __construct($params) {
        //API Details//
        $this->apiKey = 'api:key-2iionxhsy9aq5rpk06i08qvdcwdg5102';
        $this->method = 'POST';
		//$this->apiURL = 'http://track.womanfreebies.com/tracking/api/v1/';
		$this->settings = new ApiSettings();
		$this->tracking_api_url = $this->settings->getTrackingApiUrl();
       
		
        //API Details//

        $detect = new MobileDetect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'desktop');
        $scriptVersion = $detect->getScriptVersion();

        $ip = $_SERVER['REMOTE_ADDR'];
        $cookie = $_COOKIE;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $device_type = $deviceType;

        if (isset($_COOKIE['__afsb']) && !isset($params['__afsb'])) {
            $params['__afsb'] = $_COOKIE['__afsb'];
        }

        $params['device-type'] = $device_type;
        $params['user_agent'] = $user_agent;
        $params['ip'] = $ip;
        $params['cookie'] = $cookie;
        //echo print_r($params, TRUE);exit();
        $this->paramQuery = http_build_query($params);
    }

    function submitRegistrationId() {
        $this->apiAction = 'registration';
        return $this->sendData();
    }
    
    function submitForm() {
        $this->apiAction = 'addform';
        return $this->sendData();
    }

    function sendFBInfo() {
        $this->apiAction = 'addfbjson';
        return $this->sendData();
    }

 

    private function sendData() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_URL, $this->tracking_api_url . $this->apiAction);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->paramQuery);

        $result = curl_exec($ch);
        $sendErr = curl_error($ch);
        $sendErrNo = curl_errno($ch);
        curl_close($ch);
        
        if($sendErrNo){
            $result = false;
        }
        //echo $this->tracking_api_url . $this->apiAction;
        return $result;
    }

}