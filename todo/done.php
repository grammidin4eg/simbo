<?php
class SimboExecuter extends SimboObject {
    public function executeMethod() {
        $this->getRow(array('done'), 'id = '.$this->getParams()->id);
        if ($this->isError()) {
            return $this->getResult();
        }
        $res = $this->getResDataRow();
        $boolValue = $this->getSqlBool($res[0]);
        $setArray = array('done' => !$boolValue);
        $this->Query($this->getUpdateRowSql($this->getParams()->id, $setArray));
        return $this->getResult();
    }
}
?>