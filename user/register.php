<?php
class SimboExecuter extends SimboObject {
    public function executeMethod() {
        $params = $this->getParams();
        //проверить, есть ли уже такой пользователь
        $this->getRow(array('id'), 'login = "'.$params->login.'"');
        if ($this->isError()) {
            return $this->getResult();
        }
        if ($this->getResCount() > 0) {
            return $this->getErrorResult('USER_ALREADY_EXISTS','user already exist');
        }
        //если нет - добавить в базу
        $pass = password_hash($params->password, PASSWORD_DEFAULT);
        $columns = array('login', 'name', 'password');
        $values = array($params->login, $params->name, $pass);
        $this->Query($this->getAddRowSql($columns, $values));
        if ($this->isError()) {
            return $this->getResult();
        }
        $insId = $this->getResInsertId();
        $this->Log('reg user: '.$insId);

        include 'utils.php';
        $utils = new UserUtils($this->inObj, $this->dbParam);
        $utils->getUserDataRow($insId);

        return $utils->getResult();
    }
}
?>