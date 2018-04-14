<?php
   class UserObject extends SimboObject
   {
      public function Registration($inputParams) {
         //id, name, hkey, email
         //inputParams
         //email
         //password
         //fields
         //'age': age,
         //'weight': weight

         //ищем email
         $findEmail = $this->_findRec('email=\''.$inputParams->email.'\'', null);
         //если есть - кидаем ошибку
         if( $findEmail->num_rows > 0 ) {
            return $this->getSimboObject()->Error('UserRegistrationEmail', 'email is already exist');
         }
         //создаем хэш
         $fullhash = $inputParams->email.$inputParams->password;
         $hash = md5($fullhash);
         $hashArr = array("email" =>  $inputParams->email, "name" =>  $inputParams->name, "hkey" => $hash, "age" =>  $inputParams->age, "weight" =>  $inputParams->weight);
         //$inputParams->fields['hkey'] = $hash;
         //$inputParams->fields = array_merge($inputParams->fields, $hashArr);
         //создать запись
         return $this->AddRow($hashArr);
      }

      public function LoginKey($key) {
         $this->Log($key);
         //ищем по хэшу
         $find = $this->_findRec('hkey=\''.$key.'\'', array('id', 'name', 'weight', 'email', 'age'));
         //если не найдено - кидаем ошибку
         if( $find->num_rows < 1 ) {
            return $this->getSimboObject()->Error('UserLoginKey', 'wrong login password');
         }

         //вернуть данные пользователя
         //получить id
         $row = $find->fetch_assoc();
         return $this->getSimboObject()->Result('OK', array('COUNT' => $find->num_rows, 'LIST' => $row ), 'NOERROR');
         //$id = $row['id'];
         //return $object->GetData($id);
      }

      public function LoginPassword($login, $password) {
         //создаем хэш
         $fullhash = $login.$password;
         $hash = md5($fullhash);
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
            return $this->LoginPassword($params->login, $params->password);
         }

         if( $obj->MET == 'GetUserParam' ) {
            return $this->GetData($id);
         }

         if( $obj->MET == 'SetUserParam' ) {
            return $this->SetData($id, $params);
         }

         return $this->getSimboObject()->Error('NOACTION', 'no action in bl');
      }
   }
?>