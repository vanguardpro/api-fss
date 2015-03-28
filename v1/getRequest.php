<?php

class GetRequest {

    private $url, $settings, $params;

    public function __construct($params = array()) {




        $defaults = array(
            'device' => 'desktop',
            'country' => NULL,
            'project_id' => NULL,
            'post_id' => NULL,
            'start_date' => NULL,
            'end_date' => NULL,
            'category' => NULL,
            'error' => 0,
            'search' => NULL,
            'stats' => NULL,
            'limit' => 10,
            'skip' => 0
        );



        $this->settings = new ApiSettings();

        $limit = $this->settings->getLimit($params['limit']);
        $skip = $this->settings->getSkip($params['skip']);
        $params['skip'] = $skip;
        $params['limit'] = $limit;
        $this->params = array_merge($defaults, $params);
        //print_r($this->params);
        //
	 //$post= new Post($params);
        $cache = new Cache($params);
    }

}

?>