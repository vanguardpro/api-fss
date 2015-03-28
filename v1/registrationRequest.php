<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of registrationRequest
 *
 * @author makc64
 */
class registrationRequest {

    function __construct($params) {
        if (empty($params['reg_id'])) {
            new Error('Registration Id not correct', 406);
        } else {
            $this->settings = new ApiSettings();
            $oFilter = new filterClass();
            
            $params['site_id'] = $this->settings->getSiteId($params['device'], $params['country']);


            if (isset($params['lng']) && $params['lng'] != '') {
                if (!$oFilter->checkCoord($params['lng'])) {
                    new Error('Not Acceptable. Longitude is not Correct', 406);
                }
            }
            if (isset($params['lat']) && $params['lat'] != '') {
                if (!$oFilter->checkCoord($params['lat'])) {
                    new Error('Not Acceptable. Latitude is not Correct', 406);
                }
            }
                        if(!empty($params['lat'])&&!empty($params['lng'])){
			 $coordinates = new Coordinates($params['lat'], $params['lng']);               
                         $location = $coordinates->getGeoCounty($coordinates->latlng);
				
			foreach($location as $k=>$v){
				if(!isset($params[$k])){
					$params[$k]=$v;
				}
			}
                        }
            //echo "<pre>".print_r($params, TRUE)."</pre>"; exit();
            $send = new sendData($params);
            $output = $send->submitRegistrationId();
           // var_dump($output);
	    $ruf_out=json_decode($output);
            if (is_numeric($ruf_out)) {
                new Error('new entry '.$ruf_out.' created', 201);
	    }
            else if(isset($ruf_out->message)){
                new Error($ruf_out->message, $ruf_out->code);
            }
            else{
				if(is_array($ruf_out)&&count($ruf_out)>0){
				$i=0;
				foreach($ruf_out as $data){
				if($i==1){
				if($data=='1062'){
				$error="application is already registered"; 
				$code=302;
				}else{
					$error=$output;
					$code=303;
				}
				}
				$i++;
				}
					
				new Error($error, $code);
				}
				else{
				new Error('Something went wrong', 406);
				}
			}
			
        }
    }
}
