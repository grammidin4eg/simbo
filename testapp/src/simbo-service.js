export default class SimboService {
    constructor(object) {
        this.object = object;
    }

    exec(_method, _params, _navigation) {
        return new Promise((resolve, reject) => {
            const data = {
                'OBJECT': this.object,
                'METHOD': _method,
                'NAVIGATION': _navigation,
                'PARAMS': _params
            };
            /*
            NAVIGATION.pagesize
            NAVIGATION.page
            NAVIGATION.type
            */
            console.log('[REQUEST]', data);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'simBL.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

            // send the collected data as JSON
            xhr.send(JSON.stringify(data));

            xhr.onreadystatechange = function() {
                if (xhr.readyState !== xhr.DONE) return;

                console.log('[onreadystatechange]', 'status', xhr.status, 'statusText', xhr.statusText, 'responseText', xhr.responseText);
                if (xhr.status !== 200) {
                    //ошибка
                    console.log('[ERROR]', xhr.statusText)
                    reject(new Error(xhr.statusText));
                } else {
                    console.log('[RESULT]', JSON.parse(xhr.responseText));
                    resolve(JSON.parse(xhr.responseText));
                }

            }
        });
    }
}