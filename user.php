<?php
   include 'simbo.php';
   class UserObject extends SimboObject
   {
      public function Registration($inputParams) {
         //id, name, hkey, email
         //inputParams
         //-email
         //-password
         //-fields

         //ищем email
         $findEmail = $this->_findRec('email='.$inputParams->email);
         //если есть - кидаем ошибку
         if( $findEmail->num_rows > 0 ) {
            return $this->so->Error('UserRegistrationEmail', 'email is already exist');
         }
         //создаем хэш
         $fullhash = $inputParams->email.$inputParams->password;
         $hash = password_hash($fullhash, PASSWORD_DEFAULT);
         $inputParams->fields['hkey'] = $hash;
         //создать запись
         return $this->AddRow($inputParams->fields);
      }

      public function LoginKey($key) {
         //ищем по хэшу
         $find = $this->_findRec('hkey='.$key);
         //если не найдено - кидаем ошибку
         if( $find->num_rows < 1 ) {
            return $this->so->Error('UserLoginKey', 'wrong login password');
         }

         //вернуть данные пользователя
         //получить id
         $row = $find->fetch_assoc();
         return $this->so->Result('OK', array('COUNT' => $find->num_rows, 'LIST' => $row ), 'NOERROR');
         //$id = $row['id'];
         //return $object->GetData($id);
      }

      public function LoginPassword($login, $password) {
         //создаем хэш
         $fullhash = $inputParams->email.$inputParams->password;
         $hash = password_hash($fullhash, PASSWORD_DEFAULT);
         return $this->LoginKey($hash);
      }

      public function Confirm($obj) {
         $params = $obj->PARAMS;
         $id = $obj->ID;
         if( $obj->MET == 'Registration' ) {
            return $this->Registration($params);
         }

         if( $obj->MET == 'LoginKey' ) {
            return $this->LoginKey($id);
         }

         if( $obj->MET == 'LoginPassword' ) {
            return $this->LoginPassword($params[0], $params[1]);
         }

         if( $obj->MET == 'GetUserParam' ) {
            return $this->GetData($id);
         }

         if( $obj->MET == 'SetUserParam' ) {
            return $this->SetData($id, $params);
         }

         return $this->so->Error('NOACTION', 'no action in bl');
      }
   }
?>