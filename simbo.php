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

      public function DelRow($id) {
         $sql = "DELETE FROM ".$this->tableName." WHERE id=" . $id;
         return $this->so->Query($sql);
      }

      public function GetListSimple($where) {
          return $this->GetList($where, 100, 0, $this->fieldList);
      }

      public function GetList($where, $limit, $offset, $colArray) {
         $columns = '';
         $first = '';
         if(!$colArray) {
            $colArray = $this->fieldList;
         }
         foreach ($colArray as $value) {
            $columns = $columns . $first . $value;
            $first = ',';
         }
         if( $limit ) {
            $limit = ' LIMIT ' . $limit;
         }
         if( $offset ) {
            $offset = ' LIMIT ' . $offset;
         }
         if( $where ) {
            $where = 'WHERE ' . $where;
         }
         $sql = "SELECT " . $columns . " FROM " . $this->tableName . " " . $where . $limit . $offset;
         return $this->so->QueryGet($sql, $colArray);
      }

      public function SetData($id, $setArray) {
         $columns = '';
         $first = '';
         foreach ($setArray as $key => $value) {
            $columns = $columns . $first . $key . "='" . $value . "'";
            $first = ', ';
         }
         $sql = "UPDATE ".$this->tableName." SET ".$columns." WHERE id=".$id;
         return $this->so->Query($sql);
      }

      public function GetData($id) {
         return $this->GetList(('id = '.$id), 1, 0, $this->fieldList);
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

      public function Query($sql) {
         if ($this->conn->query($sql) === TRUE) {
            $data = 'NODATA';
            if( $this->conn->insert_id ) {
               $data = array('INSERT_ID' => $this->conn->insert_id);
            }
            return $this->OK($data);
         } else {
            $err = "Error: " . $sql . ";" . $this->conn->error;
            return $this->Error($sql, $err);
         }
      }

      public function QueryGet($sql, $columns) {
         $result = $this->conn->query($sql);
         $dataArr = array();
         if ($result && ($result->num_rows > 0)) {
            while($row = $result->fetch_assoc()) {
               $dataRowArr = array();
               foreach ($columns as $column) {
                  array_push($dataRowArr, $row[$column]);
               }
               array_push($dataArr, $dataRowArr);
            }
            return $this->Result('OK', array('SQL' => $sql, 'COUNT' => $result->num_rows, 'LIST' => $dataArr ), 'NOERROR');
         } else {
            return $this->Result('OK', array('SQL' => $sql, 'COUNT' => 0, 'LIST' => array() ), 'NOERROR');
         }
      }

      private function OK($data) {
         return $this->Result('OK', $data, 'NOERROR');
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