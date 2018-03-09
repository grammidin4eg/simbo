window.onload = function() {
   function simbo(method, params, callbackm) {
      var data = {
         'OBJ': 'simbo_test',
         'MET': method,
         'PARAMS': params
      };
      console.log('simbo', data);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'simBL.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

      // send the collected data as JSON
      xhr.send(JSON.stringify(data));

      xhr.onreadystatechange = function() {
         if (xhr.readyState != 4) return;

         console.log('onreadystatechange', 'status', xhr.status, 'statusText', xhr.statusText, 'responseText', xhr.responseText);
         console.log('RESULT', JSON.parse(xhr.responseText));

         //if (xhr.status != 200)
      }

      if( callbackm ) {
         callbackm(JSON.parse(xhr.responseText));
      }
   }

   function refreshList() {
      simbo('List', '', function(list) {
         console.log('list', list);
         //render list
      });
   }

   document.getElementById('AddBtn').onclick = function() {
      var text = document.getElementById('AddText').value;
      simbo('Add', [(new Date()).toLocaleDateString(), text, 'param3'], function() {
         refreshList();
      });
   }

   refreshList();

 };
