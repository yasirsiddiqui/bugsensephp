<?php

include "exceptionlogger.php";
$logger = new ExceptionLogger();

//trigger exception in a "try" block
try
  {
  checkNumValue(2);
  //If the exception is thrown, this text will not be shown
  echo 'No exception occured';
  }

//catch exception
catch(Exception $e)
  {
    $logger->logException("5.0","Customer Relationship Management System",$e);
  }
  
try {
    
    ///// Try connecting to mysql server a
    $connected = connectToDbServer();
    
    if($connected) {
        
        echo "Connected with server"; 
    }
    
    $connected = connectAgain();
    
    if(!$connected) {
        
        //// Unable to connect so throw exception
        
        throw new Exception("Unable to connect to database please try again later");
    }
       
}
catch (Exception $e) {
    
    $logger->logException("5.0","Customer Relationship Management System",$e);
}


function checkNumValue($number)
  {
  if($number<=5)
    {
        throw new Exception("Number should be greater than 5");
    }
  return true;
  }
  
function connectToDbServer() {
    
    return true;
}

function connectAgain() {
    
    return false;
}


?>