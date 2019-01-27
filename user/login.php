<?php
class SimboExecuter extends SimboObject {
    public function executeMethod() {
        $columns = array('id', 'text', 'important', 'done');
        $format = array(FORMAT::INT, FORMAT::STRING, FORMAT::BOOLEAN, FORMAT::BOOLEAN);
        $sql = "SELECT " . $this->GetFieldListStr($columns) . " FROM ".$this->getObjName();
        $this->QueryGet($sql, $columns, $format);
        return $this->getResult();
    }
}
?>