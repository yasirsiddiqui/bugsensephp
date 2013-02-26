<?php
Class ExceptionLogger {
   
    function logException($appversion,$appname,$exception) {
        
        $apikey = "ENTER_YOUR_API_KEY_HERE"; /// Set your api key here.
        
        if($apikey==""||$apikey=="ENTER_YOUR_API_KEY_HERE") {
            
            echo "Please provide a valid api key";
            exit;
        }
        
        $apiurl = "https://bugsense.appspot.com/api/errors";
        $httpheader = array("X-BugSense-Api-Key: $apikey");
        
        /// Prepare array containing data to be posted to BugSense server 
        
        $jsondataarray = array(
            
            'client' => array('name'=>$appname),
            'request' => array('remote_ip'=>$_SERVER["REMOTE_ADDR"]),
            'exception' => array('message' =>$exception->getMessage(),'where' => $exception->getFile().":".$exception->getLine(),
            'klass' => "PHP exception",
            'backtrace' => $exception->getTraceAsString()),
            'application_environment' => array('phone' => $_SERVER['SERVER_NAME'],'appver' => $appversion,
            'appname' => $appname,'osver' => '1.0')
            );
        
        /// Convert data array to JSON
        
        $postdata = json_encode($jsondataarray);
        
        /// Post data to BugSense server
        
        $content = $this->postData($apiurl,$httpheader,$postdata);
        
        $jsonobj = json_decode($content);

        if(@$jsonobj->error&&@$jsonobj->error!="") {
    
            return false;
        }
        else {
        
         return true;
        
        }
    }
    
    function postData($url,$headers,$jsondata) {
        
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );   
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    
        $content = curl_exec( $ch );
        return $content;
    }
}

?>