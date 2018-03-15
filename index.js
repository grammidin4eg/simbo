window.onload = function() {
   function simbo(method, params, callbackm) {
      var data = {
         'OBJ': 'simbo_test',
         'MET': method,
         'PARAMS': params
      };
      console.log('[REQUEST]', data);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'simBL.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

      // send the collected data as JSON
      xhr.send(JSON.stringify(data));

      xhr.onreadystatechange = function() {
         if (xhr.readyState != 4) return;

         console.log('[onreadystatechange]', 'status', xhr.status, 'statusText', xhr.statusText, 'responseText', xhr.responseText);
         console.log('[RESULT]', JSON.parse(xhr.responseText));

         var res = new BLRes(JSON.parse(xhr.responseText), ['id', 'field1', 'field2', 'field3']);

         if( callbackm ) {
            callbackm(res);
         }

         //if (xhr.status != 200)
      }

   }

   function refreshList() {
      simbo('List', '', function(list) {
         console.log('list', list.getList());
         var firstId = list.at(0).get('id');
         console.log('firstId', firstId);
         simbo('Get', [firstId], function(getObj) {
            console.log('getObj', getObj);
         });
      });
   }

   document.getElementById('AddBtn').onclick = function() {
      var text = document.getElementById('AddText').value;
      simbo('Add', [(new Date()).toLocaleDateString(), text, 'param3'], function() {
         console.log('refresh after add');
         refreshList();
      });
   }

   refreshList();

 };
