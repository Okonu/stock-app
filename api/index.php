<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
 
include_once 'config/database.php';  
include_once 'classes/API.php';  


$conn = Database::getInstance();
$api  = new API($conn);    
 

if (empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true) ? : [];
}   $data = $_POST;


 
 
//////////////////////// USER ACTIONS BELOW /////////////

if(isset($_POST['login'])) { 
    
    $user = $api->login($data);
    
	if (empty($user)) {
		echo json_response(500, 'Invalid credentials, please try again!',true);
        exit();
	} else
		echo response($user);
        exit();

} 
 
 
 
else if(isset($_POST['get_warehouse'])) { 
    
    echo response($api->get_warehouse());
    exit();
    
}
 
else if(isset($_POST['get_warehouse_bays'])) { 
    
    echo response($api->get_warehouse_bays());
    exit();
    
}
 
 
else if(isset($_POST['get_bays'])) { 
    
    echo response($api->get_bays($data["warehouse_id"]));
    exit();
    
}

 
 
 
else if(isset($_POST['get_owners'])) { 
    
    
    echo response($api->get_owners());
    exit();
    
}

 
 
 
else if(isset($_POST['get_gardens'])) { 
    
   $owner_id = "";
   
  
    
    if(!empty( $_POST['owner_id']))
    
    $owner_id = $_POST['owner_id'];
    
    echo response($api->get_gardens($owner_id));
    exit();
    
}

 
 
 
else if(isset($_POST['get_grades'])) { 
    
    echo response($api->get_grades());
    exit();
    
}

 
 
 
else if(isset($_POST['get_packages'])) { 
    
    echo response($api->get_packages());
    exit();
    
}


 
else if(isset($_POST['submit_data'])) { 
    
    // echo response($data);
    // exit();
    if($api->record_stock_entry($data))
        echo json_response(200, 'Data posted successfuly',true);
    else
        echo json_response(500, 'something went wrong, please try again!',true);
    exit();
    
}
 

 
  
else if(isset($_POST['get_entry_count'])) { 
    $uid = $_POST['get_entry_count'];
    
    echo response($api->get_entry_count($uid));
    exit();
    
}


  
else if(isset($_POST['get_recent_entries'])) { 
    $uid = $_POST['get_recent_entries'];
    
    echo response($api->get_recent_entries($uid));
    exit();
    
}


  
else if(isset($_POST['get_last_30days_entries'])) { 
    $uid = $_POST['get_last_30days_entries'];
    
    echo response($api->get_last_30days_entries($uid));
    exit();
    
}


 
else if(isset($_POST['update_entry'])) { 

    if($api->update_stock_entry($data))
        echo json_response(200, 'Data update successfuly',true);
    else
        echo json_response(500, 'something went wrong, please try again!',true);
    exit();
    
}
 
 

else{ 
    //echo response($data);
    echo json_response(403, '403 Forbiden Access!',true); 
}




## HELPER FUNCTIONS

function response($data){
    
    header("Content-Type: application/json");
    return json_encode($data);
}

function json_response($code = 200, $message = null, $error = false, $token = '')
{
    // clear the old headers
    header_remove();
    // set the actual code
    http_response_code($code);
    // set the header to make sure cache is forced
    header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
    // treat this as json
    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        403 => '403 Forbiden Access',
        404 => '404 Not Found',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
    );
    // ok, validation error, or failure
    header('Status: '.$status[$code]);
    // return the encoded json
    if ($error){
        return json_encode(array('success' => $code === 200, 'message' => $message));
    }
    return json_encode(array('success' =>  $code === 200, 'message' => $message));
}

 























