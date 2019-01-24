<?php
    class RESULT {
        const OK = 'OK';
        const ERROR = 'ERROR';
    }

    class RESTYPE {
        const NONE = 'NONE';
        const SCALAR = 'SCALAR';
        const RECORD = 'RECORD';
        const RECORDSET = 'RECORDSET';
    }

    class FORMAT {
        const INT = 'INT';
        const STRING = 'STRING';
        const BOOLEAN = 'BOOLEAN';
        const FLOAT = 'FLOAT';
        const VOID = 'VOID';
    }

    class SimboObject {

        protected $inObj;
        protected $result;
        private $conn;
        private $wasError;
        protected $fieldList;

        function __construct($inObj, $dbParam) {
            $this->inObj = $inObj;
            $this->wasError = false;
            $this->conn = new mysqli($dbParam['servername'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
        }

        function __destruct() {
            if (!$this->conn->connect_error) {
                mysqli_close($this->conn);
            }
        }

        private function isConError(){
            return $this->conn->connect_error;
        }

        private function createResultObject($result, $type, $data, $error, $count, $columns, $format) {
            $this->result = array('RESULT' => $result, 'TYPE' => $type, 'DATA' => $data, 'ERROR' => $error, 'COUNT' => $count, 'COLUMNS' => $columns, 'FORMAT' => $format);
            return $this->result;
        }

        protected function OK($data, $restype=RESTYPE::SCALAR, $count=0, $columns=array(), $format=array()) {
            return $this->createResultObject(RESULT::OK, $restype, $data, '', $count, $columns, $format);
        }

        protected function Error() {
            $this->wasError = true;
            return $this->createResultObject(RESULT::ERROR, RESTYPE::SCALAR, $this->conn->errno, $this->conn->error, 1, array(), array());
        }

        protected function isError() {
            return ($this->isConError() || $this->wasError);
        }

        protected function getResData() {
            return $this->result['DATA'];
        }

        protected function getResDataRow() {
            return $this->getResData()[0];
        }

        protected function getSqlBool($value) {
            return ($value == '1');
        }

        protected function getConError() {
            return $this->Error('ConError', $this->conn->connect_error);
        }
        protected function Query($sqlText) {
            if ($this->isConError()) {
                return $this->getConError();
            }
            if ($this->conn->query($sqlText) === TRUE) {
                $data = 'NODATA';
                $type = RESTYPE::SCALAR;
                if( $this->conn->insert_id ) {
                    $data = array('INSERT_ID' => $this->conn->insert_id);
                    $type = RESTYPE::RECORD;
                }
                return $this->OK($data, $type, 1);
            } else {
                return $this->Error();
            }
        }

        protected function GetFieldListStr($colArray, $useQuotes=false) {
            $columns = '';
            $first = '';
            $quotes = '';

            if ($useQuotes) {
                $quotes = "'";
            }
            if(!$colArray) {
                $colArray = $this->fieldList;
            }
            foreach ($colArray as $value) {
                $columns = $columns . $first . $quotes . $value . $quotes;
                $first = ',';
            }
            return $columns;
        }

        protected function QueryGet($sqlText, $columns, $format) {
            if ($this->isConError()) {
                return $this->getConError();
            }
            $result = $this->conn->query($sqlText);
            $dataArr = array();
            if ($result && ($result->num_rows > 0)) {
                while($row = $result->fetch_assoc()) {
                    $dataRowArr = array();
                    foreach ($columns as $column) {
                        array_push($dataRowArr, $row[$column]);
                    }
                    array_push($dataArr, $dataRowArr);
                }
                return $this->OK($dataArr, RESTYPE::RECORDSET, $result->num_rows, $columns, $format);
            } else if ($this->conn->errno) {
                return $this->Error();
            } else {
                return $this->OK(array(), RESTYPE::RECORDSET, 0, $columns);
            }
        }

        protected function getAddRowSql($columns, $values) {
            return "INSERT INTO ".$this->getObjName()." (".$this->GetFieldListStr($columns).") VALUES (".$this->GetFieldListStr($values, true).")";
        }

        protected function getDelRowSql($id) {
            return "DELETE FROM ".$this->getObjName()." WHERE id=" . $id;
        }

        protected function getUpdateRowSql($id, $setArray) {
            $columns = '';
            $first = '';
            foreach ($setArray as $key => $value) {
                $columns = $columns . $first . $key . "='" . $value . "'";
                $first = ', ';
            }
            return "UPDATE ".$this->getObjName()." SET ".$columns." WHERE id=".$id;
        }

        protected function getRow($columns, $where) {
            $sqlText = "SELECT " . $this->GetFieldListStr($columns) . " FROM " . $this->getObjName() . " WHERE " . $where;
            return $this->QueryGet($sqlText, $columns, array());
        }

        public function getParams() {
            return $this->inObj->PARAMS;
        }

        public function getNavigation() {
            return $this->inObj->NAVIGATION;
        }

        public function getObjName() {
            return $this->inObj->OBJECT;
        }

        public function getResult() {
            return json_encode($this->result);
        }
    }
?>