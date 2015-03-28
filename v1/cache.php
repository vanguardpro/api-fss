<?php
class Cache{
	
    private $params, $file, $TTL, $output,$settings, $filetype, $is_JSON;
    function __construct($params) {
		
	$sha1hash=$this->hashQuery($params);
        
	//$this->params = $params;
	
	$this->settings = new ApiSettings();
        $settings=$this->settings;
        $request_type=$this->settings->getRequestType($params);
        $this->TTL = $this->settings->getTTL($request_type);
	
	$maindir=$settings->getMainDir();
        $this->filetype=$settings->getFileType();
	$ds=$settings->getDS();
	 //cache enabled
        if($settings->isCacheEnabled()){
        
	if($settings->isCacheDeviceCountryBased()){
		$countryfolder=$params['country'];
		$devicefolder=$params['device'];
		if($settings->checkTest()){
                    $countryDir=$maindir.$ds.'test'.$ds.$countryfolder;
                }else{
                    $countryDir=$maindir.$ds.$countryfolder;
                }
		$this->creatDir($countryDir);
		$this->changeMode($countryDir);
		$deviceDir=$countryDir.$ds.$devicefolder;
		
		$this->creatDir($deviceDir);
		$this->changeMode($deviceDir);
		$this->file = $deviceDir.$ds. $sha1hash. $this->filetype;
	}
	
	else{
		
		$this->file = $maindir .$ds. $sha1hash. $this->filetype;
	}
	
 
    
    
if(!file_exists($this->file)){
    
        $post = new Post($params); 
        $this->output = $post->getPost();
        $this->is_JSON=$this->settings->isJson($this->output);
  	  if($this->is_JSON){    
              $this->writeFile();
                $this->changeMode();
                          }
}
else{
	
	$timer=new Timer($this->TTL, $this->file);
	if($timer->checkTimer()){
        //echo "File Expired";
        $post = new Post($params) ;
        $this->output = $post->getPost();
	    $this->writeFile();
  	}    
    }   //displays always thru json file
        $this->readFile();
        }
        //cache disabled
        else{
           $post = new Post($params); 
           $this->output = $post->getPost();
           if($this->settings->isJson($this->output))
           header('Content-Type: application/json');
           echo $this->output;
        }
    }
private function readFile(){
	$string=file_get_contents($this->file);
	if($this->filetype=='.json'){
            if($this->settings->isJson($string))
	header('Content-Type: application/json');
	}
	echo $string;
}
private function changeMode($file=NULL){
if($file==NULL){
$file=$this->file;
}
chmod($file, 0777);
}
private function writeFile(){
return file_put_contents($this->file, $this->output); 
}
private function creatDir($path){
	if (!is_dir($path)) {
 
  mkdir($path, 0777, true);
}
}
private function hashQuery($data){
    $string='';
	
	if(is_array($data)&&count($data>0)){
		if(isset($data['post_id'])){
		$string="post_id".$data['post_id']."country".$data['country']."device".$data['device']."debug".$data['debug']."related".$data['related'];
		}
		
		else{
		foreach($data as $k=>$v){
		$string.=$k.$v;
		}	
		}
		
	}
	return sha1($string);
	
	
}
       
 
  
  
  
  
  
  
  
  
  
  
 }
?>