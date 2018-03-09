<?php
   class SimboObject {

      private $so;
      private $tableName;
      private $fieldList;

      function __construct($simbo_obj, $table_name, $fields) {
         $this->so = $simbo_obj;
         $this->tableName = $table_name;
         $this->fieldList = $fields;
      }

      public function AddRow($insArray) {
         $columns = '(';
         $colValues = '(';
         $first = '';
         foreach ($insArray as $key => $value) {
            $columns = $columns . $first . $key;
            $colValues = $colValues . $first . "'" . $value . "'";
            $first = ',';
         }
         $columns = $columns . ')';
         $colValues = $colValues . ')';
         $sql = "INSERT INTO ". $this->tableName. " ".$columns." VALUES ".$colValues;
         return $this->so->Query($sql);
      }

      public function DelRow($arg) {

      }

      public function GetList($arg) {

      }

      public function SetData($arg) {

      }

      public function GetData($arg) {

      }
   }

   class Simbo {
      private $conn;
      function __construct($servername, $username, $password, $dbname) {
         $this->conn = new mysqli($servername, $username, $password, $dbname);
      }

      function __destruct() {
         if (!$this->conn->connect_error) {
            mysqli_close($this->conn);
         }
      }

      public function isConError(){
         return $this->conn->connect_error;
      }

      public function getConError() {
         return $this->Error('connection', $this->conn->connect_error);
      }

      public function Query($sql)
      {
         if ($this->conn->query($sql) === TRUE) {
            return $this->OK();
         } else {
            $err = "Error: " . $sql . ";" . $this->conn->error;
            return $this->Error($sql, $err);
         }
      }

      private function OK() {
         return $this->Result('OK', 'NODATA', 'NOERROR');
      }

      private function Error($sql, $error) {
         $err = "Error: " . $sql . ";" . $error;
         return $this->Result('ERROR', 'NODATA', $err);
      }

      private function Result($result, $data, $error)
      {
         $arr = array('RESULT' => $result, 'DATA' => $data, 'ERROR' => $error);
         return json_encode($arr);
      }
   }


?>