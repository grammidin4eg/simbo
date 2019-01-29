<?php
class SimboExecuter extends SimboObject {
    public function executeMethod() {
        $params = $this->getParams();
        //проверить, есть ли уже такой пользователь

        $this->getRow(array('id', 'password'), 'login = '.$this->textQuote($params->login));
        if ($this->isError()) {
            return $this->getResult();
        }
        if ($this->getResCount() < 1) {
            return $this->getErrorResult('LOGIN_USER_NOT_FOUND','user not found');
        }
        //если есть - получим хэш и проверим его
        $row = $this->getResDataRow();
        //$this->LogJSON($row);
        //$pass = '';
        $isLogin = password_verify($params->password, $row[1]);
        if (!$isLogin) {
            return $this->getErrorResult('LOGIN_FAILED','login failed');
        }

        include 'utils.php';
        $utils = new UserUtils($this->inObj, $this->dbParam);
        $utils->getUserDataRow($row[0]);
        return $utils->getResult();
    }
}
?>