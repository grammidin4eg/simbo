export default class SimboServiceVirtual {
    constructor(object) {
        this.object = object;
        this.lastResult = null;
        this.data  = [
            {id: 0, text: 'Пункт 1. Важно.', done: false, important: true},
            {id: 1, text: 'Пункт 2. В работе.', done: false, important: false},
            {id: 2, text: 'Пункт 3. Выполнен.', done: true, important: false}
        ];
        this.lastId = 2;
    }

    exec(_method, _params, _navigation) {
        let data;
        switch (_method) {
            case 'list':
                data = this.data;
                break;
            case 'testerror':
                data='error';
                break;
            case 'add':
                this.lastId += 1;
                this.data.push({id: this.lastId, text: _params.value, done: false, important: true});
                data=this.lastId;
                break;
            case 'delete':
                const ind = this.data.findIndex((item) => {
                    return (item.id === _params.id);
                });
                this.data.splice(ind, 1);
                data = 'OK';
                break;
            case 'done':
                this.data.map((item) => {
                    if (item.id === _params.id) {
                        item.done = !item.done;
                    }
                    return item;
                });
                data = 'OK';
                break;
            case 'important':
                this.data.map((item) => {
                    if (item.id === _params.id) {
                        item.important = !item.important;
                    }
                    return item;
                });
                data = 'OK';
                break;
            default:
                data = null;
                break;
        }
        return new Promise((resolve, reject) => {
            if (data === 'error') {
                reject(new Error('test error'));
            } else {
                resolve(data);
            }
        });
    }
}