<?php
class SimboExecuter extends SimboObject {
    public function executeMethod() {
        $this->Query($this->getDelRowSql($this->getParams()->id));
        return $this->getResult();
    }
}
?>