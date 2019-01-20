<?php
class SimboExecuter extends SimboObject {
    public function executeMethod() {
        $columns = array('text', 'important', 'done');
        $values = array($this->getParams()->value, 'FALSE', 'FALSE');

        $this->Query($this->getAddRowSql($columns, $values));
        return $this->getResult();
    }
}
?>