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

    class SimboObject {

        protected $inObj;
        private $result;
        private $conn;

        function __construct($inObj, $dbParam) {
            $this->inObj = $inObj;
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

        private function createResultObject($result, $type, $data, $error, $count, $columns) {
            $this->result = array('RESULT' => $result, 'TYPE' => $type, 'DATA' => $data, 'ERROR' => $error, 'COUNT' => $count, 'COLUMNS' => $columns);
            return $this->result;
        }

        protected function OK($data, $restype, $count, $columns) {
            return $this->createResultObject(RESULT::OK, $restype, $data, '', $count, $columns);
        }

        protected function Error() {
            return $this->createResultObject(RESULT::ERROR, RESTYPE::SCALAR, $this->conn->errno, $this->conn->error, 1, array());
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
                return $this->OK($data, $type, 1, array());
            } else {
                return $this->Error();
            }
        }

        protected function GetFieldListStr($colArray) {
            $columns = '';
            $first = '';
            if(!$colArray) {
                $colArray = $this->fieldList;
            }
            foreach ($colArray as $value) {
                $columns = $columns . $first . $value;
                $first = ',';
            }
            return $columns;
        }

        protected function QueryGet($sqlText, $columns) {
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
                return $this->OK($dataArr, RESTYPE::RECORDSET, $result->num_rows, $columns);
            } else if ($this->conn->errno) {
                return $this->Error();
            } else {
                return $this->OK(array(), RESTYPE::RECORDSET, 0, $columns);
            }
        }

        public function getResult() {
            return json_encode($this->result);
        }
    }
?>