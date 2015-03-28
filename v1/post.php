<?php 
class Post{
	
	private $url, $params;
	function __construct($params){
		
		
	  $defaults=array (
	 'device'=>'desktop',
	 'post_id'=>NULL,
	 'start_date'=>NULL,
	 'end_date'=>NULL,
	 'category'=>NULL,
	 'error'=>NULL,
         'stats' => NULL,
         'search'=>NULL,
         'project_id' => NULL,
	 'country'=>'us'
	 );
	 
	 $this->settings=new ApiSettings();
	 
         if ($params['project_id'] != NULL) {
            $this->project = $this->settings->getProject('id', $params['project_id']);
            $this->url = $this->project['url'];
        } else {
            $this->url=$this->settings->getURL($params['country']);
        }
	 
	 $this->params = array_merge($defaults, $params);
	 
		
		
	}
	
	public function getPost(){
	 
    $param_query= http_build_query($this->params); 
     //print_r($this->params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:key-2iionxhsy9aq5rpk06i08qvdcwdg5119');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_URL, $this->url.'/api/1/?limit='.$this->params['limit'].'&skip='.$this->params['skip']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param_query);

    $result = curl_exec($ch);
    curl_close($ch);
 
    return $result;
  }



}

?>
