<?php

class filterClass {

    function __construct() {
        $this->settings['gender'] = array('m', 'M', 'f', 'F');
    }

    function checkCoord($coord){
        $rexSafety = "@-?\d{1,3}\.?\d+?@";
        if($this->filter($coord, $rexSafety)){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    /**
     *
     * @param type $zip
     * @param type $country us|ca|us,ca
     * @return boolean 
     */
    function checkZip($zip, $country='us') {
		if($country=='us'){
                $rexSafety = "@^\d{5}(-\d{4})?$@";
               
		}
                else if($country=='ca'){
		$rexSafety = "@^[ABCEGHJKLMNPRSTVXYabceghjklmnprstvwxyz]\d[ABCEGHJKLMNPRSTVWXYZabceghjklmnprstvwxyz]( )?\d[ABCEGHJKLMNPRSTVWXYZabceghjklmnprstvwxyz]\d$@";	
                
		}
                else if($country=='us,ca'){
		$rexSafety = "@(^[ABCEGHJKLMNPRSTVXYabceghjklmnprstvwxyz]\d[ABCEGHJKLMNPRSTVWXYZabceghjklmnprstvwxyz]( )?\d[ABCEGHJKLMNPRSTVWXYZabceghjklmnprstvwxyz]\d$)|(^\d{5}(-\d{4})?$)@";	
                 
		}
                
        if($this->filter($zip, $rexSafety)){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    function checkEmail($email) {
        if ($this->filterEmail($email) && $this->clear_data($email)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function clear_data($txt) {
        if (!get_magic_quotes_gpc()) {
            $str = "" . addslashes($txt) . "";
        } else {
            $str = "" . $txt . "";
        }
        $str = str_ireplace(' DELETE ', '', $str);
        $str = str_ireplace(' UPDATE ', '', $str);
        $str = str_ireplace(' SET ', '', $str);
        $str = str_ireplace(' ALTER ', '', $str);
        $str = str_ireplace(' DROP ', '', $str);
        $str = str_ireplace('<script', '', $str);

        return $str;
    }

    function filterEmail($email, $error = '406') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            new Error('Not Acceptable. E-mail is not correct', $error);
        }
        $domainarr = explode('@', $email);
        $domain = $domainarr[1];
        if (!checkdnsrr($domain, 'MX')) {
            new Error('Not Acceptable. E-mail is not correct', $error);
        }
        return TRUE;
    }

    function checkGender($gender) {
        if (in_array($gender, $this->settings['gender'])) {
            return TRUE;
        }
    }

    function checkDate($date) {
        $rexSafety = "@(0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)\d\d@";
        if($this->filter($date, $rexSafety)){
            $timestamp = strtotime($date);
            if ($timestamp) {
                $pieces = explode("/", date('m/d/Y', $timestamp));
                if (checkdate($pieces['0'], $pieces['1'], $pieces['2'])) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        else{
            return FALSE;
        }
    }

    function checkName($name, $error = '406') {
        $rexSafety = "@^[_a-zA-Z0-9а-яА-Я ]+$@";
        if ($this->isEmpty($name) || $this->filter($name, $rexSafety)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function filter($item, $pattern, $empty = FALSE) {
        if (preg_match($pattern, $item)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function isEmpty($item) {
        if (empty($item)) {
            return TRUE;
        }
    }

}

?>