<?php
    class TodoList extends SimboObject {
        public function executeMethod() {
            $columns = array('id', 'text', 'important', 'done');
            $sql = "SELECT " . $this->GetFieldListStr($columns) . " FROM todo";
            $this->QueryGet($sql, $columns);
            return $this->getResult();
        }
    }
?>