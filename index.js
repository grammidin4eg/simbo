window.onload = function() {
   document.getElementById('AddBtn').onclick = function() {
      var text = document.getElementById('AddText').value;
      var data = {
         'OBJ': 'objName',
         'MET': 'methodName',
         'PARAMS': ['param1', 'param2', 'param3']
      };
      console.log('insert row', data);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'simBL.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

      // send the collected data as JSON
      xhr.send(JSON.stringify(data));

      xhr.onloadend = function () {
         console.log('done', arguments);
      }

      xhr.onreadystatechange = function() {
         if (xhr.readyState != 4) return;

         console.log('onreadystatechange', 'status', xhr.status, 'statusText', xhr.statusText, 'responseText', xhr.responseText);

         //if (xhr.status != 200)
       }
  }

 };
