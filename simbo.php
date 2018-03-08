<?php
   class SimboObject {

      private $so;
      private $tableName;
      private $fieldList;

      function __construct($simbo_obj, $table_name, $fields) {
         $this->$so = $simbo_obj;
         $this->tableName = $table_name;
         $this->$fieldList = $fields;
      }

      public function AddRow($insArray) {
         $sql = "INSERT INTO ". $this->tableName. " (firstname, lastname, email) VALUES ('John', 'Doe', 'john@example.com')";
         return $this->$so->Query($sql);
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
      function __construct($servername, $username, $password) {
         $this->$conn = new mysqli($servername, $username, $password);
         if ($this->$conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);

        }
      }

      function __destruct() {
         if (!$this->$conn->connect_error) {
            mysqli_close($this->$conn);
         }
      }

      public function isConError(){
         return $this->$conn->connect_error;
      }

      public function getConError() {
         return $this->Error('connection', $this->$conn->connect_error);
      }

      public function Query($sql)
      {
         if ($this->$conn->query($sql) === TRUE) {
            echo "New record created successfully";
            return $this->OK();
         } else {
            $err = "Error: " . $sql . ";" . $this->$conn->error;
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