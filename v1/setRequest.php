<?php

class SetRequest {

    private $settings;

    public function __construct($params = array()) {
     

            $default = array();
            $getparams = array();
            $this->settings = new ApiSettings();
            $oFilter = new filterClass();
            $defaults = array('country' => NULL,
                'site_id' => 1,
                'form_id' => 1,
                'fname' => NULL,
                'lname' => NULL,
                'email' => NULL,
                'gender' => NULL,
                'zip' => NULL,
                'dob' => NULL,
                'lat' => NULL,
                'lng' => NULL,
                'facebook_id' => NULL,
                'google_account' => NULL);
            foreach ($_REQUEST as $k => $v) {
                $getparams[$k] = $v;
            }
            $getparams['form_id'] = $this->settings->getFormId($params['device'], $params['country']);
            $getparams['site_id'] = $this->settings->getSiteId($params['device'], $params['country']);


            if (isset($getparams['fname']) && $getparams['fname'] != '' && $getparams['fname'] != '0') {
                if (!$oFilter->checkName($getparams['fname'])) {
                    new Error('Not Acceptable. First name is not Correct', 406);
                }
            } else {
                new Error('Not Acceptable. First name is not Found', 406);
            }

            if (isset($getparams['email']) && $getparams['email'] != '' && $getparams['email'] != '0') {
                if (!$oFilter->checkEmail($getparams['email'])) {
                    new Error('Not Acceptable. E-mail is not Correct', 406);
                }
            } else {
                new Error('Not Acceptable. E-mail is not Found', 406);
            }


            /*
              if (!isset($getparams['google_account']) || $getparams['google_account'] == '' || $getparams['google_account'] == '0') {
              $ok[] = '0';
              new Error('Not Acceptable. Google Account is not Correct', 406);
              } else {
              $ok[] = '1';
              }
              if (!isset($getparams['facebook_id']) || $getparams['facebook_id'] == '' || $getparams['facebook_id'] == '0') {
              $ok[] = '0';
              new Error('Not Acceptable. Facebook ID is not Correct', 406);
              } else {
              $ok[] = '1';
              }
             */
            if (isset($getparams['lng']) && $getparams['lng'] != '') {
                if (!$oFilter->checkCoord($getparams['lng'])) {
                    new Error('Not Acceptable. Longitude is not Correct', 406);
                }
            }
            if (isset($getparams['lat']) && $getparams['lat'] != '') {
                if (!$oFilter->checkCoord($getparams['lat'])) {
                    new Error('Not Acceptable. Latitude is not Correct', 406);
                }
            }
            if (isset($getparams['zip'])) {
                if (!$oFilter->checkZip($getparams['zip'], 'us,ca') && $getparams['zip'] != '') {
                    new Error('Not Acceptable. Zip Code is not Correct', 406);
                }
            }
            if (isset($getparams['lname'])) {
                if (!$oFilter->checkName($getparams['lname'])) {
                    new Error('Not Acceptable. Last name is not Correct', 406);
                }
            }
            if (isset($getparams['dob'])) {
                if (!$oFilter->checkDate($getparams['dob'])) {
                    new Error('Not Acceptable. Date is not Correct', 406);
                }
            }
            if (isset($getparams['gender'])) {
                if (!$oFilter->checkGender($getparams['gender'])) {
                    new Error('Not Acceptable. Gender is not Correct', 406);
                }
            }
            $params_to_send = array_merge($defaults, $getparams);
            $send = new sendData($params_to_send);
            $output = $send->submitForm();
            if (!$output) {
                new Error('OK', 200);
            } else {
                echo $output;
            }
        }
    }


?>
