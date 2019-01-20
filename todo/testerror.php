<?php
class SimboExecuter extends SimboObject {
    public function executeMethod() {
        $this->QueryGet("SELECT * FROM azaza", array('id'), array(FORMAT::INT));
        return $this->getResult();
    }
}
?>