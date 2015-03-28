<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of trackingRequest
 *
 * @author alekk
 */
class trackingRequest {

    private $url, $shares, $views, $clicks, $base_url, $settings, $social_network, $tracking_api_url;

    function __construct($params) {

        $this->settings = new ApiSettings();
        $this->base_url = $this->settings->getURL($params['country']);
        $this->tracking_api_url = $this->settings->getTrackingApiUrl();
        if (empty($params['url'])) {
            new Error("No url provided", 203);
        }
        if (empty($params['update_blog']) && empty($params['save_tracks'])) {
            new Error("No task", 203);
        }
        $this->url = isset($params['url']) ? $params['url'] : NULL;

        $this->shares = isset($params['shares']) ? $params['shares'] : NULL;
        $this->views = isset($params['views']) ? $params['views'] : NULL;
        $this->clicks = isset($params['clicks']) ? $params['clicks'] : NULL;
        $this->social_network = isset($params['social_network']) ? $params['social_network'] : NULL;
       


        if ($params['update_blog'] == 1) {

            echo $this->updateBlog();
        }

        if ($params['save_tracks'] == 1) {

            echo $this->saveTracks();
        }
    }

    public function updateBlog() {

        $params['url'] = $this->url;
        $params['shares'] = $this->shares;
        $params['clicks'] = $this->clicks;
        $params['views'] = $this->views;
        $param_query = http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-2iionxhsy9aq5rpk06i08qvdcwdg5119');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, $this->base_url . '/api/2/');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param_query);

        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;
    }

    public function saveTracks() {

        $params['url'] = $this->url;
        if ($this->social_network != NULL) {
            $params['social_network'] = $this->social_network;
        }
        
            $params['update_blog'] = 1;
     

        //echo $this->tracking_api_url;
        $param_query = http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-2iionxhsy9aq5rpk06i08qvdcwdg5102');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, $this->tracking_api_url.'updateshares');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param_query);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}

?>