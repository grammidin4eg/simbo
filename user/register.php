<?php
class SimboExecuter extends SimboObject {
    public function executeMethod() {
        //проверить, еслить ли уже такой пользователь
        //если нет - добавить в базу
        $columns = array('text', 'important', 'done');
        $values = array($this->getParams()->value, 'FALSE', 'FALSE');
        $this->Query($this->getAddRowSql($columns, $values));
        return $this->getResult();
    }
}
?>