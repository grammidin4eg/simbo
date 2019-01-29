<?php
class UserUtils extends SimboObject {
    public function getUserDataRow($id) {
        $columns = array('name', 'foto', 'role', 'isadmin');
        $format = array(FORMAT::STRING, FORMAT::STRING, FORMAT::STRING, FORMAT::BOOLEAN);
        $sql = "SELECT user.name as name, user.foto as foto, role.name as role, role.admin as isadmin FROM ".$this->getObjName()." LEFT JOIN role ON user.role = role.id WHERE user.id=".$id;

        return $this->QueryGet($sql, $columns, $format);
    }
}
?>