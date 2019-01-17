<?php
    include 'dbconf.php';

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

        function __construct($inObj) {
            $this->inObj = $inObj;
        }

        private function createResultObject($result, $type, $data, $error, $count) {
            $this->result = array('RESULT' => $result, 'TYPE' => $type, 'DATA' => $data, 'ERROR' => $error, 'COUNT' => $count);
        }

        public function getResult() {
            return json_encode($this->result);
        }

        public function test() {
            $this->createResultObject(RESULT::OK, RESTYPE::SCALAR, 11, '', 1);
        }
    }
?>