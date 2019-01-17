window.onload = function() {
   function simbo(method, id, params, callbackm) {
      var data = {
         'OBJ': 'simbo_test',
         'MET': method,
         'ID': id,
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
      simbo('List', 0, [], function(list) {
         console.log('list', list.getList());
         var firstId = list.at(0).get('id');
         console.log('firstId', firstId);
         simbo('Get', firstId, [], function(getObj) {
            console.log('getObj', getObj);
            simbo('Set', firstId, ['set1', 'set2'], function(setObj) {
               console.log('setObj', setObj);
            });
         });
      });
   }

   document.getElementById('AddBtn').onclick = function() {
      var text = document.getElementById('AddText').value;
      simbo('Add', 0, [(new Date()).toLocaleDateString(), text, 'param3'], function() {
         console.log('refresh after add');
         refreshList();
      });
   }

   document.getElementById('DelBtn').onclick = function() {
      var delId = parseInt(document.getElementById('DelText').value, 10);
      simbo('Del', delId, [], function() {
         console.log('refresh after del');
         refreshList();
      });
   }

   refreshList();

 };
