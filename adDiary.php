<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'dbconf.php';
include 'simbo.php';
include 'user.php';

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$obj = json_decode($json);
//echo json_encode($obj);
$simbo = new Simbo($servername, $username, $password, $dbname);
if($simbo->isConError()) {
   echo $simbo->getConError();
} else {
   $res = $simbo->Error('NOACTION', 'no action in bl');
   if( $obj->OBJ == 'User' ) {
      $object = new UserObject($simbo, $obj->OBJ, array('id', 'name', 'hkey', 'email'));
      $res = $object->Confirm($obj);
   }

   echo $res;
}

?>