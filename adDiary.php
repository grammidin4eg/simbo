<?php

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

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
   } else if( $obj->OBJ == 'AdList' ){
      $object = new SimboObject($simbo, $obj->OBJ, array('id', 'date', 'value1', 'value2', 'value3', 'value4', 'description', 'meds'));
      //SELECT * FROM `AdList` WHERE user = 14 AND MONTH(date) = 5 ORDER BY date
      //List
      $obj->LISTWHERE = 'user = '.$obj->USERID.' AND MONTH(date) = '.$obj->CURMONTH.' ORDER BY date';
      //$obj->ADDARRAY = array('field1' => $params[0], 'field2' => $params[1], 'field3' => $params[2];
      //$obj->SETARRAY = array('field1' => $params[0], 'field2' => $params[1];
      $res = $object->ConfirmStd($obj);
   }

   echo $res;
}

?>